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

class Log extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?LogAction $action_type = null;
    protected ?array $arguments = null;
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
        parent::__construct(table: "log", id: $id, flags: $flags);
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
    public function store(User $user) : Log{
        parent::__store(values: array("user" => $user?->getId()));
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : Log{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $user = null, int $action_type = null,  string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "user" => $user,
            "action_type" => $action_type
        ), table: 'log', class: 'Objects\Log', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    #[ArrayShape(["id" => "int|mixed", "user" => "int|mixed|null", "action_type" => "int|null", "arguments" => "false|null|string"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("log"),
            "action_type" => $this->action_type?->store()->getId(),
            "arguments" => $this->arguments!= null ? json_encode($this->arguments) : null
        );
    }

    /**
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String", "action" => "int|null", "description" => "null|String"])]
    public function toArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->action_type?->getName(),
            "action" => $this->action_type?->getId(),
            "description" => $this->description
        );
    }

    public function toOriginalArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->action_type?->getName(),
            "action" => $this->action_type?->getId(),
            "description" => $this->description
        );
    }

    /**
     * @return LogAction
     */
    public function getActionType(): LogAction
    {
        return $this->action_type;
    }

    /**
     * @param LogAction $action_type
     * @return Log
     */
    public function setActionType(LogAction $action_type): Log
    {
        $this->action_type = $action_type;
        $this->description = $this->action_type->getDescription();
        foreach($this->arguments as $key => $value){
            $this->description = str_replace($key, $value, $this->description);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
    }

    /**
     * @param array|mixed $arguments
     * @return Log
     */
    public function setArguments(mixed $arguments): Log
    {
        $this->arguments = $arguments;
        $this->description = $this->action_type->getDescription();
        foreach($this->arguments as $key => $value){
            $this->description = str_replace($key, $value, $this->description);
        }
        return $this;
    }

    /**
     * @return String|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}