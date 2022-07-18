<?php

namespace Objects;

use Cassandra\Blob;
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

class GlobalSetting extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?String $name = null;
    protected ?String $category = null;
    protected ?String $value = null;
    protected ?Blob $value_binary = null;
    protected ?String $data_type = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "global_settings", id: $id, flags: $flags);
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
    public function store() : GlobalSetting{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : GlobalSetting{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, String $name = null, String $category = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "name" => $name,
            "category" => $category
        ), table: 'global_settings', class: 'Objects\GlobalSetting', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|null", "name" => "null|String", "category" => "null|String", "value" => "null|String", "value_binary" => "\Cassandra\Blob|null", "data_type" => "null|String"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("global_settings"),
            "name" => $this->name,
            "category" => $this->category,
            "value" => $this->value,
            "value_binary" => $this->value_binary,
            "data_type" => $this->data_type,
        );
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|null", "name" => "null|String", "category" => "null|String", "value_binary" => "\Cassandra\Blob|null", "value" => "null|String", "data_type" => "null|String"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name,
            "category" => $this->category,
            "value_binary" => $this->value_binary,
            "value" => $this->value,
            "data_type" => $this->data_type,
        );
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|null", "name" => "null|String", "category" => "null|String", "value_binary" => "\Cassandra\Blob|null", "value" => "null|String", "data_type" => "null|String"])]
    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name,
            "category" => $this->category,
            "value_binary" => $this->value_binary,
            "value" => $this->value,
            "data_type" => $this->data_type,
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
     * @return Genre
     */
    public function setName(?string $name): GlobalSetting
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param String|null $value
     * @return GlobalSetting
     */
    public function setValue(?string $value): GlobalSetting
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getValueBinary(): ?string
    {
        return $this->value_binary;
    }

    /**
     * @param String|null $value_binary
     * @return GlobalSetting
     */
    public function setValueBinary(?string $value_binary): GlobalSetting
    {
        $this->value_binary = $value_binary;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getDataType(): ?string
    {
        return $this->data_type;
    }

    /**
     * @param String|null $data_type
     * @return GlobalSetting
     */
    public function setDataType(?string $data_type): GlobalSetting
    {
        $this->data_type = $data_type;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param String|null $group
     * @return GlobalSetting
     */
    public function setCategory(?string $category): GlobalSetting
    {
        $this->category = $category;
        return $this;
    }



}