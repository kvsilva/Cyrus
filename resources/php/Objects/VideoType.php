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

class VideoType extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?String $name = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "video_type", id: $id, flags: $flags);
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
    public function store() : VideoType{
        parent::__store();
        return $this;
    }
    /**
     * @throws IOException
     */
    public function remove() : VideoType{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, string $name = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "name" => $name
        ), table: 'video_type', class: 'Objects\VideoType', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("video_type"),
            "name" => $this->name
        );
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name
        );
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name
        );
    }

    /**
     * @return String|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed|String|null $name
     * @return VideoType
     */
    public function setName(mixed $name): VideoType
    {
        $this->name = $name;
        return $this;
    }

}