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


/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
require_once (dirname(__FILE__).'/../database.php');
use Functions\Database as database_functions;


class LogAction {

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private int $id;
    private String $name;
    private String $description;

    // RELATIONS

    private array $flags;
    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     */
    function __construct(int $id = null, array $flags = array(self::NORMAL)) {
        $this->flags = $flags;
        if($id != null){
            GLOBAL $database;
            $query = $database->query("SELECT * FROM log_action WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
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
     * @throws UniqueKey
     */
    public function store() : LogAction{
        GLOBAL $database;
        if($database->query("SELECT id from log_action where id = $this->id")->num_rows == 0) {
            if($database->query("SELECT id from log_action where name = '$this->name'")->num_rows > 0) {
                throw new UniqueKey("name");
            } else {
                $this->id = database_functions::getNextIncrement("log_action");
                $sql = "INSERT INTO log_action (id, name, description) VALUES ($this->id, '$this->name', '$this->description');";
                $database->query($sql);
            }
        } else {
            if($database->query("SELECT id from log_action where name = '$this->name' AND id <> $this->id")->num_rows > 0) {
                throw new UniqueKey("name");
            } else {
                $sql = "UPDATE log_action SET name = '$this->name', description = '$this->description' WHERE id = $this->id";
                $database->query($sql);
            }
        }
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : LogAction{
        GLOBAL $database;
        $database->query("DELETE FROM log_action where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param String|null $name
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     */
    public static function find(int $id = null, String $name = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        GLOBAL $database;
        $sql_command = "";
        if($sql != null){
            $sql_command = "SELECT id from log_action WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from log_action WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($name != null ? "(name != null AND name = '$name')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        $result = array();
        while($row = $query->fetch_array()){
            $result[] = new LogAction($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed", "name" => "mixed|String", "description" => "mixed|String"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
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
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @param mixed|String $name
     * @return LogAction
     */
    public function setName(mixed $name): LogAction
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
     * @return LogAction
     */
    public function setDescription(mixed $description): LogAction
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