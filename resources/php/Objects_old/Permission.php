<?php
namespace Objects;
/*
 * Class imports
 */

use Exceptions\NotNullable;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use mysqli;

/*
 * Object Imports
 */


/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\ColumnNotFound;
use Exceptions\TableNotFound;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;

class Permission_old {

    // Database
    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?String $tag = null;
    private ?String $name = null;
    private ?String $description = null;

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
            $query = $database->query("SELECT * FROM permission WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->tag = $row["tag"];
                $this->name = $row["name"];
                $this->description = $row["description"];
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
    public function store() : Permission{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $database->query("START TRANSACTION");
        $query_keys_values = array(
            "id" => $this->id != null ? $this->id : Database::getNextIncrement("permission"),
            "tag" => $this->tag,
            "name" => $this->name,
            "description" => $this->description
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "permission")) {
                $size = Database::getColumnSize(column: $key, table: "permission");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'permission') && $value == null){
                throw new NotNullable($key);
            }
        }
        if ($this->id == null || $database->query("SELECT id from permission where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "permission") && !Database::isUniqueValue(column: $key, table: "permission", value: $value)) throw new UniqueKey($key);
            }
            $sql_keys = "";
            $sql_values = "";
            foreach ($query_keys_values as $key => $value) {
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys, 0, -1);
            $sql_values = substr($sql_values, 0, -1);
            $sql = "INSERT INTO permission ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "permission") && !Database::isUniqueValue(column: $key, table: "permission", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach ($query_keys_values as $key => $value) {
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql, 0, -1);
            $sql = "UPDATE permission SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        // Relations
        $database->query("COMMIT");
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     * @throws IOException
     */
    public function remove() : Permission{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $database->query("DELETE FROM ROLE_PERMISSION where permission = $this->id");
        $database->query("DELETE FROM permission where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param string|null $tag
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     */
    public static function find(int $id = null, string $tag = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from permission WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from permission WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($tag != null ? "(tag != null AND tag = '$tag')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Permission($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed", "tag" => "mixed", "name" => "mixed|String", "description" => "mixed"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "tag" => $this->tag,
            "name" => $this->name,
            "description" => $this->description
        );
    }

    /**
     * @return int|mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @return mixed|String
     */
    public function getTag(): mixed
    {
        return $this->tag;
    }

    /**
     * @param mixed|String $tag
     * @return $this
     */
    public function setTag(mixed $tag): Permission
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return mixed|String
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @param mixed|String $name
     * @return Permission
     */
    public function setName(mixed $name): Permission
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed|String
     */
    public function getDescription(): mixed
    {
        return $this->description;
    }

    /**
     * @param mixed|String $description
     * @return Permission
     */
    public function setDescription(mixed $description): Permission
    {
        $this->description = $description;
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