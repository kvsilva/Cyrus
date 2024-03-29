<?php

namespace Objects;

use DateTime;
use Enumerators\Availability;
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
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use ReflectionException;

class Season extends Entity
{
    // FLAGS

    public const VIDEOS = 2;

    // DEFAULT STRUCTURE

    protected ?int $numeration = null;
    protected ?String $name = null;
    protected ?String $synopsis = null;
    protected ?DateTime $release_date = null;
    protected ?DateTime $end_date = null;
    protected ?Availability $available = null;



    protected ?int $anime = null;

    // RELATIONS

    // SEASON::VIDEOS
    private ?VideosArray $videos = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "season", id: $id, flags: $flags);
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
        if($this->hasFlag(self::VIDEOS)){
            $this->videos = new VideosArray();
            $query = $database->query("SELECT id FROM video WHERE season = $id;");
            while($row = $query->fetch_array()){
                $this->videos[] = new Video($row["id"]);
            }
        }
        parent::buildRelations();
    }

    protected function updateRelations()
    {
        parent::updateRelations();
        $database = $this->getDatabase();
        $id = $this->getId();
        if ($this->hasFlag(self::VIDEOS)) {
            $query = $database->query("SELECT id FROM video WHERE season = $id AND available = '" . Availability::AVAILABLE->value . "';");
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
                $video->store(anime: new Anime($this->anime), season: $this);
            }
        }
    }


    /**
     * @param Anime|null $anime
     * @return $this
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws RecordNotFound
     * @throws ReflectionException
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store(?Anime $anime = null) : Season{
        if($anime === null) $anime = new Anime(id: $this->anime);
        $this->anime = $anime->getId();
        parent::__store(values: array("anime" => $anime->getId()));
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : Season{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $anime = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "anime" => $anime,
            "available" => $available?->value
        ), table: 'season', class: 'Objects\Season', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "numeration" => "int|null", "name" => "null|String", "synopsis" => "null|String", "release_date" => "bool|\DateTime|null", "end_date" => "bool|\DateTime|null", "available" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("season"),
            "numeration" => $this->numeration,
            "name" => $this->name,
            "synopsis" => $this->synopsis,
            "release_date" => $this->release_date != null ? Database::convertDateToDatabase($this->release_date) : null,
            "end_date" => $this->end_date != null ? Database::convertDateToDatabase($this->end_date) : null,
            "available" => $this->available?->value
        );
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[ArrayShape(["id" => "int|null", "numeration" => "int|null", "name" => "null|String", "synopsis" => "null|String", "release_date" => "null|string", "end_date" => "null|string", "available" => "array|null", "videos" => "array|null"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "numeration" => $this->numeration,
            "name" => $this->name,
            "synopsis" => $this->synopsis,
            "release_date" => $this->release_date?->format(Database::DateFormatSimplified),
            "end_date" => $this->end_date?->format(Database::DateFormatSimplified),
            "available" => $this->available?->toArray()
        );
        if(!$minimal) {
            $array["videos"] = $this->videos != null ? array() : null;
            if ($array["videos"] !== null) foreach ($this->videos as $value) $array["videos"][] = $value->toArray();
        }
        return $array;
    }

    #[ArrayShape(["id" => "int|null", "numeration" => "int|null", "name" => "null|String", "synopsis" => "null|String", "release_date" => "bool|\DateTime|null", "end_date" => "bool|\DateTime|null", "available" => "\Enumerators\Availability|null", "videos" => "array|null"])]
    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "numeration" => $this->numeration,
            "name" => $this->name,
            "synopsis" => $this->synopsis,
            "release_date" => $this->release_date != null ? Database::convertDateToDatabase($this->release_date) : null,
            "end_date" => $this->end_date != null ? Database::convertDateToDatabase($this->end_date) : null,
            "available" => $this->available
        );
        if(!$minimal) {
            $array["videos"] = $this->videos != null ? array() : null;
            if ($array["videos"] != null) foreach ($this->videos as $value) $array["videos"][] = $value;
        }
        return $array;
    }

    /**
     * @param Video|null $entity
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removeVideos(Video $entity = null, int $id = null): Season
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


    public function addVideo(Video $entity): Season
    {
        if($this->videos === null) throw new NotInitialized("videos");
        $this->videos[] = $entity;
        return $this;
    }


    /**
     * @param int $relation
     * @param mixed $value
     * @return $this
     * @throws NotInitialized
     */
    public function addRelation(int $relation, mixed $value) : Season
    {
        switch ($relation) {
            case self::VIDEOS:
                $this->addVideo($value);
                break;
        }
        return $this;
    }

    /**
     * @throws NotInitialized
     * @noinspection PhpParamsInspection
     */
    public function removeRelation(int $relation, mixed $value = null, int $id = null) : Season
    {
        switch ($relation) {
            case self::VIDEOS:
                $this->removeVideos($value, $id);
                break;
        }
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumeration(): ?int
    {
        return $this->numeration;
    }

    /**
     * @param int|null $numeration
     * @return Season
     */
    public function setNumeration(?int $numeration): Season
    {
        $this->numeration = $numeration;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param String|null $name
     * @return Season
     */
    public function setName(?string $name): Season
    {
        $this->name = $name;
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
     * @param String|null $synopsis
     * @return Season
     */
    public function setSynopsis(?string $synopsis): Season
    {
        $this->synopsis = $synopsis;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getReleaseDate(): ?DateTime
    {
        return $this->release_date;
    }

    /**
     * @param DateTime|String $release_date
     * @return Season
     */
    public function setReleaseDate(DateTime|String $release_date): Season
    {
        if(is_string($release_date)){
            $this->release_date = DateTime::createFromFormat(Database::DateFormat, $release_date);
        } else if(is_a($release_date, "DateTime")){
            $this->release_date = $release_date;
        }
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
     * @return array|null
     */
    public function getVideos(): ?VideosArray
    {
        return $this->videos;
    }

    /**
     * @param array|null $videos
     * @return Season
     */
    public function setVideos(?VideosArray $videos): Season
    {
        $this->videos = $videos;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->end_date;
    }

    /**
     * @param DateTime|null $end_date
     * @return Season
     */
    public function setEndDate(?DateTime $end_date): Season
    {
        $this->end_date = $end_date;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAnime(): ?int
    {
        return $this->anime;
    }

    /**
     * @param int|null $anime
     * @return Season
     */
    public function setAnime(?int $anime): Season
    {
        $this->anime = $anime;
        return $this;
    }





}