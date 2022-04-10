<?php
namespace Objects;
/*
 * Class imports
 */

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use mysqli;

/*
 * Object Imports
 */

use Objects\Language;
use Objects\Video;

/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\TableNotFound;
use Exceptions\NotNullable;
use Enumerators\Availability;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;


class Subtitle_old {

    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?Language $language = null;
    private ?String $path = null;
    private ?Availability $available = null;

    // RELATIONS

    private array $flags;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     * @throws \ReflectionException
     */
    function __construct(int $id = null, array $flags = array(self::NORMAL)) {
        $this->flags = $flags;
        try {
            $this->database = Database::getConnection();
        } catch(IOException $e){
            $this->database = null;
        }
        if($id != null && $this->database != null){
            $database = $this->database;
            $query = $database->query("SELECT * FROM resource WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->language = $this->language != "" ? new Language($row["language"]) : null;
                $this->path = $row["path"];
                $this->available = $this->available != "" ? Availability::getItem($row["available"]) : null;
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @param Video $video
     * @return Subtitle
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store(Video $video) : Subtitle{
        if ($this->database == null) throw new IOException("Could not access database services.");
        if (!isset($video)) throw new NotNullable(argument: 'video');
        $database = $this->database;
        $database->query("START TRANSACTION");
        $query_keys_values = array(
            "id" => $this->id != null ? $this->id : Database::getNextIncrement("subtitle"),
            "video" => $video->getId(),
            "language" => $this->language,
            "path" => $this->path,
            "available" => $this->available?->value
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "subtitle")) {
                $size = Database::getColumnSize(column: $key, table: "subtitle");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'subtitle') && $value == null){
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from subtitle where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "subtitle") && !Database::isUniqueValue(column: $key, table: "subtitle", value: $value)) throw new UniqueKey($key);
            }
            $this->id = Database::getNextIncrement("subtitle");
            $query_keys_values["id"] = $this->id;
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO subtitle ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "subtitle") && !Database::isUniqueValue(column: $key, table: "subtitle", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE subtitle SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        $database->query("COMMIT");
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return Subtitle
     * @throws IOException
     */
    public function remove() : Subtitle{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $this->available = Availability::NOT_AVAILABLE;
        $sql = "UPDATE subtitle SET available = '$this->available->value' WHERE id = $this->id";
        $database->query($sql);
        return $this;
    }

    /**
     * @param int|null $id
     * @param int|null $video
     * @param Availability $available
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     * @throws \ReflectionException
     */
    public static function find(int $id = null, int $video = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from resource WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from resource WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($available != null ? "(available != null AND available = '$available->value')" : "") .
                ($video != null ? "(video != null AND video = '$video')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Subtitle($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed|null", "language" => "null|\Objects\Language", "path" => "mixed|null|String", "available" => "int|null"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "language" => $this->language,
            "path" => $this->path,
            "available" => $this->available?->toArray()
        );
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @param Language|null $language
     * @return Subtitle
     */
    public function setLanguage(?Language $language): Subtitle
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return mixed|String|null
     */
    public function getPath(): mixed
    {
        return $this->path;
    }

    /**
     * @param mixed|String|null $path
     * @return Subtitle
     */
    public function setPath(mixed $path): Subtitle
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return Availability|null
     */
    public function getAvailable(): ?Availability
    {
        return $this->available;
    }

    /**
     * @param Availability|null $available
     * @return Subtitle
     */
    public function setAvailable(?Availability $available): Subtitle
    {
        $this->available = $available;
        return $this;
    }
}
?>