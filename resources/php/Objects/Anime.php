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
use Exceptions\NotInitialized;
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
    // Imagem Catálogo
    protected ?Resource $profile = null;
    // Imagem de Fundo
    protected ?Resource $cape = null;
    protected ?DateTime $start_date = null;
    protected ?DateTime $end_date = null;
    protected ?Maturity $mature = null;
    protected ?DayOfWeek $launch_day = null;
    protected ?DateTime $launch_time = null;
    protected ?SourceType $source = null;
    protected ?Audience $audience = null;
    protected ?String $trailer = null;
    protected ?Availability $available = null;

    // RELATIONS

    // Anime::Videos
    private ?VideosArray $videos = null;
    // Anime::Seasons
    private ?SeasonsArray $seasons = null;
    // Anime::Genders
    private ?GendersArray $genders = null;

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
            $this->seasons = new SeasonsArray();
            $query = $database->query("SELECT numeration as id FROM season WHERE anime = $id AND available = '" . Availability::AVAILABLE->value . "';");
            while($row = $query->fetch_array()){
                $this->seasons[] = new Season($row["id"], array(Entity::ALL));
            }
        }
        if($this->hasFlag(self::VIDEOS)){
            $this->videos = new VideosArray();
            $query = $database->query("SELECT id FROM video as id WHERE anime = $id AND season IS NULL AND available = '" . Availability::AVAILABLE->value . "';");
            while($row = $query->fetch_array()){
                $this->videos[] = new Video($row["id"]);
            }
        }
        if($this->hasFlag(self::GENDERS)){
            $this->genders = new GendersArray();
            $query = $database->query("SELECT gender as id FROM anime_gender WHERE anime = $id;");
            while($row = $query->fetch_array()){
                $this->genders[] = new Gender($row["id"]);
            }
        }
        parent::buildRelations();
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
        parent::updateRelations();
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
                    $database->query("DELETE FROM ANIME_GENDER where anime = $id AND gender = $row[id]");
                }
            }
            foreach ($this->genders as $gender) {
                $gender->store();
                $database->query("INSERT IGNORE INTO ANIME_GENDER (anime, gender) VALUES ($id, " . $gender->getId() . ")");
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
    public static function find(int $id = null, string $title = null, DayOfWeek $launch_day = null, Availability $available = Availability::AVAILABLE, string $operator = "=", ?int $limit = null, string $orderBy = "id", string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "title" => $title,
            "launch_day" => $launch_day?->value,
            "available" => $available?->value
        ), table: 'anime', class: 'Objects\Anime', sql: $sql, operator: $operator, orderBy: $orderBy, limit: $limit, flags: $flags);
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
            "cape" => $this->cape?->store()->getId(),
            "profile" => $this->profile?->store()->getId(),
            "start_date" => $this->start_date?->format(Database::DateFormat),
            "end_date" => $this->end_date?->format(Database::DateFormat),
            "mature" => $this->mature?->value,
            "launch_day" =>  $this->launch_day?->value,
            "launch_time" => $this->launch_time?->format(Database::TimeFormat),
            "source" => $this->source?->store()->getId(),
            "audience" => $this->audience?->store()->getId(),
            "trailer" => $this->trailer,
            "available" => $this->available?->value
        );
    }

    /**
     * @param bool $minimal
     * @return array
     */
    public function toArray(bool $minimal = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "title" => $this->title,
            "original_title" => $this->original_title,
            "synopsis" => $this->synopsis,
            "cape" => $this->cape?->toArray(),
            "profile" => $this->profile?->toArray(),
            "start_date" => $this->start_date?->format(Database::DateFormat),
            "end_date" => $this->end_date?->format(Database::DateFormat),
            "mature" => $this->mature?->toArray(),
            "launch_day" => $this->launch_day?->toArray(),
            "launch_time" => $this->launch_time?->format(Database::TimeFormat),
            "source" => $this->source?->toArray(),
            "audience" => $this->audience?->toArray(),
            "trailer" => $this->trailer,
            "available" => $this->available?->toArray()
        );
        if (!$minimal) {
        // Relations
            $array["videos"] = null;
            if ($this->videos != null) {
                $array["videos"] = array();
                foreach ($this->videos as $value) $array["videos"][] = $value->toArray();
            }
            $array["genders"] = null;
            if ($this->genders != null) {
                $array["genders"] = array();
                foreach ($this->genders as $value) $array["genders"][] = $value->toArray();
            }
        }
        return $array;
    }

    public function toOriginalArray(bool $minimal = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "title" => $this->title,
            "original_title" => $this->original_title,
            "synopsis" => $this->synopsis,
            "cape" => $this->cape,
            "profile" => $this->profile,
            "start_date" => $this->start_date?->format(Database::DateFormat),
            "end_date" => $this->end_date?->format(Database::DateFormat),
            "mature" => $this->mature,
            "launch_day" => $this->launch_day,
            "launch_time" => $this->launch_time?->format(Database::TimeFormat),
            "source" => $this->source,
            "audience" => $this->audience,
            "trailer" => $this->trailer,
            "available" => $this->available
        );
        if (!$minimal) {
            // Relations
            $array["videos"] = null;
            if ($this->videos != null) {
                $array["videos"] = array();
                foreach ($this->videos as $value) $array["videos"][] = $value;
            }
            $array["genders"] = null;
            if ($this->genders != null) {
                $array["genders"] = array();
                foreach ($this->genders as $value) $array["genders"][] = $value;
            }
        }
        return $array;
    }


    /** @noinspection PhpParamsInspection */
    public function setRelation(int $relation, EntityArray|array $value) : Anime
    {
        switch ($relation) {
            case self::VIDEOS:
                $this->setVideos($value);
                break;
            case self::GENDERS:
                $this->setGenders($value);
                break;
            case self::SEASONS:
                $this->setSeasons($value);
                break;
        }
        return $this;
    }

    /**
     * @param int $relation
     * @param mixed $value
     * @return $this
     * @throws NotInitialized
     */
    public function addRelation(int $relation, mixed $value) : Anime
    {
        switch ($relation) {
            case self::VIDEOS:
                $this->addVideo($value);
                break;
            case self::SEASONS:
                $this->addSeason($value);
                break;
            case self::GENDERS:
                $this->addGender($value);
                break;
        }
        return $this;
    }

    /**
     * @throws NotInitialized
     * @noinspection PhpParamsInspection
     */
    public function removeRelation(int $relation, mixed $value = null, int $id = null) : Anime
    {
        switch ($relation) {
            case self::VIDEOS:
                $this->removeVideos($value, $id);
                break;
            case self::SEASONS:
                $this->removeSeasons($value, $id);
                break;
            case self::GENDERS:
                $this->removeGender($value, $id);
                break;
        }
        return $this;
    }

    /**
     * @param Video|null $entity
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removeVideos(Video $entity = null, int $id = null): Anime
    {
        if($this->videos == null) throw new NotInitialized("videos");
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->videos); $i++) {
                if ($this->videos[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->videos); $i++) {
                if ($this->videos[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->videos[$item]);
        return $this;
    }

    /**
     * @param Season|null $entity
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removeSeasons(Season $entity = null, int $id = null): Anime
    {
        if($this->seasons == null) throw new NotInitialized("seasons");
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->seasons); $i++) {
                if ($this->seasons[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->seasons); $i++) {
                if ($this->seasons[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->seasons[$item]);
        return $this;
    }

    /**
     * @param Gender|null $entity
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removeGender(Gender $entity = null, int $id = null): Anime
    {
        if($this->genders == null) throw new NotInitialized("genders");
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->genders); $i++) {
                if ($this->genders[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->genders); $i++) {
                if ($this->genders[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->genders[$item]);
        return $this;
    }

    /**
     * @param Video $entity
     * @return $this
     * @throws NotInitialized
     */
    public function addVideo(Video $entity): Anime
    {
        if($this->videos == null) throw new NotInitialized("videos");
        $this->videos[] = $entity;
        return $this;
    }

    /**
     * @param Season $entity
     * @return $this
     * @throws NotInitialized
     */
    public function addSeason(Season $entity): Anime
    {
        if($this->seasons == null) throw new NotInitialized("seasons");
        $this->seasons[] = $entity;
        return $this;
    }

    /**
     * @param Gender $entity
     * @return $this
     * @throws NotInitialized
     */
    public function addGender(Gender $entity): Anime
    {
        if($this->genders == null) throw new NotInitialized("genders");
        $this->genders[] = $entity;
        return $this;
    }

    /**
     * @return VideosArray|null
     */
    public function getVideos(): ?VideosArray
    {
        return $this->videos;
    }

    /**
     * @param VideosArray|null $videos
     * @return Anime
     */
    public function setVideos(?VideosArray $videos): Anime
    {
        $this->videos = $videos;
        return $this;
    }

    /**
     * @return SeasonsArray|null
     */
    public function getSeasons(): ?SeasonsArray
    {
        return $this->seasons;
    }

    /**
     * @param SeasonsArray|null $seasons
     * @return Anime
     */
    public function setSeasons(?SeasonsArray $seasons): Anime
    {
        $this->seasons = $seasons;
        return $this;
    }

    /**
     * @return GendersArray|null
     */
    public function getGenders(): ?GendersArray
    {
        return $this->genders;
    }

    /**
     * @param GendersArray|null $genders
     * @return Anime
     */
    public function setGenders(?GendersArray $genders): Anime
    {
        $this->genders = $genders;
        return $this;
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

    /**
     * @return Resource|null
     */
    public function getProfile(): ?Resource
    {
        return $this->profile;
    }

    /**
     * @param Resource|null $profile
     * @return Anime
     */
    public function setProfile(?Resource $profile): Anime
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return Resource|null
     */
    public function getCape(): ?Resource
    {
        return $this->cape;
    }

    /**
     * @param Resource|null $cape
     * @return Anime
     */
    public function setCape(?Resource $cape): Anime
    {
        $this->cape = $cape;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getLaunchTime(): ?DateTime
    {
        return $this->launch_time;
    }

    /**
     * @param DateTime|null $launch_time
     * @return Anime
     */
    public function setLaunchTime(?DateTime $launch_time): Anime
    {
        $this->launch_time = $launch_time;
        return $this;
    }
}