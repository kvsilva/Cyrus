<?php

namespace Objects;

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

class Resource extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?String $title = null;
    protected ?String $description = null;
    protected ?String $extension = null;
    protected ?String $path = null;
    protected ?Availability $available = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "resource", id: $id, flags: $flags);
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
    public function store() : Resource{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : Resource{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "available" => $available?->value
        ), table: 'resource', class: 'Objects\Resource', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "title" => "null|String", "description" => "null|String", "extension" => "null|String", "path" => "null|String", "available" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("resource"),
            "title" => $this->title,
            "description" => $this->description,
            "extension" => $this->extension,
            "path" => $this->path,
            "available" => $this->available?->value
        );
    }

    /**
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "title" => "null|String", "description" => "null|String", "extension" => "null|String", "path" => "null|String", "available" => "array|null"])]
    public function toArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "title" => $this->title,
            "description" => $this->description,
            "extension" => $this->extension,
            "path" => $this->path,
            "available" => $this->available?->toArray()
        );
    }

    #[Pure] #[ArrayShape(["id" => "int|mixed", "title" => "null|String", "description" => "null|String", "extension" => "null|String", "path" => "null|String", "available" => "array|null"])]
    public function toOriginalArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "title" => $this->title,
            "description" => $this->description,
            "extension" => $this->extension,
            "path" => $this->path,
            "available" => $this->available
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
     * @param String|null $title
     * @return Resource
     */
    public function setTitle(?string $title): Resource
    {
        $this->title = $title;
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
     * @return Resource
     */
    public function setDescription(?string $description): Resource
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param String|null $extension
     * @return Resource
     */
    public function setExtension(?string $extension): Resource
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param String|null $path
     * @return Resource
     */
    public function setPath(?string $path): Resource
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
     * @return Resource
     */
    public function setAvailable(?Availability $available): Resource
    {
        $this->available = $available;
        return $this;
    }

}