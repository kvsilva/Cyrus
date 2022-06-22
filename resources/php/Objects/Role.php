<?php

namespace Objects;

use Exceptions\ColumnNotFound;
use Exceptions\InvalidDataType;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Exceptions\RecordNotFound;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Functions\Database;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use ReflectionException;

class Role extends Entity
{
    // FLAGS
    public const PERMISSIONS = 2;

    // DEFAULT STRUCTURE

    protected ?String $name = null;

    // RELATIONS

    // Role::Permissions
    private ?array $permissions = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "role", id: $id, flags: $flags);
    }

    /**
     * @return void
     * @throws RecordNotFound|ReflectionException
     */
    protected function buildRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
        if($this->hasFlag(self::PERMISSIONS)){
            $this->permissions = array();
            $query = $database->query("SELECT permission as 'id' FROM ROLE_PERMISSION WHERE role = $id;");
            while($row = $query->fetch_array()){
                $this->permissions[] = new Permission($row["id"]);
            }
        }
    }

    /**
     * @return $this
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store() : Role{
        parent::__store();
        return $this;
    }

    /**
     * @return void
     */
    public function updateRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
        if ($this->hasFlag(self::PERMISSIONS)) {
            $query = $database->query("SELECT permission as 'id' FROM role_permission WHERE role = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->permissions as $permission) {
                    $permission->store();
                    if ($permission->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) $database->query("DELETE FROM ROLE_PERMISSION WHERE role = $id AND permission = $row[id]");
            }
            foreach ($this->permissions as $permission) {
                $database->query("INSERT IGNORE INTO role_permission (role, permission) VALUES ($id, $permission->getId())");
            }
        }
    }

    /**
     * @throws IOException
     */
    public function remove() : Role{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, string $name = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "name" => $name
        ), table: 'role', class: 'Objects\Role', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("role"),
            "name" => $this->name
        );
    }

    /**
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    public function toArray(bool $minimal = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name
        );
    }

    /**
     * @return String|null
     */
    public function getName(): ?string
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