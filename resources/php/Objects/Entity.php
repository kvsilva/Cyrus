<?php
namespace Objects;
/*
 * Class imports
 */

use DateTime;
use Enumerators\Removal;
use JetBrains\PhpStorm\ArrayShape;
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
use Exceptions\MalformedJSON;
use Exception;
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
use ReflectionException;
use ReflectionMethod;

abstract class Entity {

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
                $reflection = (new ReflectionClass($this));
                foreach($row as $key => $value){
                    if($value != null) {
                        if(!is_bool(get_parent_class(get_called_class()))){
                            if(property_exists($reflection->getParentClass()->getName(), $key)){
                                if(!$reflection->getParentClass()->getProperty($key)->isProtected() && !$reflection->getParentClass()->getProperty($key)->isPublic()) continue;
                                $type = $reflection->getParentClass()->getProperty($key)->getType();
                            } else if (property_exists(get_called_class(), $key)){
                                if(!$reflection->getProperty($key)->isProtected() && !$reflection->getProperty($key)->isPublic()) continue;
                                $type = $reflection->getProperty($key)->getType();
                            }
                            if(isset($type)){
                                if($value == "") {
                                    $this->{$key} = null;
                                } else if (strtolower($type) == "datetime"){
                                    $this->{$key} = DateTime::createFromFormat(Database::DateFormat, $value);
                                } else if (str_starts_with(strtolower($type), "objects\\")) {
                                    $this->{$key} = (new ReflectionClass($type))->newInstanceArgs(array($value));
                                } else if (str_starts_with(strtolower($type), "enumerators\\")){
                                    $this->{$key} = (new ReflectionMethod($type, "getItem"))->invoke((new ReflectionClass($type))->newInstanceWithoutConstructor(), $value);
                                } else {
                                    $this->{$key} = $value;
                                }
                            }
                        }
                    }
                }
                $this->buildRelations();
            } else {
                throw new RecordNotFound();
            }
        }
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
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
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
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE $table SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        $database->query("COMMIT");
        $this->id = $query_keys_values["id"];
        $this->updateRelations();
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
     * @return array
     * @throws ReflectionException
     */
    protected static function __find(array $fields, String $table, String $class, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from $table WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from $table WHERE ";
            foreach($fields as $key => $value){
                $sql_command .= ($value != null ? "($key IS NOT NULL AND $key = '$value')" : "");
            }
            $sql_command = str_replace(") AND (", ")(", $sql_command);
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        echo $sql_command;
        $query = $database->query($sql_command);
        while($row = $query->fetch_array(MYSQLI_ASSOC)){
            $result[] = (new ReflectionClass($class))->newInstanceArgs(array($row["id"], $flags));
        }

        return $result;
    }

    /**
     * @return array
     */
    public abstract function toArray(): array;

    /**
     * @return array
     */
    abstract protected function valuesArray() : array;

    /**
     * @return int|mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @return array
     */
    protected function getFlags(): array
    {
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
    protected function getTable(): string
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

}
?>