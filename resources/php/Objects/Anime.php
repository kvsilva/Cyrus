<?php
namespace Objects;
/*
 * Class imports
 */

use DateTime;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use mysqli;

/*
 * Object Imports
 */

use Objects\Audience;
use Objects\SourceType;
use Objects\Video;
use Objects\Season;

/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\TableNotFound;

/*
 * Enumerator Imports
 */
use Enumerators\Availability;
use Enumerators\DayOfWeek;
use Enumerators\Maturity;
/*
 * Others
 */
use Functions\Database;


class Anime {

    // Database
    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;
    public const VIDEOS = 2;
    public const SEASONS = 3;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?String $title = null;
    private ?String $original_title = null;
    private ?String $synopsis = null;
    private ?DateTime $start_date = null;
    private ?DateTime $end_date = null;
    private ?Maturity $mature = null;
    private ?DayOfWeek $launch_day = null;
    private ?SourceType $source = null;
    private ?Audience $audience = null;
    private ?String $trailer = null;
    private ?Availability $available = null;

    // RELATIONS

    private array $flags;

    // Anime::Videos
    private array $videos = array();
    // Anime::Seasons
    private array $seasons = array();

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
            $query = $database->query("SELECT * FROM anime WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->title = $row["title"];
                $this->original_title = $row["original_title"];
                $this->synopsis = $row["synopsis"];
                $this->start_date = $row["start_date"] != "" ? DateTime::createFromFormat(Database::DateFormat, $row["start_date"]) : null;
                $this->end_date = $row["end_date"] != "" ? DateTime::createFromFormat(Database::DateFormat, $row["end_date"]) : null;
                $this->mature = Maturity::getMaturity($row["mature"]);
                $this->launch_day = DayOfWeek::getDayOfWeek($row["launch_day"]);
                $this->source = $row["source"] != "" ? new SourceType($row["source"]) : null;
                $this->audience = $row["audience"] != "" ? new Audience($row["audience"]) : null;
                $this->trailer = $row["trailer"];
                $this->available = $row["available"];
                // Um vídeo pode pertencer a um anime ou também a uma season
                // Procedimento: os videos serão incluídos primeiro nas seasons, e os que não tiver mesmo season, no videos
                if(in_array(self::SEASONS, $this->flags) || in_array(self::ALL, $this->flags)){
                    $query = $database->query("SELECT numeration as 'id' FROM season WHERE anime = $id;");
                    while($row = $query->fetch_array()){
                        $this->seasons[] = new Season($row["id"]);
                    }
                }
                if(in_array(self::VIDEOS, $this->flags) || in_array(self::ALL, $this->flags)){
                    $query = $database->query("SELECT id FROM video WHERE anime = $id AND season = null;");
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
     * @return $this
     * @throws IOException
     * @throws InvalidSize
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws TableNotFound
     */
    public function store() : Anime{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $query_keys_values = array(
            "id" => $this->id,
            "title" => $this->title,
            "original_title" => $this->original_title,
            "synopsis" => $this->synopsis,
            "start_date" => isset($this->start_date) ? $this->start_date->format(Database::DateFormat) : null,
            "end_date" => isset($this->end_date) ? $this->end_date->format(Database::DateFormat) : null,
            "mature" => isset($this->mature) ? $this->mature->value : null,
            "launch_day" => isset($this->launch_day) ? $this->launch_day->value : null,
            "source" => isset($this->source) ? $this->source->store()->getId() : null,
            "audience" => isset($this->audience) ? $this->audience->store()->getId() : null,
            "trailer" => $this->trailer,
            "available" => isset($this->available) ? $this->available->value : null

        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "anime")) {
                $size = Database::getColumnSize(column: $key, table: "anime");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            }
        }
        if($this->id == null || $database->query("SELECT id from anime where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "anime") && !Database::isUniqueValue(column: $key, table: "anime", value: $value)) throw new UniqueKey($key);
            }
            $this->id = Database::getNextIncrement("anime");
            $query_keys_values["id"] = $this->id;
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO anime ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "anime") && !Database::isUniqueValue(column: $key, table: "anime", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE anime SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function remove() : Anime{
        $database = $this->database;
        $this->available = Availability::NOT_AVAILABLE;
        $this->store();
        return $this;
    }

    /**
     * @param int|null $id
     * @param string|null $title
     * @param DayOfWeek|null $launch_day
     * @param Availability $availability
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     */
    public static function find(int $id = null, string $title = null, DayOfWeek $launch_day = null, Availability $availability = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from anime WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from anime WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($title != null ? "($title != null AND $title = '$title')" : "") .
                ($launch_day != null ? "(launch_day != null AND launch_day = '$launch_day->value')" : "")
                ($availability != null ? "(availability != null AND availability = '$availability->value')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Anime($row["id"], $flags);
        }
        return $result;
    }


    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "title" => $this->title,
            "original_title" => $this->original_title,
            "synopsis" => $this->synopsis,
            "start_date" => isset($this->start_date) ? $this->start_date->format(Database::DateFormat) : null,
            "end_date" => isset($this->end_date) ? $this->end_date->format(Database::DateFormat) : null,
            "mature" => isset($this->mature) ? $this->mature->toArray() : null,
            "launch_day" => isset($this->launch_day) ? $this->launch_day->toArray() : null,
            "source" => isset($this->source) ? $this->source->toArray() : null,
            "audience" => isset($this->audience) ? $this->audience->toArray() : null,
            "trailer" => $this->trailer,
            "available" => isset($this->available) ? $this->available->toArray() : null
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
     * @return mixed|String|null
     */
    public function getTitle(): mixed
    {
        return $this->title;
    }

    /**
     * @param mixed|String|null $title
     * @return Anime
     */
    public function setTitle(mixed $title): Anime
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed|String|null
     */
    public function getOriginalTitle(): mixed
    {
        return $this->original_title;
    }

    /**
     * @param mixed|String|null $original_title
     * @return Anime
     */
    public function setOriginalTitle(mixed $original_title): Anime
    {
        $this->original_title = $original_title;
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
     * @return Anime
     */
    public function setSynopsis(mixed $synopsis): Anime
    {
        $this->synopsis = $synopsis;
        return $this;
    }

    /**
     * @return DateTime|false|null
     */
    public function getStartDate(): DateTime|bool|null
    {
        return $this->start_date;
    }

    /**
     * @param DateTime|String $start_date
     * @return Anime
     */
    public function setStartDate(DateTime|String $start_date): Anime
    {
        if(is_string($start_date)){
            $this->start_date = DateTime::createFromFormat(Database::DateFormat, $start_date);
        } else if(is_a($start_date, "DateTime")){
            $this->start_date = $start_date;
        }
        return $this;
    }

    /**
     * @return DateTime|false|null
     */
    public function getEndDate(): DateTime|bool|null
    {
        return $this->end_date;
    }

    /**
     * @param DateTime|String $end_date
     * @return Anime
     */
    public function setEndDate(DateTime|String $end_date): Anime
    {
        if(is_string($end_date)){
            $this->end_date = DateTime::createFromFormat(Database::DateFormat, $end_date);
        } else if(is_a($end_date, "DateTime")){
            $this->end_date = $end_date;
        }
        return $this;
    }

    /**
     * @return Maturity|null
     */
    public function getMature(): ?Maturity
    {
        return $this->mature;
    }

    /**
     * @param Maturity|null $mature
     * @return Anime
     */
    public function setMature(?Maturity $mature): Anime
    {
        $this->mature = $mature;
        return $this;
    }

    /**
     * @return DayOfWeek|null
     */
    public function getLaunchDay(): ?DayOfWeek
    {
        return $this->launch_day;
    }

    /**
     * @param DayOfWeek|null $launch_day
     * @return Anime
     */
    public function setLaunchDay(?DayOfWeek $launch_day): Anime
    {
        $this->launch_day = $launch_day;
        return $this;
    }

    /**
     * @return mixed|SourceType|null
     */
    public function getSource(): mixed
    {
        return $this->source;
    }

    /**
     * @param mixed|SourceType|null $source
     * @return Anime
     */
    public function setSource(mixed $source): Anime
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return mixed|Audience|null
     */
    public function getAudience(): mixed
    {
        return $this->audience;
    }

    /**
     * @param mixed|Audience|null $audience
     * @return Anime
     */
    public function setAudience(mixed $audience): Anime
    {
        $this->audience = $audience;
        return $this;
    }

    /**
     * @return mixed|String|null
     */
    public function getTrailer(): mixed
    {
        return $this->trailer;
    }

    /**
     * @param mixed|String|null $trailer
     * @return Anime
     */
    public function setTrailer(mixed $trailer): Anime
    {
        $this->trailer = $trailer;
        return $this;
    }

    /**
     * @return Availability|mixed|null
     */
    public function getAvailable(): mixed
    {
        return $this->available;
    }

    /**
     * @param Availability|mixed|null $available
     * @return Anime
     */
    public function setAvailable(mixed $available): Anime
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