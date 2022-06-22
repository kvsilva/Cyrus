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

class PunishmentType extends Entity
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
        parent::__construct(table: "punishment_type", id: $id, flags: $flags);
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
    public function store() : PunishmentType{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : PunishmentType{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, string $name = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "name" => $name
        ), table: 'punishment_type', class: 'Objects\PunishmentType', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("punishment_type"),
            "name" => $this->name
        );
    }

    /**
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

    /**
     * @return String|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param String|null $name
     * @return PunishmentType
     */
    public function setName(?string $name): PunishmentType
    {
        $this->name = $name;
        return $this;
    }

}