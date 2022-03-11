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


class Permission {

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private int $id;
    private String $tag;
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
     * @throws UniqueKey
     */
    public function store() : Permission{
        GLOBAL $database;
        if($database->query("SELECT id from permission where id = $this->id")->num_rows == 0) {
            if($database->query("SELECT id from permission where tag = '$this->tag'")->num_rows > 0) {
                throw new UniqueKey("tag");
            } else {
                $this->id = database_functions::getNextIncrement("permission");
                $sql = "INSERT INTO permission (id, tag, name, description) VALUES ($this->id, '$this->tag', '$this->name', '$this->description');";
                $database->query($sql);
            }
        } else {
            if($database->query("SELECT id from permission where tag = '$this->tag' AND id <> $this->id")->num_rows > 0) {
                throw new UniqueKey("tag");
            } else {
                $sql = "UPDATE permission SET tag = '$this->tag', name = '$this->name', description = '$this->description' WHERE id = $this->id";
                $database->query($sql);
            }
        }
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : Permission{
        GLOBAL $database;
        $database->query("DELETE FROM ROLE_PERMISSION where permission = $this->id");
        $database->query("DELETE FROM permission where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param string|null $name
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     */
    public static function find(int $id = null, string $tag = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        GLOBAL $database;
        $sql_command = "";
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
        $result = array();
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