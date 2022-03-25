<?php

namespace Objects;

use DateTime;
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
use ReflectionException;

class AccountPurchase extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?AccountPlan $plan = null;
    protected ?float $price = null;
    protected ?DateTime $purchased_on = null;
    protected ?int $duration = null;
    protected ?User $revoked_by = null;
    protected ?String $revoked_reason = null;
    protected ?DateTime $revoked_at = null;
    protected ?DateTime $rescued_at = null;
    protected ?Availability $available = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "account_purchase", id: $id, flags: $flags);
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
    public function store(User $user) : AccountPurchase{
        parent::__store(values: array("user" => $user->getId()));
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : AccountPurchase{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $user = null, int $plan = null, int $revoked_by = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "user" => $user,
            "plan" => $plan,
            "revoked_by" => $revoked_by,
            "available" => $available?->value
        ), table: 'account_purchase', class: 'Objects\AccountPurchase', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("account_purchase"),
            "plan" => $this->plan?->store()->getId(),
            "price" => $this->price ?? null,
            "purchased_on" => $this->purchased_on?->format(Database::DateFormat),
            "duration" => $this->duration ?? null,
            "revoked_by" => $this->revoked_by?->store()->getId(),
            "revoked_reason" => $this->revoked_reason ?? null,
            "revoked_at" => $this->revoked_at?->format(Database::DateFormat),
            "rescued_at" => $this->rescued_at?->format(Database::DateFormat),
            "available" => $this->available?->value
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            "id" => $this->getId(),
            "plan" => $this->plan?->toArray(),
            "price" => $this->price,
            "purchased_on" => $this->purchased_on?->format(Database::DateFormat),
            "duration" => $this->duration,
            "revoked_by" => $this->revoked_by?->toArray(),
            "revoked_reason" => $this->revoked_reason,
            "revoked_at" => $this->revoked_at?->format(Database::DateFormat),
            "rescued_at" => $this->rescued_at?->format(Database::DateFormat),
            "available" => $this->available?->toArray()
        );
    }
    /**
     * @return AccountPlan|null
     */
    public function getPlan(): ?AccountPlan
    {
        return $this->plan;
    }

    /**
     * @param AccountPlan|null $plan
     * @return AccountPurchase
     */
    public function setPlan(?AccountPlan $plan): AccountPurchase
    {
        $this->plan = $plan;
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
     * @return AccountPurchase
     */
    public function setPrice(mixed $price): AccountPurchase
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return DateTime|false|null
     */
    public function getPurchasedOn(): DateTime|bool|null
    {
        return $this->purchased_on;
    }

    /**
     * @param DateTime|String $purchased_on
     * @return AccountPurchase
     */
    public function setCreationDate(DateTime|String $purchased_on): AccountPurchase
    {
        if(is_string($purchased_on)){
            $this->purchased_on = DateTime::createFromFormat(Database::DateFormat, $purchased_on);
        } else if(is_a($purchased_on, "DateTime")){
            $this->purchased_on = $purchased_on;
        }
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
     * @return AccountPurchase
     */
    public function setDuration(mixed $duration): AccountPurchase
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getRevokedBy(): ?User
    {
        return $this->revoked_by;
    }

    /**
     * @param User|null $revoked_by
     * @return AccountPurchase
     */
    public function setRevokedBy(?User $revoked_by): AccountPurchase
    {
        $this->revoked_by = $revoked_by;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getRevokedReason(): ?string
    {
        return $this->revoked_reason;
    }

    /**
     * @param mixed|String|null $revoked_reason
     * @return AccountPurchase
     */
    public function setRevokedReason(mixed $revoked_reason): AccountPurchase
    {
        $this->revoked_reason = $revoked_reason;
        return $this;
    }

    /**
     * @return DateTime|false|null
     */
    public function getRevokedAt(): DateTime|bool|null
    {
        return $this->revoked_at;
    }

    /**
     * @param DateTime|String $revoked_at
     * @return AccountPurchase
     */
    public function setRevokedAt(DateTime|String $revoked_at): AccountPurchase
    {
        if(is_string($revoked_at)){
            $this->revoked_at = DateTime::createFromFormat(Database::DateFormat, $revoked_at);
        } else if(is_a($revoked_at, "DateTime")){
            $this->revoked_at = $revoked_at;
        }
        return $this;
    }

    /**
     * @return DateTime|false|null
     */
    public function getRescuedAt(): DateTime|bool|null
    {
        return $this->rescued_at;
    }

    /**
     * @param DateTime|String $rescued_at
     * @return AccountPurchase
     */
    public function setRescuedAt(DateTime|String $rescued_at): AccountPurchase
    {
        if(is_string($rescued_at)){
            $this->rescued_at = DateTime::createFromFormat(Database::DateFormat, $rescued_at);
        } else if(is_a($rescued_at, "DateTime")){
            $this->rescued_at = $rescued_at;
        }
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
     * @return AccountPurchase
     */
    public function setAvailable(?Availability $available): AccountPurchase
    {
        $this->available = $available;
        return $this;
    }


}