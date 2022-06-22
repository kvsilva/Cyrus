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

class Permission extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE
    protected ?String $tag = null;
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
        parent::__construct(table: "permission", id: $id, flags: $flags);
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
    public function store() : Permission{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : Permission{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, string $tag = null,  string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "tag" => $tag
        ), table: 'permission', class: 'Objects\Permission', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "tag" => "null|String", "name" => "null|String", "description" => "null|String"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("permission"),
            "tag" => $this->tag,
            "name" => $this->name,
            "description" => $this->description
        );
    }

    /**
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "tag" => "null|String", "name" => "null|String", "description" => "null|String"])]
    public function toArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "tag" => $this->tag,
            "name" => $this->name,
            "description" => $this->description
        );
    }

    /**
     * @return String|null
     */
    public function getTag(): ?string
    {
        return $this->tag;
    }

    /**
     * @param String|null $tag
     * @return Permission
     */
    public function setTag(?string $tag): Permission
    {
        $this->tag = $tag;
        return $this;
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
     * @return Permission
     */
    public function setName(?string $name): Permission
    {
        $this->name = $name;
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
     * @return Permission
     */
    public function setDescription(?string $description): Permission
    {
        $this->description = $description;
        return $this;
    }



}