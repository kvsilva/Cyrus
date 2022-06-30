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

class Audience extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?String $name = null;
    protected ?int $minimum_age = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "audience", id: $id, flags: $flags);
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
    public function store() : Audience{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : Audience{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, String $name = null, int $minimum_age = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "name" => $name,
            "minimum_age" => $minimum_age
        ), table: 'audience', class: 'Objects\Audience', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "name" => "null|String", "minimum_age" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("audience"),
            "name" => $this->name,
            "minimum_age" => $this->minimum_age
        );
    }

    /**
     * @param bool $minimal
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String", "minimum_age" => "int|null"])]
    public function toArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name,
            "minimum_age" => $this->minimum_age
        );
    }

    /**
     * @param bool $minimal
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String", "minimum_age" => "int|null"])]
    public function toOriginalArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name,
            "minimum_age" => $this->minimum_age
        );
    }

}