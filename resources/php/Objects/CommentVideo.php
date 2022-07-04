<?php

namespace Objects;

use DateTime;
use Enumerators\Availability;
use Enumerators\Removal;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Exceptions\RecordNotFound;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Functions\Database;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use ReflectionException;

class CommentVideo extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?DateTime $post_date = null;
    protected ?String $description = null;
    protected ?bool $spoiler = null;

    protected ?Video $video = null;
    protected ?User $user = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "commentvideo", id: $id, flags: $flags);
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
    public function store(Video|int|null $video = null, User|int|null $user = null) : CommentVideo{
        if($video === null) $video = new Video(id: $this->video?->getId());
        if($user === null) $user = new User(id: $this->user?->getId());
        $this->video = is_int($video) ? new Video(id: $video) : $video;
        $this->user = is_int($user) ? new User(id: $user) : $user;
        $values = array();
        $values["video"] = $this->video?->getId();
        $values["user"] = $this->user?->getId();
        parent::__store(values: $values);
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : CommentVideo{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $user = null, int $video = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "video" => $video,
            "user" => $user,
        ), table: 'commentvideo', class: 'Objects\CommentVideo', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */

    #[ArrayShape(["id" => "int|null", "post_date" => "string", "title" => "null|String", "description" => "null|String", "spoiler" => "bool|null", "classification" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("commentvideo"),
            "post_date" => $this->post_date?->format(Database::DateFormat),
            "description" => $this->description,
            "spoiler" => $this->spoiler,
        );
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[ArrayShape(["id" => "int|null", "post_date" => "string", "title" => "null|String", "description" => "null|String", "spoiler" => "bool|null", "classification" => "int|null"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "post_date" => $this->post_date?->format(Database::DateFormat),
            "description" => $this->description,
            "spoiler" => $this->spoiler
        );
        if($entities){
            $array["video"] = $this->video?->toArray();
            $array["user"] = $this->user?->toArray();
        }
        return $array;
    }


    #[ArrayShape(["id" => "int|null", "post_date" => "string", "title" => "null|String", "description" => "null|String", "spoiler" => "bool|null", "classification" => "int|null"])]
    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "post_date" => $this->post_date?->format(Database::DateFormat),
            "description" => $this->description,
            "spoiler" => $this->spoiler,
        );
        if($entities){
            $array["video"] = $this->video;
            $array["user"] = $this->user;
        }
        return $array;
    }

    /**
     * @return DateTime|null
     */
    public function getPostDate(): ?DateTime
    {
        return $this->post_date;
    }

    /**
     * @param DateTime|null $post_date
     * @return CommentVideo
     */
    public function setPostDate(?DateTime $post_date): CommentVideo
    {
        $this->post_date = $post_date;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param String|null $description
     * @return CommentVideo
     */
    public function setDescription(?string $description): CommentVideo
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSpoiler(): ?bool
    {
        return $this->spoiler;
    }

    /**
     * @param bool|null $spoiler
     * @return CommentVideo
     */
    public function setSpoiler(?bool $spoiler): CommentVideo
    {
        $this->spoiler = $spoiler;
        return $this;
    }

    /**
     * @return Video|null
     */
    public function getVideo(): ?Video
    {
        return $this->video;
    }

    /**
     * @param Video|null $video
     * @return CommentVideo
     */
    public function setVideo(?Video $video): CommentVideo
    {
        $this->video = $video;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return CommentVideo
     */
    public function setUser(?User $user): CommentVideo
    {
        $this->user = $user;
        return $this;
    }






}