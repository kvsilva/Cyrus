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
use JetBrains\PhpStorm\Pure;
use ReflectionException;

class Example_Object extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?String $title = null;

    // RELATIONS

    private ?array $relations = null;

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
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    protected function buildRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
        if($this->hasFlag(self::NORMAL)){
            $this->relations = array();
            $query = $database->query("# noinspection SqlResolveForFile

# noinspection SqlResolveForFile

# noinspection SqlResolveForFile

SELECT object as 'id' FROM example_object WHERE id = $id;");
            while($row = $query->fetch_array()){
                $this->relations[] = new Example_Object($row["id"], array(Entity::ALL));
            }
        }
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
     * @throws IOException
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    #[Pure]
    protected function updateRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
        if ($this->hasFlag(self::ALL)) {
            $query = $database->query("# noinspection SqlResolveForFile

# noinspection SqlResolveForFile

# noinspection SqlResolveForFile

SELECT object as 'id' FROM example_object WHERE id = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->relations as $relation) {
                    if ($relation->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) {
                    (new Example_Object($row["id"]))->remove();
                    $query = $database->query("# noinspection SqlResolveForFile

# noinspection SqlResolveForFile

# noinspection SqlResolveForFile

DELETE FROM example_object_relation where object = $id AND relation = $row[id];");
                }
            }
            foreach ($this->relations as $relation) {
                $relation->store();
            }
        }
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
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("example_object")
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = array(
            "id" => $this->getId()
        );
        // Relations
        $array["relations"] = $this->relations != null ? array() : null;
        if($array["relations"] != null) foreach($this->relations as $value) $array["relations"][] = $value->toArray();
        return $array;
    }

}