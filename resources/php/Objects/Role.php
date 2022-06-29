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

    protected ?string $name = null;

    // RELATIONS

    // Role::Permissions
    private ?PermissionsArray $permissions = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL, self::PERMISSIONS))
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
        if ($this->hasFlag(self::PERMISSIONS)) {
            $this->permissions = new PermissionsArray();
            $query = $database->query("SELECT permission as 'id' FROM ROLE_PERMISSION WHERE role = $id;");
            while ($row = $query->fetch_array()) {
                $this->permissions[] = new Permission($row["id"]);
            }
        }
        parent::buildRelations();
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
    public function store(): Role
    {
        parent::__store();
        return $this;
    }

    /**
     * @return void
     */
    protected function updateRelations()
    {
        parent::updateRelations();
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
                $database->query("INSERT IGNORE INTO role_permission (role, permission) VALUES ($id, ".$permission->getId() .")");
            }
        }
    }

    /**
     * @throws IOException
     */
    public function remove(): Role
    {
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, string $name = null, string $sql = null, array $flags = [self::NORMAL]): EntityArray
    {
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
        $array = array(
            "id" => $this->getId(),
            "name" => $this->name
        );
        $array["permissions"] = null;
        if($this->permissions != null) {
            $array["permissions"] = array();
            foreach($this->permissions as $value) $array["permissions"][] = $value->toArray();
        };
        return $array;
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
    public function setName(string $name): Role
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
     * @param PermissionsArray $permissions
     * @return Role
     */
    public function setPermissions(PermissionsArray $permissions): Role
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
        $this->permissions[] = $permission;
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
        if (isset($permission)) {
            for ($i = 0; $i < count($this->permissions); $i++) {
                if ($this->permissions[$i]->getId() == $permission->getId()) {
                    unset($this->permissions[$i]);
                }
            }
        } else if (isset($id)) {
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
    public function hasPermission(Permission $permission = null, string $tag = null): bool
    {
        foreach ($this->permissions as $element) {
            if (($permission != null && $element->getId() == $permission->getId()) || ($element != null && $element->getTag() == $tag)) return true;
        }
        return false;
    }

    public function setRelation(int $relation, array|EntityArray $value): Role
    {
        switch ($relation) {
            case self::PERMISSIONS:
                $this->setPermissions($value);
                break;
        }
        return $this;
    }

    /**
     * @throws InvalidDataType
     */
    public function addRelation(int $relation, mixed $value): Role
    {
        switch ($relation) {
            case self::PERMISSIONS:
                $this->addPermission($value);
                break;
        }
        return $this;
    }

    /**
     * @throws InvalidDataType
     */
    public function removeRelation(int $relation, mixed $value = null, int $id = null): Role
    {
        switch ($relation) {
            case self::PERMISSIONS:
                $this->removePermission(permission: $value);
                break;
        }
        return $this;
    }

}