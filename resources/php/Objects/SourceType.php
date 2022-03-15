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
use Exceptions\NotNullable;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;


class SourceType {

    // Database
    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?String $name = null;

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
            $query = $database->query("SELECT * FROM source_type WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->name = $row["name"];
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
     * @throws NotNullable
     */
    public function store() : SourceType{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $query_keys_values = array(
            "id" => $this->id,
            "name" => $this->id
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "source_type")) {
                $size = Database::getColumnSize(column: $key, table: "source_type");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'source_type') && $value == null){
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from source_type where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "source_type") && !Database::isUniqueValue(column: $key, table: "source_type", value: $value)) throw new UniqueKey($key);
            }
            $this->id = Database::getNextIncrement("source_type");
            $query_keys_values["id"] = $this->id;
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO source_type ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "source_type") && !Database::isUniqueValue(column: $key, table: "source_type", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE source_type SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : SourceType{
        $database = $this->database;
        $database->query("DELETE FROM source_type where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     */
    public static function find(int $id = null, string $name = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from source_type WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from source_type WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($name != null ? "(name != null AND name = '$name')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new SourceType($row["id"], $flags);
        }
        return $result;
    }



    #[ArrayShape(["id" => "int|mixed|null", "name" => "mixed|null|String"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "name" => $this->name
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
     * @return mixed|String|null
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @param mixed|String|null $name
     * @return SourceType
     */
    public function setName(mixed $name): SourceType
    {
        $this->name = $name;
        return $this;
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