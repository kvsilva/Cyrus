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
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\InvalidDataType;
use Exceptions\RecordNotFound;
use Exceptions\ColumnNotFound;
use Exceptions\TableNotFound;
use Exceptions\NotNullable;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;
use mysqli;


class Role {

    // Database
    private ?MySqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;
    public const PERMISSIONS = 2;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?String $name = null;

    // RELATIONS

    private array $flags;

    // Role::Permissions
    private ?array $permissions = null;


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
            $query = $database->query("SELECT * FROM ROLE WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->name = $row["name"];
                if(in_array(self::PERMISSIONS, $this->flags) || in_array(self::ALL, $this->flags)){
                    $this->permissions = array();
                    $query = $database->query("SELECT permission as 'id' FROM ROLE_PERMISSION WHERE role = $id;");
                    while($row = $query->fetch_array()){
                        $this->permissions[] = new Permission($row["id"]);
                    }
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
     * @throws NotNullable
     */
    public function store() : Role{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $database->query("START TRANSACTION");
        $query_keys_values = array(
            "id" => $this->id != null ? $this->id : Database::getNextIncrement("role"),
            "name" => $this->name
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "role")) {
                $size = Database::getColumnSize(column: $key, table: "role");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'role') && $value == null){
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from ROLE where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "role") && !Database::isUniqueValue(column: $key, table: "role", value: $value)) throw new UniqueKey($key);
            }
            $sql_keys = "";
            $sql_values = "";
            foreach ($query_keys_values as $key => $value) {
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys, 0, -1);
            $sql_values = substr($sql_values, 0, -1);
            $sql = "INSERT INTO role ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "role") && !Database::isUniqueValue(column: $key, table: "role", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach ($query_keys_values as $key => $value) {
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql, 0, -1);
            $sql = "UPDATE role SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);

        // Relations

        if (in_array(self::PERMISSIONS, $this->flags)) {
            $query = $database->query("SELECT permission as 'id' FROM role_permission WHERE role = $this->id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->permissions as $permission) {
                    $permission->store();
                    if ($permission->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) $database->query("DELETE FROM ROLE_PERMISSION WHERE role = $this->id AND permission = $row[id]");
            }
            foreach ($this->permissions as $permission) {
                $database->query("INSERT IGNORE INTO role_permission (role, permission) VALUES ($this->id, $permission->getId())");
            }
        }
        $database->query("COMMIT");
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     * @throws IOException
     */
    public function remove() : Role{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $database->query("DELETE FROM USER_ROLE where role = $this->id");
        $database->query("DELETE FROM ROLE_PERMISSION where role = $this->id");
        $database->query("DELETE FROM role where id = $this->id");
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
    public static function find(int $id = null, string $name = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from role WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from role WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($name != null ? "(name != null AND name = '$name')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new Role($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed|null", "name" => "mixed|null|String", "permissions" => "array|null"])]
    #[Pure]
    public function toArray(): array
    {
        $array = array(
            "id" => $this->id,
            "name" => $this->name
        );
        $array["permissions"] = $this->permissions != null ? array() : null;
        if($array["permissions"] != null) foreach($this->permissions as $value) $array["permissions"][] = $value->toArray();
        return $array;
    }

    /**
     * @return int
     */
    public function getId(): int
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
     * @param String $name
     * @return Role
     */
    public function setName(String $name): Role
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

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     * @return Role
     */
    public function setPermissions(array $permissions): Role
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * @param Permission $permission
     * @return Role
     * @throws InvalidDataType
     */
    public function addPermission(Permission $permission): Role
    {
        if(is_a($permission, 'Permission')){
            $this->permissions[] = $permission;
        } else throw new InvalidDataType("permission", "Permission");
        return $this;
    }

    /**
     * @param Permission|null $permission
     * @param int|null $id
     * @return $this
     * @throws InvalidDataType
     */
    public function removePermission(Permission $permission = null, int $id = null): Role
    {
        if(isset($permission)){
            if(is_a($permission, 'Permission')) {
                for ($i = 0; $i < count($this->permissions); $i++) {
                    if ($this->permissions[$i]->getId() == $permission->getId()) {
                        unset($this->permissions[$i]);
                    }
                }
            } else throw new InvalidDataType("permission", "Permission");
        } else if (isset($id)){
            for ($i = 0; $i < count($this->permissions); $i++) {
                if ($this->permissions[$i]->getId() == $id) {
                    unset($this->permissions[$i]);
                }
            }
        }
        return $this;
    }

    /**
     * @param Permission|null $permission
     * @param String|null $tag
     * @return bool
     */
    public function hasPermission(Permission $permission = null, String $tag = null) : bool{
        foreach($this->permissions as $element){
            if(($permission != null && $element->getId() == $permission->getId()) || ($element != null  && $element->getTag() == $tag)) return true;
        }
        return false;
    }

}
?>