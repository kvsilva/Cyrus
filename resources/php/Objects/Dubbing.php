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

class Dubbing extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?Language $language = null;
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
        parent::__construct(table: "dubbing", id: $id, flags: $flags);
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
    public function store(Video $video) : Dubbing{
        parent::__store(values: array("video" => $video?->getId()));
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : Dubbing{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $video = null, Availability $available = Availability::AVAILABLE,  string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "video" => $video,
            "available" => $available?->value
        ), table: 'dubbing', class: 'Objects\Dubbing', sql: $sql, flags: $flags);
    }


    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "language" => "int|null", "path" => "null|String", "available" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("dubbing"),
            "language" => $this->language?->getId(),
            "path" => $this->path,
            "available" => $this->available?->value
        );
    }


    /**
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "language" => "null|\Objects\Language", "path" => "null|String", "available" => "array|null"])]
    public function toArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "language" => $this->language,
            "path" => $this->path,
            "available" => $this->available?->toArray()
        );
    }

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @param Language|null $language
     * @return Dubbing
     */
    public function setLanguage(?Language $language): Dubbing
    {
        $this->language = $language;
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
     * @return Dubbing
     */
    public function setPath(?string $path): Dubbing
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
     * @return Dubbing
     */
    public function setAvailable(?Availability $available): Dubbing
    {
        $this->available = $available;
        return $this;
    }
}