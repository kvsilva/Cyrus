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

class CommentAnime extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?DateTime $post_date = null;
    protected ?String $title = null;
    protected ?String $description = null;
    protected ?bool $spoiler = null;
    protected ?int $classification = null;

    protected ?Anime $anime = null;
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
        parent::__construct(table: "commentanime", id: $id, flags: $flags);
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
    public function store(Anime|int|null $anime = null, User|int|null $user = null) : CommentAnime{
        if($anime === null) $anime = new Anime(id: $this->anime?->getId());
        if($user === null) $user = new User(id: $this->user?->getId());
        $this->anime = is_int($anime) ? new Anime(id: $anime) : $anime;
        $this->user = is_int($user) ? new User(id: $user) : $user;
        $values = array();
        $values["anime"] = $this->anime?->getId();
        $values["user"] = $this->user?->getId();
        parent::__store(values: $values);
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : CommentAnime{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $user = null, int $anime = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "anime" => $anime,
            "user" => $user,
        ), table: 'commentanime', class: 'Objects\CommentAnime', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */

    #[ArrayShape(["id" => "int|null", "post_date" => "string", "title" => "null|String", "description" => "null|String", "spoiler" => "bool|null", "classification" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("commentanime"),
            "post_date" => $this->post_date?->format(Database::DateFormat),
            "title" => $this->title,
            "description" => $this->description,
            "spoiler" => $this->spoiler,
            "classification" => $this->classification,
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
            "title" => $this->title,
            "description" => $this->description,
            "spoiler" => $this->spoiler,
            "classification" => $this->classification,
        );
        if($entities){
            $array["anime"] = $this->anime?->toArray();
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
            "title" => $this->title,
            "description" => $this->description,
            "spoiler" => $this->spoiler,
            "classification" => $this->classification,
        );
        if($entities){
            $array["anime"] = $this->anime;
            $array["user"] = $this->user;
        }
        return $array;
    }






}