<?php

namespace Objects;

use DateTime;
use Enumerators\Availability;
use Enumerators\Removal;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\NotInitialized;
use Exceptions\NotNullable;
use Exceptions\RecordNotFound;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Functions\Database;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use ReflectionException;

class News extends Entity
{
    // FLAGS

    public const NEWSBODY = 2;

    // DEFAULT STRUCTURE

    protected ?DateTime $created_at = null;
    protected ?bool $spotlight = null;
    protected ?Availability $available = null;

    // FK

    protected ?User $user = null;

    // RELATIONS

    protected ?NewsBodysArray $editions = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "news", id: $id, flags: $flags);
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
    public function store() : News{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : News{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, String $spotlight = null, int $user = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "user" => $user,
            "spotlight" => $spotlight,
            "available" => $available?->value
        ), table: 'news', class: 'Objects\News', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */

    #[ArrayShape(["id" => "int|null", "created_at" => "null|string", "spotlight" => "bool|null", "available" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("news"),
            "created_at" => $this->created_at?->format(Database::DateFormatSimplified),
            "spotlight" => $this->spotlight,
            "available" => $this->available?->value,
        );
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "name" => "null|String", "minimum_age" => "int|null"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
        "id" => $this->getId(),
        "created_at" => $this->created_at?->format(Database::DateFormatSimplified),
        "spotlight" => $this->spotlight,
        "available" => $this->available?->toArray(),
    );
        if($entities){
            $array["user"] = $this->user?->toArray();
        }
        if(!$minimal){
            $array["editions"] = null;
            if ($this->editions != null) {
                $array["editions"] = array();
                foreach ($this->editions as $value) $array["editions"][] = $value->toArray();
            }
        }
        return $array;
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[ArrayShape(["id" => "int|null", "created_at" => "null|string", "spotlight" => "bool|null", "available" => "array|null", "user" => "array|null"])]public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "created_at" => $this->created_at?->format(Database::DateFormatSimplified),
            "spotlight" => $this->spotlight,
            "available" => $this->available,
        );
        if($entities){
            $array["user"] = $this->user;
        }
        if(!$minimal){
            $array["editions"] = null;
            if ($this->editions != null) {
                $array["editions"] = array();
                foreach ($this->editions as $value) $array["editions"][] = $value;
            }
        }
        return $array;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    /**
     * @param DateTime|null $created_at
     * @return News
     */
    public function setCreatedAt(?DateTime $created_at): News
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSpotlight(): ?bool
    {
        return $this->spotlight;
    }

    /**
     * @param bool|null $spotlight
     * @return News
     */
    public function setSpotlight(?bool $spotlight): News
    {
        $this->spotlight = $spotlight;
        return $this;
    }

    /**
     * @return Availability|null
     */
    public function getAvailable(): ?Availability
    {
        return $this->available;
    }

    /**
     * @param Availability|null $available
     * @return News
     */
    public function setAvailable(?Availability $available): News
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return News
     */
    public function setUser(?User $user): News
    {
        $this->user = $user;
        return $this;
    }

    public function addRelation(int $relation, mixed $value) : News
    {
        switch ($relation) {
            case self::NEWSBODY:
                $this->addEdition($value);
                break;
        }
        return $this;
    }

    /**
     * @throws NotInitialized
     * @noinspection PhpParamsInspection
     */
    public function removeRelation(int $relation, mixed $value = null, int $id = null) : News
    {
        switch ($relation) {
            case self::NEWSBODY:
                $this->removeEdition($value, $id);
                break;
        }
        return $this;
    }

    /**
     * @param NewsBody $entity
     * @return News
     * @throws NotInitialized
     */
    public function addEdition(NewsBody $entity): News
    {
        if($this->editions == null) throw new NotInitialized("NewsBody");
        $this->editions[] = $entity;
        return $this;
    }

    /**
     * @param NewsBody|null $entity
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removeEdition(NewsBody $entity = null, int $id = null): News
    {
        if($this->editions == null) throw new NotInitialized("NewsBody");
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->editions); $i++) {
                if ($this->editions[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->editions); $i++) {
                if ($this->editions[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->editions[$item]);
        return $this;
    }


}