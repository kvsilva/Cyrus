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


class Resource {

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private int $id;
    private String $title;
    private String $description;
    private String $extension;
    private String $path;

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
            $query = $database->query("SELECT * FROM resource WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->title = $row["title"];
                $this->description = $row["description"];
                $this->extension = $row["extension"];
                $this->path = $row["path"];
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @return $this
     */
    public function store() : Resource{
        GLOBAL $database;
        if($database->query("SELECT id from resource where id = $this->id")->num_rows == 0) {
            $this->id = database_functions::getNextIncrement("resource");
            $sql = "INSERT INTO resource (id, title, description, extension, path) VALUES ($this->id, '$this->title', '$this->description', '$this->extension', '$this->path');";
        } else {
            $sql = "UPDATE resource SET title = '$this->title', description = '$this->description', extension = '$this->extension', path = '$this->path' WHERE id = $this->id";
        }
        $database->query($sql);
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : Resource{
        GLOBAL $database;
        $database->query("DELETE FROM resource where id = $this->id");
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
        GLOBAL $database;
        $sql_command = "";
        if($sql != null){
            $sql_command = "SELECT id from resource WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from resource WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        $result = array();
        while($row = $query->fetch_array()){
            $result[] = new Resource($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed", "title" => "mixed", "description" => "mixed", "extension" => "mixed", "path" => "mixed"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "extension" => $this->extension,
            "path" => $this->path
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
    public function getTitle(): mixed
    {
        return $this->title;
    }

    /**
     * @param mixed|String $title
     * @return Resource
     */
    public function setTitle(mixed $title): Resource
    {
        $this->title = $title;
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
     * @return Resource
     */
    public function setDescription(mixed $description): Resource
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed|String
     */
    public function getExtension(): mixed
    {
        return $this->extension;
    }

    /**
     * @param mixed|String $extension
     * @return Resource
     */
    public function setExtension(mixed $extension): Resource
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return mixed|String
     */
    public function getPath(): mixed
    {
        return $this->path;
    }

    /**
     * @param mixed|String $path
     * @return Resource
     */
    public function setPath(mixed $path): Resource
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return array|int[]
     */
    public function getFlags(): array
    {
        return $this->flags;
    }
}
?>