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
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use ReflectionException;

class Punishment extends Entity
{
    // FLAGS

    // DEFAULT STRUCTURE

    protected ?PunishmentType $punishment_type = null;
    protected ?String $reason = null;
    protected ?DateTime $lasts_until = null;
    protected ?User $performed_by = null;
    protected ?DateTime $performed_date = null;
    protected ?User $revoked_by = null;
    protected ?DateTime $revoked_date = null;
    protected ?String $revoked_reason = null;
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
        parent::__construct(table: "punishment", id: $id, flags: $flags);
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
    public function store(User $user) : Punishment{
        parent::__store(values: array("user" => $user?->getId()));
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : Punishment{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $user = null, int $punishment_type = null, int $performed_by = null, int $revoked_by = null, Availability $available = Availability::AVAILABLE,  string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "user" => $user,
            "punishment_type" => $punishment_type,
            "performed_by" => $performed_by,
            "revoked_by" => $revoked_by,
            "available" => $available?->value
        ), table: 'punishment', class: 'Objects\Punishment', sql: $sql, flags: $flags);
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
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("punishment"),
            "punishment_type" => $this->punishment_type?->store()->getId(),
            "reason" => $this->reason ?? null,
            "lasts_until" => $this->lasts_until?->format(Database::DateFormat),
            "performed_by" => $this->performed_by?->store()->getId(),
            "performed_date" => $this->performed_date?->format(Database::DateFormat),
            "revoked_by" => $this->revoked_by?->store()->getId(),
            "revoked_date" => $this->revoked_date?->format(Database::DateFormat),
            "revoked_reason" => $this->revoked_reason ?? null,
            "available" => $this->available?->value,
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            "id" => $this->getId(),
            "punishment_type" => $this->punishment_type?->toArray(),
            "reason" => $this->reason,
            "lasts_until" => $this->lasts_until?->format(Database::DateFormat),
            "performed_by" => $this->performed_by?->toArray(),
            "performed_date" => $this->performed_date?->format(Database::DateFormat),
            "revoked_by" => $this->revoked_by?->toArray(),
            "revoked_date" => $this->revoked_date?->format(Database::DateFormat),
            "revoked_reason" => $this->revoked_reason,
            "available" => $this->available?->toArray()
        );
    }

    /**
     * @return PunishmentType
     */
    public function getPunishmentType(): PunishmentType
    {
        return $this->punishment_type;
    }

    /**
     * @param PunishmentType $punishment_type
     * @return Punishment
     */
    public function setPunishmentType(PunishmentType $punishment_type): Punishment
    {
        $this->punishment_type = $punishment_type;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param mixed|String $reason
     * @return Punishment
     */
    public function setReason(mixed $reason): Punishment
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastsUntil(): DateTime
    {
        return $this->lasts_until;
    }

    /**
     * @param DateTime $lasts_until
     * @return Punishment
     */
    public function setLastsUntil(DateTime $lasts_until): Punishment
    {
        $this->lasts_until = $lasts_until;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getPerformedDate(): DateTime
    {
        return $this->performed_date;
    }

    /**
     * @param DateTime|String $performed_date
     * @return Punishment
     */
    public function setPerformedDate(DateTime|String $performed_date): Punishment
    {
        if(is_string($performed_date)){
            $this->performed_date = DateTime::createFromFormat(Database::DateFormat, $performed_date);
        } else if(is_a($performed_date, "DateTime")){
            $this->performed_date = $performed_date;
        }
        return $this;
    }

    /**
     * @return User
     */
    public function getPerformedBy(): User
    {
        return $this->performed_by;
    }

    /**
     * @param User $performed_by
     * @return Punishment
     */
    public function setPerformedBy(User $performed_by): Punishment
    {
        $this->performed_by = $performed_by;
        return $this;
    }

    /**
     * @return User
     */
    public function getRevokedBy(): User
    {
        return $this->revoked_by;
    }

    /**
     * @param User $revoked_by
     * @return Punishment
     */
    public function setRevokedBy(User $revoked_by): Punishment
    {
        $this->revoked_by = $revoked_by;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getRevokedDate(): DateTime
    {
        return $this->revoked_date;
    }

    /**
     * @param DateTime|String $revoked_date
     * @return Punishment
     */
    public function setRevokedDate(DateTime|String $revoked_date): Punishment
    {
        if(is_string($revoked_date)){
            $this->revoked_date = DateTime::createFromFormat(Database::DateFormat, $revoked_date);
        } else if(is_a($revoked_date, "DateTime")){
            $this->revoked_date = $revoked_date;
        }
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
     * @param mixed|String $revoked_reason
     * @return Punishment
     */
    public function setRevokedReason(mixed $revoked_reason): Punishment
    {
        $this->revoked_reason = $revoked_reason;
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
     * @return Punishment
     */
    public function setAvailable(?Availability $available): Punishment
    {
        $this->available = $available == null ? Availability::NOT_AVAILABLE : $available;
        return $this;
    }

}