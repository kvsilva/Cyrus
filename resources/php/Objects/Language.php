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

class Language extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE
    protected ?String $code = null;
    protected ?String $name = null;
    protected ?String $original_name = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "language", id: $id, flags: $flags);
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
    public function store() : Language{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : Language{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, string $code = null,  string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "code" => $code
        ), table: 'language', class: 'Objects\Language', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "code" => "null|String", "name" => "null|String", "original_name" => "null|String"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("language"),
            "code" => $this->code,
            "name" => $this->name,
            "original_name" => $this->original_name
        );
    }

    /**
     * @param bool $entities
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "code" => "null|String", "name" => "null|String", "original_name" => "null|String"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        return array(
            "id" => $this->getId(),
            "code" => $this->code,
            "name" => $this->name,
            "original_name" => $this->original_name
        );
    }

    #[ArrayShape(["id" => "int|null", "code" => "null|String", "name" => "null|String", "original_name" => "null|String"])]
    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        return array(
            "id" => $this->getId(),
            "code" => $this->code,
            "name" => $this->name,
            "original_name" => $this->original_name
        );
    }


    /**
     * @return String|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param String|null $code
     * @return Language
     */
    public function setCode(?string $code): Language
    {
        $this->code = $code;
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
     * @return Language
     */
    public function setName(?string $name): Language
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getOriginalName(): ?string
    {
        return $this->original_name;
    }

    /**
     * @param String|null $original_name
     * @return Language
     */
    public function setOriginalName(?string $original_name): Language
    {
        $this->original_name = $original_name;
        return $this;
    }

}