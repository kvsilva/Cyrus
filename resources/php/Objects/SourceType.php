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

class SourceType extends Entity
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
        parent::__construct(table: "source_type", id: $id, flags: $flags);
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
    public function store() : SourceType{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : SourceType{
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
        ), table: 'source_type', class: 'Objects\SourceType', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("source_type"),
            "name" => $this->name
        );
    }

    /**
     * @param bool $minimal
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    public function toArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name
        );
    }

    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    public function toOriginalArray(bool $minimal = false): array
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
     * @param String|null $name
     * @return SourceType
     */
    public function setName(?string $name): SourceType
    {
        $this->name = $name;
        return $this;
    }

}