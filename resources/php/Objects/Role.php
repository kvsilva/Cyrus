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
use Exceptions\NotNullable;
use Exceptions\InvalidDataType;
use Exceptions\RecordNotFound;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
require_once (dirname(__FILE__).'/../database.php');
use Functions\Database as database_functions;


class Role {

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;
    public const PERMISSIONS = 2;

    // DEFAULT STRUCTURE

    private int $id;
    private String $name;

    // RELATIONS

    private array $flags;

    // Role::Permissions
    private array $permissions = array();



    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     */
    function __construct(int $id = null, array $flags = array(self::NORMAL)) {
        $this->flags = $flags;
        if($id != null){
            GLOBAL $database;
            $query = $database->query("SELECT * FROM ROLE WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->name = $row["name"];

                if(in_array(self::PERMISSIONS, $this->flags) || in_array(self::ALL, $this->flags)){
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
     * @throws UniqueKey
     */
    public function store() : Role{
        GLOBAL $database;
        if($database->query("SELECT id from ROLE where id = $this->id")->num_rows == 0) {
            if($database->query("SELECT id from ROLE where name = '$this->name'")->num_rows > 0) {
                throw new UniqueKey("name");
            } else {
                $this->id = database_functions::getNextIncrement("role");
                $sql = "INSERT INTO ROLE (id, name) VALUES ($this->id, '$this->name');";
                $database->query($sql);
            }
        } else {
            if($database->query("SELECT id from ROLE where name = '$this->name'")->num_rows > 0) {
                throw new UniqueKey("name");
            } else {
                $sql = "UPDATE ROLE SET name = '$this->name' WHERE id = $this->id";
                $database->query($sql);
                if (in_array(self::PERMISSIONS, $this->flags)) {
                    $query = $database->query("SELECT permission as 'id' FROM role_permission WHERE role = $this->id;");
                    while ($row = $query->fetch_array()) {
                        $remove = true;
                        foreach ($permission as $this->permissions) {
                            $permission->store();
                            if ($permission->getId() == $row["id"]) {
                                $remove = false;
                                break;
                            }
                        }
                        if ($remove) $database->query("DELETE FROM ROLE_PERMISSION WHERE role = $this->id AND permission = $row[id]");
                    }
                    foreach ($permission as $this->permissions) {
                        $database->query("INSERT IGNORE INTO role_permission (role, permission) VALUES ($this->id, $row[id])");
                    }
                }
            }
        }
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     * @throws UniqueKey
     */
    public function remove() : Role{
        GLOBAL $database;
        $database->query("DELETE FROM USER_ROLE where role = $this->id");
        $database->query("DELETE FROM ROLE_PERMISSION where role = $this->id");
        $database->query("DELETE FROM role where id = $this->id");
        $this->store();
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
        GLOBAL $database;
        $sql_command = "";
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
        $result = array();
        while($row = $query->fetch_array()){
            $result[] = new Role($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed", "name" => "mixed", "permissions" => "array|null"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "permissions" => count($this->permissions) == 0 ? null : $this->permissions
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