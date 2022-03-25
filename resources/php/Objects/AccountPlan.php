<?php

namespace Objects;

use Enumerators\Availability;
use Enumerators\Removal;
use Exceptions\ColumnNotFound;
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

class AccountPlan extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?String $name = null;
    protected ?int $duration = null;
    protected ?float $price = null;
    protected ?int $stack = null;
    protected ?int $maximum = null;
    protected ?Availability $available = Availability::AVAILABLE;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "account_plan", id: $id, flags: $flags);
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
    public function store() : AccountPlan{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : AccountPlan{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $name = null, float $duration = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "name" => $name,
            "duration" => $duration,
            "available" => $available?->value
        ), table: 'account_plan', class: 'Objects\AccountPlan', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "name" => "null|String", "duration" => "mixed", "price" => "float|int", "stack" => "int|null", "maximum" => "int|null", "available" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("account_plan"),
            "name" => $this->name,
            "duration" => $this->reason ?? null,
            "price" => $this->price ?? 0,
            "stack" => $this->stack ?? null,
            "maximum" => $this->maximum ?? null,
            "available" => $this->available?->value,
        );
    }

    /**
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String", "duration" => "mixed", "price" => "float|int", "stack" => "int|null", "maximum" => "int|null", "available" => "int|null"])]
    public function toArray(): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name,
            "duration" => $this->reason ?? null,
            "price" => $this->price ?? 0,
            "stack" => array(
                "value" => $this->stack,
                "isStackable" => $this->isStackable() ? 'true' : 'false',
                "isUnlimited" => $this->isUnlimitedStackable() ? 'true' : 'false'
            ),
            "maximum" => array(
                "value" => $this->maximum,
                "isPurchasable" => $this->isPurchasable() ? 'true' : 'false',
                "isUnlimited" => $this->isUnlimitedPurchasable() ? 'true' : 'false'
            ),
            "available" => $this->available?->value,
        );
    }

    /**
     * @return bool
     */
    public function isPurchasable() : Bool{
        return ($this->maximum != null && $this->maximum != "" && $this->maximum >= 0);
    }

    /**
     * @return bool
     */
    public function isUnlimitedPurchasable() : Bool{
        return ($this->maximum != null && $this->maximum != "" &&$this->maximum == 0);
    }

    /**
     * @return bool
     */
    #[Pure]
    public function isStackable() : Bool{
        return ($this->stack != null && $this->stack != "" && $this->stack >= 0 && $this->isPurchasable());
    }

    /**
     * @return bool
     */
    #[Pure]
    public function isUnlimitedStackable() : Bool{
        return ($this->maximum != null && $this->maximum != "" && $this->stack == 0 && $this->isPurchasable());
    }

    /**
     * @return String|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed|String|null $name
     * @return AccountPlan
     */
    public function setName(mixed $name): AccountPlan
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int|mixed|null $duration
     * @return AccountPlan
     */
    public function setDuration(mixed $duration): AccountPlan
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|mixed|null $price
     * @return AccountPlan
     */
    public function setPrice(mixed $price): AccountPlan
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStack(): ?int
    {
        return $this->stack;
    }

    /**
     * @param int|mixed|null $stack
     * @return AccountPlan
     */
    public function setStack(mixed $stack): AccountPlan
    {
        if($stack == null || $stack < -1) $stack = -1;
        $this->stack = $stack;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    /**
     * @param int|mixed|null $maximum
     * @return AccountPlan
     */
    public function setMaximum(mixed $maximum): AccountPlan
    {
        if($maximum == null || $maximum < -1) $maximum = -1;
        $this->maximum = $maximum;
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
     * @return AccountPlan
     */
    public function setAvailable(?Availability $available): AccountPlan
    {
        $this->available = $available;
        return $this;
    }

}