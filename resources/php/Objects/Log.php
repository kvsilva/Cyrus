<?php
namespace Objects;
/*
 * Class imports
 */

use Exception;
use Exceptions\MalformedJSON;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

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

/*
 * Enumerator Imports
 */

/*
 * Others
 */
require_once (dirname(__FILE__).'/../database.php');
use Functions\Database as database_functions;


class Log {

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private int $id;
    private User $user;
    private LogAction $action_type;
    private array $arguments;
    private String $description;

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
        if($id != null){
            GLOBAL $database;
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
     */
    public function store() : Log{
        GLOBAL $database;
        $arguments = json_encode($this->arguments);
        if($database->query("SELECT id from log where id = $this->id")->num_rows == 0) {
            $this->id = database_functions::getNextIncrement("log");
            $sql = "INSERT INTO log (id, user, action_type, arguments) VALUES ($this->id, '$this->user->getId()', '$this->action_type->getId()', '$arguments');";
        } else {
            $sql = "UPDATE log SET user = '$this->user->getId()', action_type = '$this->action_type->getId()', arguments = '$arguments' WHERE id = $this->id";
        }
        $database->query($sql);
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : Log{
        GLOBAL $database;
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
        GLOBAL $database;
        $sql_command = "";
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
        $result = array();
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
            "user" => $this->user->getId(),
            "name" => $this->action_type->getName(),
            "action" => $this->action_type->getId(),
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
     * @param \Objects\User $user
     * @return Log
     */
    public function setUser(\Objects\User $user): Log
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