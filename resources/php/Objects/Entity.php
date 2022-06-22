<?php
namespace Objects;
/*
 * Class imports
 */

use DateTime;
use Enumerators\Removal;
use JetBrains\PhpStorm\Pure;
use mysqli;

/*
 * Object Imports
 */

use Objects\LogAction;
use Objects\User;

/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exceptions\IOException;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\TableNotFound;
use Exceptions\NotNullable;

/*
 * Enumerator Imports
 */
use Enumerators\Availability;
/*
 * Others
 */
use Functions\Database;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use ReflectionMethod;

abstract class Entity
{

    // Database
    private ?Mysqli $database;

    // Table
    private String $table;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;

    // RELATIONS

    private array $flags;

    /**
     * @param String $table
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    function __construct(String $table, int $id = null, array $flags = array(self::NORMAL)) {
        $this->flags = $flags;
        $this->table = $table;
        $this->id = $id;
        try {
            $this->database = Database::getConnection();
        } catch(IOException $e){
            $this->database = null;
        }
        if($id != null && $this->database != null){
            $database = $this->database;
            $query = $database->query("SELECT * FROM $table WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $this->arrayObject($row);
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    private function arrayObject(array $array): void
    {
        $reflection = (new ReflectionClass($this));

        foreach($this->getValidFlags($this, $array["relations"] ?? null) as $flag){
            if(!$this->hasFlag($flag)){
                $this->flags[] = $flag;
            }
        }
        foreach($array as $key => $value) {
            if (strtolower($key) != "relations") {
                if ($value !== null) {
                    if (!is_bool(get_parent_class(get_called_class()))) {
                        if (property_exists($reflection->getParentClass()->getName(), $key)) {
                            if (!$reflection->getParentClass()->getProperty($key)->isProtected() && !$reflection->getParentClass()->getProperty($key)->isPublic()) continue;
                            $type = $reflection->getParentClass()->getProperty($key)->getType();
                        } else if (property_exists(get_called_class(), $key)) {
                            if (!$reflection->getProperty($key)->isProtected() && !$reflection->getProperty($key)->isPublic()) continue;
                            $type = $reflection->getProperty($key)->getType();
                        }
                        $value_type = gettype($value) == "object" ? get_class($value) : gettype($value);
                        if (isset($type)) {
                            if ($value == "") {
                                $this->{$key} = null;
                            } else if (str_contains(strtolower($type), "datetime")) {
                                $value = str_replace("/","-", $value);
                                $date = DateTime::createFromFormat(Database::DateFormat, $value);
                                if (is_bool($date)) $date = DateTime::createFromFormat(Database::DateFormatSimplified, $value);
                                $this->{$key} = $date;
                            } else if (str_contains(strtolower($type), "objects\\")) {
                                $name = substr(strtolower($type), 1);
                                if (is_array($value)) {
                                    $class = (new ReflectionClass($name));
                                    $class->newInstanceArgs(array("id" => null));
                                    $this->{$key} = $class->getMethod("arrayToObject")->invokeArgs(object: null, args: array($value));
                                } else {
                                    $this->{$key} = (new ReflectionClass($name))->newInstanceArgs(array($value));
                                }
                            } else if (str_contains(strtolower($type), "enumerators\\")) {
                                $name = substr(strtolower($type), 1);
                                if (is_array($value)) {
                                    $this->{$key} = (new ReflectionEnum($name))->getMethod("getItem")->invokeArgs(object: null, args: array($value["value"]));
                                } else {
                                    $this->{$key} = (new ReflectionEnum($name))->getMethod("getItem")->invokeArgs(object: null, args: array($value));
                                }
                            } else {
                                $this->{$key} = $value;
                            }
                        }
                    }
                }
            }
        }
        if($this->getId() != null) $this->buildRelations();
        $this->arrayRelations($array["relations"] ?? null);
    }

    /**
     * @throws ReflectionException
     */
    public function arrayRelations(?array $relations) : void
    {
        if($relations != null) {
            foreach ($relations as $key => $value) {
                if(sizeof($value) == 0) continue;
                $const = $this->getConstant(strtoupper($key));
                $array_name = "Objects\\" . ucwords($key) . "Array";
                if (class_exists($array_name)) {
                    $object_name = (new ReflectionClass($array_name))->newInstanceArgs()->isArrayOf();
                    if(!$this->hasFlag($const)){
                        $this->flags[] = $const;
                        if($this->getId() != null) $this->buildRelations();
                        $this->setRelation($const, (new ReflectionClass($array_name))->newInstanceArgs());

                    }
                    foreach ($value as $relation_array) {
                        if(sizeof($relation_array) == 0) continue;
                        // relation_array hasValidFields
                        $obj = static::arrayToObject(object: $object_name, array: $relation_array);
                        $this->addRelation($const, $obj);
                    }
                }
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    private function getValidFlags(Entity|string $object, array $array = null) : array
    {
        if (is_string($object)) {
            $object = (new ReflectionClass($object))->newInstanceWithoutConstructor();
        }
        $flags = array(Entity::NORMAL);
        if($array != null) {
            foreach ($array as $flag => $relation) {
                if ($object->hasConstant(strtoupper($flag))) {
                    $flags[] = $object->getConstant(strtoupper($flag));
                }
            }
        }
        return $flags;
    }

    /**
     * @throws ReflectionException
     */
    public static function arrayToObject(Entity|String $object, array $array = array(), int $id = null, array $flags = array(self::NORMAL)) : Entity
    {
        if($id == null && isset($array["id"])) $id = $array["id"];
        if(is_string($object)){
            $object = (new ReflectionClass($object))->newInstanceArgs(array("id" => $id, "flags" => $flags));
        }
        $object->arrayObject($array);
        return $object; // is Entity
    }

    /**
     * @throws ReflectionException
     */
    public static function arrayToRelations(Entity $object, array $array, int $id = null, array $flags = array(self::NORMAL)) : Entity
    {
        if($array != null && sizeof($array) > 0) {
            $object->arrayRelations($array);
        }
        return $object; // is Entity
    }
    /**
     * @return void
     */
    protected function buildRelations(){}

    /**
     * This method will update the data in the database, according to the object properties
     * @return $this
     * @throws IOException
     * @throws InvalidSize
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws TableNotFound
     * @throws NotNullable
     */
    protected function __store($values = null) : Entity{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $table = $this->table;
        $database->query("START TRANSACTION");
        if($values != null){
            if(is_array($values)){
                $query_keys_values = $this->valuesArray();
                foreach($values as $key => $v) {
                    if ($v == null) throw new NotNullable(argument: $key);
                    $query_keys_values[$key] = $v;
                }
            } else $query_keys_values = $this->valuesArray();
        } else $query_keys_values = $this->valuesArray();
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: $table)) {
                $size = Database::getColumnSize(column: $key, table: $table);
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: $table) && $value == null){
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from $table where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: $table) && !Database::isUniqueValue(column: $key, table: $table, value: $value)) throw new UniqueKey($key);
            }
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= "`" . $key . "`,";
                $sql_values .= ($value !== null ? "'" . $value . "'" : "default") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO $table ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: $table) && !Database::isUniqueValue(column: $key, table: $table, value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ("`" . $key . "` = " . ($value !== null ? "'" . $value . "'" : "default")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE $table SET $update_sql WHERE id = $this->id";
        }
        //echo "Sql: \n" . $sql . "\n";
        $database->query($sql);
        $database->query("COMMIT");
        $this->id = $query_keys_values["id"];
        if($this->getId() != null) $this->updateRelations();
        return $this;
    }

    /**
     * @return void
     */
    protected function updateRelations(){}

    /**
     * This method will remove the object from the database.
     * @param Removal $method
     * @return Entity
     * @throws IOException
     */
    public function __remove(Removal $method = Removal::DELETE) : Entity{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $table = $this->table;
        // Availability
        if($method == Removal::AVAILABILITY) {
            $this->{"availability"} = Availability::NOT_AVAILABLE;
            $sql = "UPDATE $table SET available = '". $this->{"availability"}->value ."' WHERE id = $this->id";
            $database->query($sql);
        } else {
            // Delete
            $database->query("DELETE FROM $table where id = $this->id");
        }
        return $this;
    }

    /**
     * @param array $fields
     * @param String $table
     * @param String $class
     * @param string|null $sql
     * @param array $flags
     * @return EntityArray
     * @throws ReflectionException
     */
    public static function __find(array $fields, String $table, String $class, string $sql = null, string $operator = "=", array $flags = [self::NORMAL]): EntityArray
    {
        if(class_exists($class . "sArray")){
            $result = (new ReflectionClass($class . "sArray"))->newInstanceArgs(array());
        } else {
            $result = new EntityArray($class);
        }
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from $table WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from $table WHERE ";
            $clause = false;
            foreach($fields as $key => $value){
                $clause = true;
                $sql_command .= ($value != null ? "($table.`$key` IS NOT NULL AND $table.`$key` ". $operator ." '$value') AND " : "");
            }
            if($clause) $sql_command = substr($sql_command,0,-4);
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array(MYSQLI_ASSOC)){
            $result[] = (new ReflectionClass($class))->newInstanceArgs(array($row["id"], $flags));
        }
        return $result;
    }

    /**
     * @param bool $minimal
     * @return array
     */
    public abstract function toArray(bool $minimal = false): array;

    /**
     * @return array
     */
    abstract protected function valuesArray() : array;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * @param int $flag
     * @return array
     */
    public function addFlag(int $flag): array
    {
        if(!$this->hasFlag($flag)){
            $this->flags[] = $flag;
            if($this->getId() != null) $this->buildRelations();
        }
        return $this->flags;
    }

    /**
     * @param array $flags
     * @return array
     */
    public function addFlags(array $flags): array
    {
        $newFlag = false;
        foreach($flags as $flag){
            if(!$this->hasFlag($flag)){
                $newFlag = true;
                $this->flags[] = $flag;
            }
        }
        if($newFlag && $this->getId() != null) $this->buildRelations();
        return $this->flags;
    }

    /**
     * @return mysqli|null
     */
    protected function getDatabase(): ?mysqli
    {
        return $this->database;
    }

    /**
     * @return String
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param int $flag
     * @return bool
     */
    #[Pure]
    protected function hasFlag(int $flag) : bool{
        return (in_array($flag, $this->getFlags()) || in_array(self::ALL, $this->getFlags()));
    }

    /**
     * @param int $relation
     * @param mixed $value
     * @return $this
     */
    public function setRelation(int $relation, EntityArray $value) : Entity{
        return $this;
    }

    /**
     * @param int $relation
     * @param Entity $value
     * @return $this
     */
    public function addRelation(int $relation, Entity $value) : Entity
    {
        return $this;
    }

    /**
     * @param int $relation
     * @param Entity|null $value
     * @param int|null $id
     * @return $this
     */
    public function removeRelation(int $relation, Entity $value = null, int $id = null) : Entity
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getConstants(): array
    {
        $reflection = (new ReflectionClass($this));
        return $reflection->getConstants();
    }

    /**
     * @return array
     */
    public function getConstant(String $name): mixed
    {
        $reflection = (new ReflectionClass($this));
        return $reflection->getConstant($name);
    }

    /**
     * @param String $name
     * @return bool
     */
    public function hasConstant(String $name): bool
    {
        $reflection = (new ReflectionClass($this));
        return !is_bool($reflection->getConstant($name));
    }


}
?>