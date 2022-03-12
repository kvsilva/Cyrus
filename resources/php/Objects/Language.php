<?php
namespace Objects;
/*
 * Class imports
 */

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Mysqli;

/*
 * Object Imports
 */


/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exceptions\IOException;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;


class Language {

    // Database
    private ?Mysqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?String $code = null;
    private ?String $name = null;
    private ?String $original_name = null;

    // RELATIONS

    private array $flags;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     */
    function __construct(int $id = null, array $flags = array(self::NORMAL)) {
        try {
            $this->database = Database::getConnection();
        } catch(IOException $e){
            $this->database = null;
        }
        $database = $this->database;
        $this->flags = $flags;
        if($id != null){
            $query = $database->query("SELECT * FROM language WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->code = $row["code"];
                $this->name = $row["name"];
                $this->original_name = $row["original_name"];;
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @return $this
     * @throws UniqueKey
     * @throws IOException
     */
    public function store() : Language{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;

        $query_keys_values = array(
            "id" => $this->id,
            "code" => $this->code,
            "name" => $this->name,
            "original_name" => $this->original_name
        );
        $sql = "";
        if ($this->id == null || $database->query("SELECT id from user where id = $this->id")->num_rows == 0) {
            if($database->query("SELECT id from language where code = '$this->code'")->num_rows > 0) {
                throw new UniqueKey("code");
            } else if($database->query("SELECT id from language where name = '$this->name'")->num_rows > 0) {
                throw new UniqueKey("name");
            } else if($database->query("SELECT id from language where original_name = '$this->original_name'")->num_rows > 0) {
                throw new UniqueKey("original_name");
            } else {
                $this->id = Database::getNextIncrement("language");

                $query_keys_values["id"] = $this->id;
                $sql_keys = "";
                $sql_values = "";
                foreach($query_keys_values as $key => $value){
                    $sql_keys .= $key . ",";
                    $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
                }
                $sql_keys = substr($sql_keys,0,-1);
                $sql_values = substr($sql_values,0,-1) ;
                $sql = "INSERT INTO language ($sql_keys) VALUES ($sql_values)";
                $database->query($sql);
            }
        } else {
            if($database->query("SELECT id from language where code = '$this->code' AND id <> $this->id")->num_rows > 0) {
                throw new UniqueKey("code");
            } else if($database->query("SELECT id from language where name = '$this->name' AND id <> $this->id")->num_rows > 0) {
                throw new UniqueKey("name");
            } else if($database->query("SELECT id from language where original_name = '$this->original_name' AND id <> $this->id")->num_rows > 0) {
                throw new UniqueKey("original_name");
            } else {
                $update_sql = "";
                foreach ($query_keys_values as $key => $value) {
                    $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
                }
                $update_sql = substr($update_sql, 0, -1);
                $sql = "UPDATE language SET $update_sql WHERE id = $this->id";
                $database->query($sql);
            }
        }
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : Language{
        GLOBAL $database;
        $database->query("DELETE FROM language where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     */
    public static function find(int $id = null, String $code = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        GLOBAL $database;
        $sql_command = "";
        if($sql != null){
            $sql_command = "SELECT id from language WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from language WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($code != null ? "(code != null AND code = '$code')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        $result = array();
        while($row = $query->fetch_array()){
            $result[] = new Language($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed", "code" => "mixed|String", "name" => "mixed|String", "original_name" => "mixed|String"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "code" => $this->code,
            "name" => $this->name,
            "original_name" => $this->original_name
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
    public function getCode(): mixed
    {
        return $this->code;
    }

    /**
     * @param mixed|String $code
     * @return Language
     */
    public function setCode(mixed $code): Language
    {
        $this->code = $code;
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
     * @return Language
     */
    public function setName(mixed $name): Language
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed|String
     */
    public function getOriginalName(): mixed
    {
        return $this->original_name;
    }

    /**
     * @param mixed|String $original_name
     * @return Language
     */
    public function setOriginalName(mixed $original_name): Language
    {
        $this->original_name = $original_name;
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