<?php
namespace Objects;
/*
 * Class imports
 */

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use mysqli;

/*
 * Object Imports
 */


/*
 * Exception Imports
 */
use Exceptions\RecordNotFound;
use Exceptions\IOException;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;


class PunishmentType {

    // Database
    private ?Mysqli $database;

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
        try {
            $this->database = Database::getConnection();
        } catch(IOException $e){
            $this->database = null;
        }
        $database = $this->database;
        $this->flags = $flags;
        if($id != null){
            echo "id: " . $id;
            $query = $database->query("SELECT * FROM punishment_type WHERE id = $id;");
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
     */
    public function store() : PunishmentType{
        if($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;

        $query_keys_values = array(
            "id" => $this->id,
            "name" => $this->name
        );
        $sql = "";
        if($this->id == null || $database->query("SELECT id from punishment_type where id = $this->id")->num_rows == 0) {
            $this->id = Database::getNextIncrement("punishment_type");

            $query_keys_values["id"] = $this->id;
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO punishment_type ($sql_keys) VALUES ($sql_values)";
        } else {
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE punishment_type SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : PunishmentType{
        GLOBAL $database;
        $database->query("DELETE FROM punishment_type where id = $this->id");
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
            $sql_command = "SELECT id from punishment_type WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from punishment_type WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        $result = array();
        while($row = $query->fetch_array()){
            $result[] = new PunishmentType($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed", "name" => "mixed|String"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "name" => $this->name
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
     * @return $this
     */
    public function setName(mixed $name): PunishmentType
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