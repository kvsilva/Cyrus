<?php
namespace Objects;
/*
 * Class imports
 */

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/*
 * Object Imports
 */

use mysqli;

/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\TableNotFound;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;


class Example_Object {

    // Database
    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;

    // RELATIONS

    private array $flags;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     */
    function __construct(int $id = null, array $flags = array(self::NORMAL)) {
        $this->flags = $flags;
        try {
            $this->database = Database::getConnection();
        } catch(IOException $e){
            $this->database = null;
        }
        if($id != null && $this->database != null){
            $database = $this->database;
            $query = $database->query("SELECT * FROM example_object WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @return $this
     * @throws IOException
     * @throws InvalidSize
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws TableNotFound
     */
    public function store() : Example_Object{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $query_keys_values = array(
            "id" => $this->id
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "example_object")) {
                $size = Database::getColumnSize(column: $key, table: "example_object");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            }
        }
        if($this->id == null || $database->query("SELECT id from example_object where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "example_object") && !Database::isUniqueValue(column: $key, table: "example_object", value: $value)) throw new UniqueKey($key);
            }
            $this->id = Database::getNextIncrement("example_object");
            $query_keys_values["id"] = $this->id;
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO example_object ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "example_object") && !Database::isUniqueValue(column: $key, table: "example_object", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE example_object SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : Example_Object{
        $database = $this->database;
        $database->query("DELETE FROM example_object where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     */
    public static function find(int $id = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from example_object WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from example_object WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Example_Object($row["id"], $flags);
        }
        return $result;
    }


    #[ArrayShape(["id" => "int|mixed"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id
        );
    }
    /**
     * @return int
     */
    public function getId(): int
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
}
?>