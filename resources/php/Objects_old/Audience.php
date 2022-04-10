<?php
namespace Objects;
/*
 * Class imports
 */

use Exceptions\NotNullable;
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


class Audience_old {

    // Database
    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?String $name = null;
    private ?int $minimum_age = null;

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
            $query = $database->query("SELECT * FROM audience WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->name = $row["name"];
                $this->minimum_age = $row["minimum_age"];
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @return Audience
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store() : Audience{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $database->query("START TRANSACTION");
        $query_keys_values = array(
            "id" => $this->id,
            "name" => $this->name,
            "minimum_age" => $this->minimum_age
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "audience")) {
                $size = Database::getColumnSize(column: $key, table: "audience");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'audience') && $value == null) {
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from audience where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "audience") && !Database::isUniqueValue(column: $key, table: "audience", value: $value)) throw new UniqueKey($key);
            }
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO audience ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "audience") && !Database::isUniqueValue(column: $key, table: "audience", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE audience SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        $database->query("COMMIT");
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return Audience
     * @throws IOException
     */
    public function remove() : Audience{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $database->query("DELETE FROM audience where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param String|null $name
     * @param int|null $minimum_age
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     * @throws \ReflectionException
     */
    public static function find(int $id = null, String $name = null, int $minimum_age = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from audience WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from audience WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($name != null ? "(name != null AND name = '$name')" : "") .
                ($minimum_age != null ? "(minimum_age != null AND minimum_age = '$minimum_age')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Audience($row["id"], $flags);
        }
        return $result;
    }


    #[ArrayShape(["id" => "int|mixed|null", "name" => "mixed|null|String", "minimum_age" => "int|mixed|null"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "minimum_age" => $this->minimum_age
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
     * @return Audience
     */
    public function setName(mixed $name): Audience
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int|mixed|null
     */
    public function getMinimumAge(): mixed
    {
        return $this->minimum_age;
    }

    /**
     * @param int|mixed|null $minimum_age
     * @return Audience
     */
    public function setMinimumAge(mixed $minimum_age): Audience
    {
        $this->minimum_age = $minimum_age;
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