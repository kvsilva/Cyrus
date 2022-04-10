<?php

namespace Objects;

use DateTime;
use Enumerators\Availability;
use Enumerators\DayOfWeek;
use Enumerators\Maturity;
use Enumerators\Removal;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Exceptions\RecordNotFound;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Functions\Database;
use JetBrains\PhpStorm\Pure;
use ReflectionException;

class Anime extends Entity
{
    // FLAGS

    public const VIDEOS = 2;
    public const SEASONS = 3;
    public const GENDERS = 4;

    // DEFAULT STRUCTURE

    protected ?String $title = null;
    protected ?String $original_title = null;
    protected ?String $synopsis = null;
    protected ?DateTime $start_date = null;
    protected ?DateTime $end_date = null;
    protected ?Maturity $mature = null;
    protected ?DayOfWeek $launch_day = null;
    protected ?SourceType $source = null;
    protected ?Audience $audience = null;
    protected ?String $trailer = null;
    protected ?Availability $available = null;

    // RELATIONS

    // Anime::Videos
    private ?array $videos = null;
    // Anime::Seasons
    private ?array $seasons = null;
    // Anime::Genders
    private ?array $genders = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "anime", id: $id, flags: $flags);
    }

    /**
     * @return void
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    protected function buildRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
        if($this->hasFlag(self::SEASONS)){
            $this->seasons = array();
            $query = $database->query("SELECT numeration as 'id' FROM season WHERE anime = $id AND available = '" . Availability::AVAILABLE->value . "';");
            while($row = $query->fetch_array()){
                $this->seasons[] = new Season($row["id"], array(Entity::ALL));
            }
        }
        if($this->hasFlag(self::VIDEOS)){
            $this->videos = array();
            $query = $database->query("SELECT id FROM video as 'id' WHERE anime = $id AND season IS NULL AND available = '" . Availability::AVAILABLE->value . "';");
            while($row = $query->fetch_array()){
                $this->videos[] = new Video($row["id"]);
            }
        }
        if($this->hasFlag(self::GENDERS)){
            $this->videos = array();
            $query = $database->query("SELECT gender as 'id'FROM anime_gender WHERE anime = $id;");
            while($row = $query->fetch_array()){
                $this->videos[] = new Gender($row["id"]);
            }
        }
    }

    /**
     * @return $this
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store() : Anime{
        parent::__store();
        return $this;
    }

    /**
     * @return void
     * @throws IOException
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    #[Pure]
    protected function updateRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
        if ($this->hasFlag(self::SEASONS)) {
            $query = $database->query("SELECT id FROM season WHERE anime = $id AND available = '" . Availability::AVAILABLE->value . "';");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->seasons as $season) {
                    if ($season->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) (new Season($row["id"]))->remove();
            }
            foreach ($this->seasons as $season) {
                $season->store(anime: $this);
            }
        }
        if ($this->hasFlag(self::VIDEOS)) {
            $query = $database->query("SELECT id FROM video WHERE anime = $id AND season IS NULL AND available = '" . Availability::AVAILABLE->value . "';");
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
                $video->store(anime: $this);
            }
        }
        if ($this->hasFlag(self::GENDERS)) {
            $query = $database->query("SELECT gender as 'id' FROM anime_gender WHERE anime = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->videos as $video) {
                    if ($video->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) {
                    $database->query("DELETE FROM anime_gender where anime = $id AND gender = $row[id]");
                }
            }
            foreach ($this->genders as $gender) {
                $gender->store();
                $database->query("INSERT IGNORE INTO USER_ROLE (user, role) VALUES ($id, " . $gender->getId() . ")");
            }
        }
    }

    /**
     * @throws IOException
     */
    public function remove() : Anime{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, string $title = null, DayOfWeek $launch_day = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "title" => $title,
            "launch_day" => $launch_day?->value,
            "available" => $available?->value
        ), table: 'anime', class: 'Objects\Anime', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("anime"),
            "title" => $this->title,
            "original_title" => $this->original_title,
            "synopsis" => $this->synopsis,
            "start_date" => $this->start_date?->format(Database::DateFormat),
            "end_date" => $this->end_date?->format(Database::DateFormat),
            "mature" => $this->mature?->value,
            "launch_day" =>  $this->launch_day?->value,
            "source" => $this->source?->store()->getId(),
            "audience" => $this->audience?->store()->getId(),
            "trailer" => $this->trailer,
            "available" => $this->available?->value
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            "id" => $this->getId(),
            "title" => $this->title,
            "original_title" => $this->original_title,
            "synopsis" => $this->synopsis,
            "start_date" => $this->start_date?->format(Database::DateFormat),
            "end_date" => $this->end_date?->format(Database::DateFormat),
            "mature" => $this->mature?->value,
            "launch_day" =>  $this->launch_day?->value,
            "source" => $this->source?->getId(),
            "audience" => $this->audience?->getId(),
            "trailer" => $this->trailer,
            "available" => $this->available?->value
        );
    }

    /**
     * @return String|null
     */
    public function getTitle(): ?string
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
     * @return String|null
     */
    public function getOriginalTitle(): ?string
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
     * @return String|null
     */
    public function getSynopsis(): ?string
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
     * @return SourceType|null
     */
    public function getSource(): ?SourceType
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
     * @return Audience|null
     */
    public function getAudience(): ?Audience
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
     * @return String|null
     */
    public function getTrailer(): ?string
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
     * @return Availability|null
     */
    public function getAvailable(): ?Availability
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

}