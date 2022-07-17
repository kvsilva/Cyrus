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

class CommentNews extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?DateTime $post_date = null;
    protected ?String $description = null;

    protected ?User $user = null;
    protected ?News $news = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "commentnews", id: $id, flags: $flags);
    }

    /**
     * @param News|int|null $news
     * @param User|int|null $user
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
    public function store(News|int|null $news = null, User|int|null $user = null) : CommentNews{
        if($news === null) $news = new News(id: $this->news?->getId());
        if($user === null) $user = new User(id: $this->user?->getId());
        $this->news = is_int($news) ? new News(id: $news) : $news;
        $this->user = is_int($user) ? new User(id: $user) : $user;
        $values = array();
        $values["news"] = $this->news?->getId();
        $values["user"] = $this->user?->getId();
        parent::__store(values: $values);
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : CommentNews{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $news = null, int $user = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "news" => $news,
            "user" => $user,
        ), table: 'commentanime', class: 'Objects\CommentNews', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */

    #[ArrayShape(["id" => "int|null", "post_date" => "string", "title" => "null|String", "description" => "null|String", "spoiler" => "bool|null", "classification" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("commentnews"),
            "post_date" => $this->post_date?->format(Database::DateFormat),
            "description" => $this->description,
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
        );
        if($entities){
            $array["news"] = $this->news?->toArray();
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
        );
        if($entities){
            $array["news"] = $this->news;
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
     * @return CommentNews
     */
    public function setPostDate(?DateTime $post_date): CommentNews
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
     * @return CommentNews
     */
    public function setDescription(?string $description): CommentNews
    {
        $this->description = $description;
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
     * @return CommentNews
     */
    public function setUser(?User $user): CommentNews
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return News|null
     */
    public function getNews(): ?News
    {
        return $this->news;
    }

    /**
     * @param News|null $news
     * @return CommentNews
     */
    public function setNews(?News $news): CommentNews
    {
        $this->news = $news;
        return $this;
    }



}