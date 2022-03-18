<?php
namespace Objects;
/*
 * Class imports
 */

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
use Enumerators\Availability;
/*
 * Others
 */
use Functions\Database;


class Video {

    // Database
    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;
    public const SUBTITLES = 2;
    public const DUBBING = 3;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?VideoType $video_type = null;
    private ?int $numeration = null;
    private ?String $title = null;
    private ?String $synopsis = null;
    private ?int $duration = null;
    private ?int $opening_start = null;
    private ?int $opening_end = null;
    private ?int $ending_start = null;
    private ?int $ending_end = null;
    private ?String $path = null;
    private ?Availability $available = null;

    // RELATIONS

    private array $flags;

    // Video::Subtitles
    private ?array $subtitles = null;
    // Video::Dubbing
    private ?array $dubbing = null;

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
            $query = $database->query("SELECT * FROM video WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->video_type = $row["video_type"] != "" ? new VideoType($row["video_type"]) : null;
                $this->numeration = $row["numeration"];
                $this->title = $row["title"];
                $this->synopsis = $row["synopsis"];
                $this->duration = $row["duration"];
                $this->opening_start = $row["opening_start"];
                $this->opening_end = $row["opening_end"];
                $this->ending_start = $row["ending_start"];
                $this->ending_end = $row["ending_end"];
                $this->path = $row["path"];
                $this->available = Availability::getAvailability($row["available"]);
                // RELATIONS
                if(in_array(self::SUBTITLES, $this->flags) || in_array(self::ALL, $this->flags)){
                    $this->subtitles = array();
                    $query = $database->query("SELECT id FROM subtitle WHERE video = $id;");
                    while($row = $query->fetch_array()){
                        $this->subtitles[] = new Subtitle($row["id"]);
                    }
                }
                if(in_array(self::DUBBING, $this->flags) || in_array(self::ALL, $this->flags)){
                    $this->dubbing = array();
                    $query = $database->query("SELECT id FROM dubbing WHERE video = $id;");
                    while($row = $query->fetch_array()){
                        $this->dubbing[] = new Dubbing($row["id"]);
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
     * @throws NotNullable
     * @throws RecordNotFound
     */
    public function store(Anime $anime, ?Season $season = null) : Video{
        if ($this->database == null) throw new IOException("Could not access database services.");
        if (!isset($anime)) throw new NotNullable(argument: 'anime');
        $database = $this->database;
        $query_keys_values = array(
            "id" => $this->id,
            "anime" => $anime->getId(),
            "season" => $season?->getId(),
            "video_type" => isset($this->video_type) ? $this->video_type->getId() : null,
            "numeration" => $this->numeration,
            "title"=> $this->title,
            "synopsis"=> $this->synopsis,
            "duration"=> $this->duration,
            "opening_start"=> $this->opening_start,
            "opening_end"=> $this->opening_end,
            "ending_start" => $this->ending_start,
            "ending_end" => $this->ending_end,
            "path" => $this->path,
            "available" => isset($this->available) ? $this->available->value : null
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "video")) {
                $size = Database::getColumnSize(column: $key, table: "video");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'video') && $value == null){
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from video where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "video") && !Database::isUniqueValue(column: $key, table: "video", value: $value)) throw new UniqueKey($key);
            }
            $this->id = Database::getNextIncrement("video");
            $query_keys_values["id"] = $this->id;
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO video ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "video") && !Database::isUniqueValue(column: $key, table: "video", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE video SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        if (in_array(self::SUBTITLES, $this->flags) || in_array(self::ALL, $this->flags)) {
            $query = $database->query("SELECT id FROM subtitle WHERE video = $this->id AND available = '" . Availability::AVAILABLE->value . "';");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->subtitles as $value) {
                    if ($value->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) (new Subtitle($row["id"]))->remove();
            }
            foreach ($this->subtitles as $value) {
                $value->store(video: $this);
            }
        }
        if (in_array(self::DUBBING, $this->flags) || in_array(self::ALL, $this->flags)) {
            $query = $database->query("SELECT id FROM dubbing WHERE video = $this->id AND available = '" . Availability::AVAILABLE->value . "';");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->dubbing as $value) {
                    if ($value->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) (new Dubbing($row["id"]))->remove();
            }
            foreach ($this->dubbing as $value) {
                $value->store(video: $this);
            }
        }
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     * @throws IOException
     */
    public function remove() : Video{
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
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     */
    public static function find(int $id = null, int $anime = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from video WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from video WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($available != null ? "(available != null AND available = '$available->value')" : "") .
                ($anime != null ? "(anime != null AND anime = '$anime')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Video($row["id"], $flags);
        }
        return $result;
    }


    #[Pure]
    public function toArray(): array
    {
        $array = array(
            "id" => $this->id,
            "video_type" => isset($this->video_type) ? $this->video_type->toArray() : null,
            "numeration" => $this->numeration,
            "title"=> $this->title,
            "synopsis"=> $this->synopsis,
            "duration"=> $this->duration,
            "opening_start"=> $this->opening_start,
            "opening_end"=> $this->opening_end,
            "ending_start" => $this->ending_start,
            "ending_end" => $this->ending_end,
            "path" => $this->path,
            "available" => isset($this->available) ? $this->available->toArray() : null
        );
        $array["subtitles"] = $this->subtitles != null ? array() : null;
        if($array["subtitles"] != null) foreach($this->subtitles as $value) $array["subtitles"][] = $value->toArray();
        $array["dubbing"] = $this->dubbing != null ? array() : null;
        if($array["dubbing"] != null) foreach($this->dubbing as $value) $array["dubbing"][] = $value->toArray();
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
     * @return VideoType|null
     */
    public function getVideoType(): ?VideoType
    {
        return $this->video_type;
    }

    /**
     * @param VideoType|null $video_type
     * @return Video
     */
    public function setVideoType(?VideoType $video_type): Video
    {
        $this->video_type = $video_type;
        return $this;
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
     * @return Video
     */
    public function setNumeration(mixed $numeration): Video
    {
        $this->numeration = $numeration;
        return $this;
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
     * @return Video
     */
    public function setTitle(mixed $title): Video
    {
        $this->title = $title;
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
     * @return Video
     */
    public function setSynopsis(mixed $synopsis): Video
    {
        $this->synopsis = $synopsis;
        return $this;
    }

    /**
     * @return int|mixed|null
     */
    public function getDuration(): mixed
    {
        return $this->duration;
    }

    /**
     * @param int|mixed|null $duration
     * @return Video
     */
    public function setDuration(mixed $duration): Video
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return int|mixed|null
     */
    public function getOpeningStart(): mixed
    {
        return $this->opening_start;
    }

    /**
     * @param int|mixed|null $opening_start
     * @return Video
     */
    public function setOpeningStart(mixed $opening_start): Video
    {
        $this->opening_start = $opening_start;
        return $this;
    }

    /**
     * @return int|mixed|null
     */
    public function getOpeningEnd(): mixed
    {
        return $this->opening_end;
    }

    /**
     * @param int|mixed|null $opening_end
     * @return Video
     */
    public function setOpeningEnd(mixed $opening_end): Video
    {
        $this->opening_end = $opening_end;
        return $this;
    }

    /**
     * @return int|mixed|null
     */
    public function getEndingStart(): mixed
    {
        return $this->ending_start;
    }

    /**
     * @param int|mixed|null $ending_start
     * @return Video
     */
    public function setEndingStart(mixed $ending_start): Video
    {
        $this->ending_start = $ending_start;
        return $this;
    }

    /**
     * @return int|mixed|null
     */
    public function getEndingEnd(): mixed
    {
        return $this->ending_end;
    }

    /**
     * @param int|mixed|null $ending_end
     * @return Video
     */
    public function setEndingEnd(mixed $ending_end): Video
    {
        $this->ending_end = $ending_end;
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
     * @return Video
     */
    public function setPath(mixed $path): Video
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
     * @return Video
     */
    public function setAvailable(?Availability $available): Video
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @return array
     */
    public function getSubtitles(): array
    {
        return $this->subtitles;
    }

    /**
     * @param array $subtitles
     * @return Video
     */
    public function setSubtitles(array $subtitles): Video
    {
        $this->subtitles = $subtitles;
        return $this;
    }

    /**
     * @return array
     */
    public function getDubbing(): array
    {
        return $this->dubbing;
    }

    /**
     * @param array $dubbing
     * @return Video
     */
    public function setDubbing(array $dubbing): Video
    {
        $this->dubbing = $dubbing;
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