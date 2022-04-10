<?php
namespace Objects;
/*
 * Class imports
 */

use Enumerators\Availability;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use mysqli;

/*
 * Object Imports
 */


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

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;


class Resource_old {

    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?String $title = null;
    private ?String $description = null;
    private ?String $extension = null;
    private ?String $path = null;
    private ?Availability $available = null;

    // RELATIONS

    private array $flags;
    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
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
                $this->title = $row["title"];
                $this->description = $row["description"];
                $this->extension = $row["extension"];
                $this->path = $row["path"];
                $this->available = Availability::getItem($row["available"]);
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @return Resource
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store() : Resource{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $database->query("START TRANSACTION");
        $query_keys_values = array(
            "id" => $this->id != null ? $this->id : Database::getNextIncrement("resource"),
            "title" => $this->title,
            "description" => $this->description,
            "extension" => $this->extension,
            "path" => $this->path,
            "available" => $this->available?->value
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "resource")) {
                $size = Database::getColumnSize(column: $key, table: "resource");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'resource') && $value == null){
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from resource where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "resource") && !Database::isUniqueValue(column: $key, table: "resource", value: $value)) throw new UniqueKey($key);
            }
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO resource ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "resource") && !Database::isUniqueValue(column: $key, table: "resource", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE resource SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        $database->query("COMMIT");
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return Resource
     * @throws IOException
     */
    public function remove() : Resource{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $this->available = Availability::NOT_AVAILABLE;
        $sql = "UPDATE season SET available = '$this->available->value' WHERE id = $this->id";
        $database->query($sql);
        return $this;
    }

    /**
     * @param int|null $id
     * @param Availability $available
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     * @throws \ReflectionException
     */
    public static function find(int $id = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
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
                ($available != null ? "(available != null AND available = '$available->value')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Resource($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed|null", "title" => "mixed|null|String", "description" => "mixed|null|String", "extension" => "mixed|null|String", "path" => "mixed|null|String", "available" => "array|null"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "extension" => $this->extension,
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
     * @return String
     */
    public function getTitle(): String
    {
        return $this->title;
    }

    /**
     * @param String $title
     * @return Resource
     */
    public function setTitle(String $title): Resource
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return String
     */
    public function getDescription(): String
    {
        return $this->description;
    }

    /**
     * @param String $description
     * @return Resource
     */
    public function setDescription(String $description): Resource
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return String
     */
    public function getExtension(): String
    {
        return $this->extension;
    }

    /**
     * @param String $extension
     * @return Resource
     */
    public function setExtension(String $extension): Resource
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return String
     */
    public function getPath(): String
    {
        return $this->path;
    }

    /**
     * @param String $path
     * @return Resource
     */
    public function setPath(String $path): Resource
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
     * @return Resource
     */
    public function setAvailable(?Availability $available): Resource
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags;
    }
}
?>