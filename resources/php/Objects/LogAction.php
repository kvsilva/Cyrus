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

class LogAction extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?String $name = null;
    protected ?String $description = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "log_action", id: $id, flags: $flags);
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
    public function store() : LogAction{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : LogAction{
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
        ), table: 'log_action', class: 'Objects\LogAction', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "name" => "null|String", "description" => "null|String"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("log_action"),
            "name" => $this->name,
            "description" => $this->description
        );
    }

    /**
     * @param bool $entities
     * @return array
     */
    #[Pure]
    #[ArrayShape(["id" => "int|null", "name" => "null|String", "description" => "null|String"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name,
            "description" => $this->description
        );
    }

    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name,
            "description" => $this->description
        );
    }

    /**
     * @return String
     */
    public function getName(): String
    {
        return $this->name;
    }

    /**
     * @param String $name
     * @return LogAction
     */
    public function setName(String $name): LogAction
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return String
     */
    public function getDescription(): String
    {
        return $this->description;
    }

    /**
     * @param String $description
     * @return LogAction
     */
    public function setDescription(String $description): LogAction
    {
        $this->description = $description;
        return $this;
    }

}