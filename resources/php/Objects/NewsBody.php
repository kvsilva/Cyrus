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

class NewsBody extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?DateTime $edited_at = null;
    protected ?String $content = null;
    protected ?String $title = null;
    protected ?String $subtitle = null;
    protected ?Resource $thumbnail = null;

    // FK

    protected ?User $user = null;
    protected ?News $news = null;

    // RELATIONS

    protected ?NewsBodysArray $editions = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "news_body", id: $id, flags: $flags);
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
    public function store() : NewsBody{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : NewsBody{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, String $title = null, int $user = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "title" => $title,
            "user" => $user
        ), table: 'news_body', class: 'Objects\NewsBody', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|null", "created_at" => "null|string", "content" => "null|String", "title" => "null|String", "subtitle" => "null|String", "thumbnail" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("news_body"),
            "edited_at" => $this->edited_at?->format(Database::DateFormatSimplified),
            "content" => $this->content,
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            "thumbnail" => $this->thumbnail?->getId(),
        );
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */

    #[ArrayShape(["edited_at" => "null|string", "content" => "null|String", "title" => "null|String", "subtitle" => "null|String", "thumbnail" => "array|null", "news" => "array|null", "user" => "array|null"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "edited_at" => $this->edited_at?->format(Database::DateFormatSimplified),
            "content" => $this->content,
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            "thumbnail" => $this->thumbnail?->toArray(),
        );
        if($entities){
            $array["user"] = $this->user?->toArray();
            $array["news"] = $this->news?->toArray();
        }
        return $array;
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[ArrayShape(["edited_at" => "null|string", "content" => "null|String", "title" => "null|String", "subtitle" => "null|String", "thumbnail" => "array|null", "news" => "array|null", "user" => "array|null"])]
    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "edited_at" => $this->edited_at?->format(Database::DateFormatSimplified),
            "content" => $this->content,
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            "thumbnail" => $this->thumbnail,
        );
        if($entities){
            $array["user"] = $this->user;
            $array["news"] = $this->news;
        }
        return $array;
    }

    /**
     * @return DateTime|null
     */
    public function getEditedAt(): ?DateTime
    {
        return $this->edited_at;
    }

    /**
     * @param DateTime|null $edited_at
     * @return NewsBody
     */
    public function setEditedAt(?DateTime $edited_at): NewsBody
    {
        $this->edited_at = $edited_at;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param String|null $content
     * @return NewsBody
     */
    public function setContent(?string $content): NewsBody
    {
        $this->content = $content;
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
     * @param String|null $title
     * @return NewsBody
     */
    public function setTitle(?string $title): NewsBody
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    /**
     * @param String|null $subtitle
     * @return NewsBody
     */
    public function setSubtitle(?string $subtitle): NewsBody
    {
        $this->subtitle = $subtitle;
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
     * @return NewsBody
     */
    public function setThumbnail(?Resource $thumbnail): NewsBody
    {
        $this->thumbnail = $thumbnail;
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
     * @return NewsBody
     */
    public function setUser(?User $user): NewsBody
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
     * @return NewsBody
     */
    public function setNews(?News $news): NewsBody
    {
        $this->news = $news;
        return $this;
    }

    /**
     * @return NewsBodysArray|null
     */
    public function getEditions(): ?NewsBodysArray
    {
        return $this->editions;
    }

    /**
     * @param NewsBodysArray|null $editions
     * @return NewsBody
     */
    public function setEditions(?NewsBodysArray $editions): NewsBody
    {
        $this->editions = $editions;
        return $this;
    }



}