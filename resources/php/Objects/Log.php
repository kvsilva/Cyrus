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
use Objects\LogAction;
use Objects\User;

/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exception;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\MalformedJSON;
use Exceptions\TableNotFound;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;


class Log {

    // Database
    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?User $user = null;
    private ?LogAction $action_type = null;
    private ?array $arguments = null;
    private ?String $description = null;

    // RELATIONS

    private array $flags;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     * @throws MalformedJSON
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
            $query = $database->query("SELECT * FROM log WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->user = new User($row["user"]);
                $this->action_type = new LogAction($row["action_type"]);
                try {
                    $this->arguments = json_decode($row["arguments"], true);
                    $this->description = $this->action_type->getDescription();
                    foreach($this->arguments as $key => $value){
                        $this->description = str_replace($key, $value, $this->description);
                    }
                } catch (Exception $e){
                    $this->arguments = array();
                    throw new MalformedJSON();
                }
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
    public function store() : Log{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $query_keys_values = array(
            "id" => $this->id,
            "user" => isset($this->user) ? $this->user->store()->getId() : null,
            "action_type" => isset($this->action_type) ? $this->action_type->getId() : null,
            "arguments" => isset($this->arguments) ? json_encode($this->arguments) : null
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "log")) {
                $size = Database::getColumnSize(column: $key, table: "log");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            }
        }
        if($this->id == null || $database->query("SELECT id from log where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "log") && !Database::isUniqueValue(column: $key, table: "log", value: $value)) throw new UniqueKey($key);
            }
            $this->id = Database::getNextIncrement("log");
            $query_keys_values["id"] = $this->id;
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO log ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "log") && !Database::isUniqueValue(column: $key, table: "log", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE log SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : Log{
        $database = $this->database;
        $database->query("DELETE FROM log where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param int|null $user
     * @param int|null $action_type
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws MalformedJSON
     * @throws RecordNotFound
     */
    public static function find(int $id = null, int $user = null, int $action_type = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from log WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from log WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($user != null ? "(user != null AND user = '$user')" : "") .
                ($action_type != null ? "(action_type != null AND action_type = '$action_type')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Log($row["id"], $flags);
        }
        return $result;
    }


    #[ArrayShape(["id" => "int|mixed", "user" => "int", "name" => "mixed|String", "action" => "int|mixed", "description" => "mixed"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "user" => isset($this->user) ? $this->user->getId() : null,
            "name" => isset($this->action_type) ? $this->action_type->getName() : null,
            "action" => isset($this->action_type) ? $this->action_type->getId() : null,
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
     * @return \Objects\User
     */
    public function getUser(): \Objects\User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Log
     */
    public function setUser(User $user): Log
    {
        $this->user = $user;
        return $this;
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
     * @return array|mixed
     */
    public function getArguments(): mixed
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
     * @return String
     */
    public function getDescription(): mixed
    {
        return $this->description;
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