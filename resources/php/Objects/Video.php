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
use JetBrains\PhpStorm\Pure;
use ReflectionException;

class Video extends Entity
{
    // FLAGS

    public const SUBTITLES = 2;
    public const DUBBING = 3;
    public const COMMENTVIDEOS = 3;

    // DEFAULT STRUCTURE

    protected ?VideoType $video_type = null;
    protected ?int $numeration = null;
    protected ?String $title = null;
    protected ?String $synopsis = null;
    protected ?Resource $thumbnail = null;
    protected ?DateTime $release_date = null;
    protected ?int $duration = null;
    protected ?int $opening_start = null;
    protected ?int $opening_end = null;
    protected ?int $ending_start = null;
    protected ?int $ending_end = null;
    protected ?Resource $path = null;
    protected ?Availability $available = null;
    // Foreign Key
    protected ?Anime $anime = null;
    protected ?Season $season = null;

    // RELATIONS

    // Video::Subtitles
    private ?SubtitlesArray $subtitles = null;
    // Video::CommentVideos
    private ?CommentVideosArray $comments = null;
    // Video::DubbingOld
    private ?array $dubbing = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "video", id: $id, flags: $flags);
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
        if($this->hasFlag(self::SUBTITLES)){
            $this->subtitles = new SubtitlesArray();
            $query = $database->query("SELECT id FROM subtitle WHERE video = $id;");
            while($row = $query->fetch_array()){
                $this->subtitles[] = new Subtitle($row["id"]);
            }
        }
        if($this->hasFlag(self::DUBBING)){
            $this->dubbing = array();
            $query = $database->query("SELECT id FROM dubbing WHERE video = $id;");
            while($row = $query->fetch_array()){
                $this->dubbing[] = new Dubbing($row["id"]);
            }
        }
        if($this->hasFlag(self::COMMENTVIDEOS)){
            $this->comments = new CommentVideosArray();
            $query = $database->query("SELECT id FROM commentvideo WHERE video = $id;");
            while($row = $query->fetch_array()){
                $this->comments[] = new CommentVideo($row["id"]);
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
    public function store(Anime|int|null $anime = null, Season|int|null $season = null) : Video{
        if($anime === null) $anime = new Anime(id: $this->anime?->getId());
        if($season === null) $season = new Season(id: $this->season?->getId());
        $this->anime = is_int($anime) ? new Anime(id: $anime) : $anime;
        $this->season = is_int($season) ? new Season(id: $season) : $season;
        $values = array();
        $values["anime"] = $anime->getId();
        $values["season"] = $season->getId();
        parent::__store(values: $values);
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
        if ($this->hasFlag(self::SUBTITLES)) {
            $query = $database->query("SELECT id FROM subtitle WHERE video = $id AND available = '" . Availability::AVAILABLE->value . "';");
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
        if ($this->hasFlag(self::DUBBING)) {
            $query = $database->query("SELECT id FROM dubbing WHERE video = $id AND available = '" . Availability::AVAILABLE->value . "';");
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
        if ($this->hasFlag(self::COMMENTVIDEOS)) {
            $query = $database->query("SELECT id FROM commentvideo WHERE video = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->comments as $comment) {
                    if ($comment->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) {
                    $database->query("DELETE FROM commentvideo where video = $id");
                }
            }
            foreach ($this->comments as $comment) {
                $comment->store(video: $this->getId());
            }
        }
    }

    /**
     * @throws IOException
     */
    public function remove() : Video{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $anime = null, int $numeration = null, String $title = null, Availability $available = Availability::AVAILABLE, string $sql = null, ?int $limit = null, string $order = "id", string $operator = "=", array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "numeration" => $numeration,
            "anime" => $anime,
            "title" => $title,
            "available" => $available?->value
        ), table: 'video', class: 'Objects\Video', sql: $sql, operator: $operator, limit: $limit, flags: $flags);
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
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("video"),
            "video_type" => $this->video_type?->getId(),
            "numeration" => $this->numeration,
            "title"=> $this->title,
            "synopsis"=> $this->synopsis,
            "thumbnail"=> $this->thumbnail?->store()->getId(),
            "release_date"=> $this->release_date?->format(Database::DateFormat),
            "duration"=> $this->duration,
            "opening_start"=> $this->opening_start,
            "opening_end"=> $this->opening_end,
            "ending_start" => $this->ending_start,
            "ending_end" => $this->ending_end,
            "path" => $this->path?->store()->getId(),
            "available" => $this->available?->value
        );
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    public function toArray(bool $minimal = false, bool $entities = false): array
    {

        $array = array(
            "id" => $this->getId(),
            "video_type" => $this->video_type?->toArray(false, $entities),
            "numeration" => $this->numeration,
            "title"=> $this->title,
            "synopsis"=> $this->synopsis,
            "thumbnail"=> $this->thumbnail?->toArray(false, $entities),
            "release_date"=> $this->release_date?->format(Database::DateFormat),
            "duration"=> $this->duration,
            "opening_start"=> $this->opening_start,
            "opening_end"=> $this->opening_end,
            "ending_start" => $this->ending_start,
            "ending_end" => $this->ending_end,
            "path" => $this->path?->toArray(false, $entities),
            "available" => $this->available?->toArray()
        );
        if(!$minimal){
            $array["subtitles"] = $this->subtitles !== null ? array() : null;
            if($array["subtitles"] !== null) foreach($this->subtitles as $value) {
                $array["subtitles"][] = $value->toArray();
            }
            $array["dubbing"] = $this->dubbing !== null ? array() : null;
            if($array["dubbing"] !== null) foreach($this->dubbing as $value) $array["dubbing"][] = $value->toArray();
            $array["comments"] = null;
            if ($this->comments !== null) {
                $array["comments"] = array();
                foreach ($this->comments as $value) $array["comments"][] = $value->toArray(entities: $entities);
            }
        }
        if($entities){
            $array["anime"] = $this->anime?->toArray(false, $entities);
            $array["season"] = $this->season?->toArray(false, $entities);
        }
        return $array;
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {

        $array = array(
            "id" => $this->getId(),
            "video_type" => $this->video_type,
            "numeration" => $this->numeration,
            "title"=> $this->title,
            "synopsis"=> $this->synopsis,
            "thumbnail"=> $this->thumbnail,
            "release_date"=> $this->release_date?->format(Database::DateFormat),
            "duration"=> $this->duration,
            "opening_start"=> $this->opening_start,
            "opening_end"=> $this->opening_end,
            "ending_start" => $this->ending_start,
            "ending_end" => $this->ending_end,
            "path" => $this->path,
            "available" => $this->available,
        );
        if(!$minimal){
            $array["subtitles"] = $this->subtitles != null ? array() : null;
            if($array["subtitles"] != null) foreach($this->subtitles as $value) $array["subtitles"][] = $value;
            $array["dubbing"] = $this->dubbing != null ? array() : null;
            if($array["dubbing"] != null) foreach($this->dubbing as $value) $array["dubbing"][] = $value;
            $array["comments"] = null;
            if ($this->comments !== null) {
                $array["comments"] = array();
                foreach ($this->comments as $value) $array["comments"][] = $value;
            }
        }
        if($entities){
            $array["anime"] = $this->anime;
            $array["season"] = $this->season;
        }
        return $array;
    }


    /**
     * @param Subtitle|null $entity
     * @param int|null $id
     * @return Video
     * @throws NotInitialized
     */
    public function removeSubtitle(Subtitle $entity = null, int $id = null): Video
    {
        if($this->subtitles == null) throw new NotInitialized("subtitles");
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->subtitles); $i++) {
                if ($this->subtitles[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->subtitles); $i++) {
                if ($this->subtitles[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->subtitles[$item]);
        return $this;
    }


    /**
     * @throws NotInitialized
     */
    public function removeComment(CommentVideo $entity = null, int $id = null): Video
    {
        if($this->comments == null) throw new NotInitialized("comments");
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->comments); $i++) {
                if ($this->comments[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->comments); $i++) {
                if ($this->comments[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->comments[$item]);
        return $this;
    }

    /**
     * @throws NotInitialized
     */
    public function addSubtitle(Subtitle $entity): Video
    {
        if($this->subtitles === null) throw new NotInitialized("subtitles");
        $this->subtitles[] = $entity;
        return $this;
    }

    /**
     * @param CommentVideo $entity
     * @return $this
     * @throws NotInitialized
     */
    public function addComment(CommentVideo $entity): Video
    {
        if($this->comments == null) throw new NotInitialized("comments");
        $this->comments[] = $entity;
        return $this;
    }


    /**
     * @param int $relation
     * @param mixed $value
     * @return Video
     * @throws NotInitialized
     */
    public function addRelation(int $relation, mixed $value) : Video
    {
        switch ($relation) {
            case self::SUBTITLES:
                $this->addSubtitle($value);
                break;
            case self::COMMENTVIDEOS:
                $this->addComment($value);
                break;
        }
        return $this;
    }

    /**
     * @throws NotInitialized
     */
    public function removeRelation(int $relation, mixed $value = null, int $id = null) : Video
    {
        switch ($relation) {
            case self::SUBTITLES:
                $this->removeSubtitle($value, $id);
                break;
            case self::COMMENTVIDEOS:
                $this->removeComment($value, $id);
                break;
        }
        return $this;
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
     * @return int|null
     */
    public function getNumeration(): ?int
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
     * @return String|null
     */
    public function getTitle(): ?string
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
     * @return String|null
     */
    public function getSynopsis(): ?string
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
     * @return int|null
     */
    public function getDuration(): ?int
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
     * @return int|null
     */
    public function getOpeningStart(): ?int
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
     * @return int|null
     */
    public function getOpeningEnd(): ?int
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
     * @return int|null
     */
    public function getEndingStart(): ?int
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
     * @return int|null
     */
    public function getEndingEnd(): ?int
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
     * @return DateTime|null
     */
    public function getReleaseDate(): ?DateTime
    {
        return $this->release_date;
    }

    /**
     * @param DateTime|null $release_date
     * @return Video
     */
    public function setReleaseDate(?DateTime $release_date): Video
    {
        $this->release_date = $release_date;
        return $this;
    }


    /**
     * @return Resource|null
     */
    public function getPath(): ?Resource
    {
        return $this->path;
    }

    /**
     * @param Resource|null $path
     * @return Video
     */
    public function setPath(?Resource $path): Video
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
     * @return SubtitlesArray
     */
    public function getSubtitles(): SubtitlesArray
    {
        return $this->subtitles;
    }

    /**
     * @param SubtitlesArray $subtitles
     * @return Video
     */
    public function setSubtitles(SubtitlesArray $subtitles): Video
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
     * @return Resource|null
     */
    public function getThumbnail(): ?Resource
    {
        return $this->thumbnail;
    }

    /**
     * @param Resource|null $thumbnail
     * @return Video
     */
    public function setThumbnail(?Resource $thumbnail): Video
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return Anime|null
     */
    public function getAnime(): ?Anime
    {
        return $this->anime;
    }

    /**
     * @param Anime|null $anime
     * @return Video
     */
    public function setAnime(?Anime $anime): Video
    {
        $this->anime = $anime;
        return $this;
    }

    /**
     * @return Season|null
     */
    public function getSeason(): ?Season
    {
        return $this->season;
    }

    /**
     * @param Season|null $season
     * @return Video
     */
    public function setSeason(?Season $season): Video
    {
        $this->season = $season;
        return $this;
    }

    /**
     * @return CommentVideosArray|null
     */
    public function getComments(): ?CommentVideosArray
    {
        return $this->comments;
    }

    /**
     * @param CommentVideosArray|null $comments
     * @return Video
     */
    public function setComments(?CommentVideosArray $comments): Video
    {
        $this->comments = $comments;
        return $this;
    }




}