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
use JetBrains\PhpStorm\Pure;
use ReflectionException;

class Example_Object extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    private ?String $title = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "example_object", id: $id, flags: $flags);
    }

    /**
     * @return void
     */
    #[Pure]
    protected function buildRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
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
    public function store(Object $object) : Example_Object{
        parent::__store(values: array("object" => $object));
        return $this;
    }

    /**
     * @return void
     */
    #[Pure]
    protected function updateRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
    }

    /**
     * @throws IOException
     */
    public function remove() : Example_Object{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "available" => $available?->value
        ), table: 'example_object', class: 'Objects\Example_Object', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    protected function valuesArray(): array
    {
        return array(

        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(

        );
    }

}