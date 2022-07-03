<?php
namespace Objects;
/*
 * Class imports
 */

use DateTime;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use mysqli;

/*
 * Object Imports
 */

use Objects\LogAction;
use Objects\User;

/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exceptions\IOException;
use Exceptions\MalformedJSON;
use Exception;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\TableNotFound;
use Exceptions\NotNullable;

/*
 * Enumerator Imports
 */
use Enumerators\Availability;
/*
 * Others
 */
use Functions\Database;
use ReflectionException;

class TicketStatus extends Entity {

    // FLAGS

    // DEFAULT STRUCTURE

    protected ?String $name = null;

    // RELATIONS

    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "ticket_status", id: $id, flags: $flags);
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
    public function store() : TicketStatus{
        parent::__store();
        return $this;
    }

    /**
     * @throws IOException
     */
    public function remove() : TicketStatus{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $name = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "name" => $name
        ), table: 'ticket_status', class: 'Objects\TicketStatus', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("ticket_status"),
            "name" => $this->name
        );
    }

    /**
     * @param bool $entities
     * @return array
     */
    #[Pure] #[ArrayShape(["id" => "int|mixed", "name" => "null|String"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->name
        );
    }

    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
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
     * @param String|null $name
     * @return TicketStatus
     */
    public function setName(?string $name): TicketStatus
    {
        $this->name = $name;
        return $this;
    }
}
?>