<?php
namespace Objects;
/*
 * Class imports
 */

use DateTime;
use Enumerators\Availability;
use Exceptions\DependentArgument;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/*
 * Object Imports
 */

use mysqli;

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


class Season_old {

    // Database
    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;
    public const VIDEOS = 2;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?int $numeration = null;
    private ?String $name = null;
    private ?String $synopsis = null;
    private ?DateTime $release_date = null;
    private ?Availability $available = null;

    // RELATIONS

    private array $flags;

    // SEASON::VIDEOS
    private ?array $videos = null;

    /**
     * @param int $id
     * @param array $flags
     * @throws RecordNotFound
     * @throws \ReflectionException
     */
    function __construct(int $id, array $flags = array(self::NORMAL)) {
        $this->flags = $flags;
        try {
            $this->database = Database::getConnection();
        } catch(IOException $e){
            $this->database = null;
        }
        if ($id != null && $this->database != null){
            $database = $this->database;
            $query = $database->query("SELECT * FROM season WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->numeration = $row["numeration"];
                $this->name = $row["name"];
                $this->synopsis = $row["synopsis"];
                $this->release_date = $row["release_date"] != "" ? Database::convertDateFromDatabase($row["release_date"]) : null;
                $this->available = Availability::getItem($row["available"]);
                // RELATIONS
                if(in_array(self::VIDEOS, $this->flags) || in_array(self::ALL, $this->flags)){
                    $this->videos = array();
                    $query = $database->query("SELECT id FROM video WHERE season = $id;");
                    while($row = $query->fetch_array()){
                        $this->videos[] = new Video($row["id"]);
                    }
                }
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @param Anime $anime
     * @return Season
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws RecordNotFound
     * @throws TableNotFound
     * @throws UniqueKey
     * @throws \ReflectionException
     */
    public function store(Anime $anime) : Season{
        if ($this->database == null) throw new IOException("Could not access database services.");
        if (!isset($anime)) throw new NotNullable(argument: 'anime');
        $database = $this->database;
        $database->query("START TRANSACTION");
        $query_keys_values = array(
            "id" => $this->id != null ? $this->id : Database::getNextIncrement("season"),
            "anime" => $anime->getId(),
            "numeration" => $this->numeration,
            "name" => $this->name,
            "synopsis" => $this->synopsis,
            "release_date" => $this->release_date != null ? Database::convertDateToDatabase($this->release_date) : null,
            "available" => $this->available?->value
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "season")) {
                $size = Database::getColumnSize(column: $key, table: "season");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'season') && $value == null){
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from season where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "season") && !Database::isUniqueValue(column: $key, table: "season", value: $value)) throw new UniqueKey($key);
            }
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO season ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "season") && !Database::isUniqueValue(column: $key, table: "season", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE season SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        // RELATIONS
        if (in_array(self::VIDEOS, $this->flags) || in_array(self::ALL, $this->flags)) {
            $query = $database->query("SELECT id FROM video WHERE season = $this->id AND available = '" . Availability::AVAILABLE->value . "';");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->videos as $video) {
                    if ($video->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) (new Video($row["id"]))->remove();
            }
            foreach ($this->videos as $video) {
                $video->store(anime: $anime, season: $this);
            }
        }
        $database->query("COMMIT");
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return Season
     * @throws IOException
     */
    public function remove() : Season{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $this->available = Availability::NOT_AVAILABLE;
        $sql = "UPDATE season SET available = '$this->available->value' WHERE id = $this->id";
        $database->query($sql);
        return $this;
    }

    /**
     * @param int|null $id
     * @param int|null $anime
     * @param Availability $available
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     * @throws \ReflectionException
     */
    public static function find(int $id = null, int $anime = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from video_type WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from video_type WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($available != null ? "(available != null AND available = '$available->value')" : "") .
                ($anime != null ? "(anime != null AND anime = '$anime')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Season($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed|null", "numeration" => "int|mixed|null", "name" => "mixed|null|String", "synopsis" => "mixed|null|String", "release_date" => "bool|\DateTime|null", "available" => "array|null"])]
    public function toArray(): array
    {
        $array = array(
            "id" => $this->id,
            "numeration" => $this->numeration,
            "name" => $this->name,
            "synopsis" => $this->synopsis,
            "release_date" => $this->release_date != null ? Database::convertDateToDatabase($this->release_date) : null,
            "available" => $this->available?->toArray()
        );
        $array["videos"] = $this->videos != null ? array() : null;
        if($array["videos"] != null) foreach($this->videos as $value) $array["videos"][] = $value->toArray();
        return $array;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|mixed|null
     */
    public function getNumeration(): mixed
    {
        return $this->numeration;
    }

    /**
     * @param int|mixed|null $numeration
     * @return Season
     */
    public function setNumeration(mixed $numeration): Season
    {
        $this->numeration = $numeration;
        return $this;
    }

    /**
     * @return mixed|String|null
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @param mixed|String|null $name
     * @return Season
     */
    public function setName(mixed $name): Season
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed|String|null
     */
    public function getSynopsis(): mixed
    {
        return $this->synopsis;
    }

    /**
     * @param mixed|String|null $synopsis
     * @return Season
     */
    public function setSynopsis(mixed $synopsis): Season
    {
        $this->synopsis = $synopsis;
        return $this;
    }

    /**
     * @return bool|DateTime|null
     */
    public function getReleaseDate(): DateTime|bool|null
    {
        return $this->release_date;
    }

    /**
     * @param bool|DateTime|null $release_date
     * @return Season
     */
    public function setReleaseDate(DateTime|bool|null $release_date): Season
    {
        $this->release_date = $release_date;
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
     * @return Season
     */
    public function setAvailable(?Availability $available): Season
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