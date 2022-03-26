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

class Ticket extends Entity {

    // FLAGS
    public const MESSAGES = 2;

    // DEFAULT STRUCTURE
    protected ?String $title = null;
    protected ?User $attended_by = null;
    protected ?TicketStatus $status = null;
    protected ?DateTime $created_at = null;
    protected ?DateTime $closed_at = null;
    protected ?User $closed_by = null;
    protected ?int $evaluation = null;

    // RELATIONS

    private ?array $messages = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "ticket", id: $id, flags: $flags);
    }

    /**
     * @return void
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    protected function buildRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
        if($this->hasFlag(self::MESSAGES)){
            $this->messages = array();
            $query = $database->query("SELECT id as 'id' FROM ticket_message WHERE ticket = $id;");
            while($row = $query->fetch_array()){
                $this->messages[] = new TicketMessage($row["id"], array(Entity::ALL));
            }
        }
    }

    /**
     * @param User $user
     * @return $this
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store(User $user) : Ticket{
        parent::__store(values: array("user" => $user?->getId()));
        return $this;
    }

    /**
     * @return void
     * @throws IOException
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    #[Pure]
    protected function updateRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
        if ($this->hasFlag(self::ALL)) {
            $query = $database->query("SELECT id as 'id' FROM ticket_message WHERE ticket = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->messages as $message) {
                    if ($message->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) {
                    (new TicketMessage($row["id"]))->remove();
                }
            }
            foreach ($this->messages as $message) {
                $message->store();
            }
        }
    }

    /**
     * @throws IOException
     */
    public function remove() : Ticket{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $user = null, int $attended_by = null, int $closed_by = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "user" => $user,
            "attended_by" => $attended_by,
            "closed_by" => $closed_by
        ), table: 'ticket', class: 'Objects\Ticket', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "attended_by" => "int|null", "status" => "int|mixed|null", "created_at" => "bool|\DateTime|null", "closed_at" => "bool|\DateTime|null", "closed_by" => "int|null", "evaluation" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("ticket"),
            "attended_by" => $this->attended_by?->getId(),
            "status" => $this->status?->getId(),
            "created_at" => $this->created_at != null ? Database::convertDateToDatabase($this->created_at) : $this->created_at,
            "closed_at" => $this->closed_at != null ? Database::convertDateToDatabase($this->closed_at) : $this->closed_at,
            "closed_by" => $this->closed_by?->getId(),
            "evaluation" => $this->evaluation
        );
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "attended_by" => "array", "status" => "array", "created_at" => "null|string", "closed_at" => "null|string", "closed_by" => "array|null", "evaluation" => "int|null"])]
    public function toArray(): array
    {
        $array = array(
            "id" => $this->getId(),
            "attended_by" => $this->attended_by->toArray(),
            "status" => $this->status->toArray(),
            "created_at" => $this->created_at?->format(Database::DateFormat),
            "closed_at" => $this->closed_at?->format(Database::DateFormat),
            "closed_by" => $this->closed_by?->toArray(),
            "evaluation" => $this->evaluation
        );
        // Relations
        $array["messages"] = $this->messages != null ? array() : null;
        if($array["messages"] != null) foreach($this->messages as $value) $array["messages"][] = $value->toArray();
        return $array;
    }

    /**
     * @return String|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param String|null $title
     * @return Ticket
     */
    public function setTitle(?string $title): Ticket
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return \Objects\User|null
     */
    public function getAttendedBy(): ?\Objects\User
    {
        return $this->attended_by;
    }

    /**
     * @param \Objects\User|null $attended_by
     * @return Ticket
     */
    public function setAttendedBy(?\Objects\User $attended_by): Ticket
    {
        $this->attended_by = $attended_by;
        return $this;
    }

    /**
     * @return TicketStatus|null
     */
    public function getStatus(): ?TicketStatus
    {
        return $this->status;
    }

    /**
     * @param TicketStatus|null $status
     * @return Ticket
     */
    public function setStatus(?TicketStatus $status): Ticket
    {
        $this->status = $status;
        return $this;
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
     * @return Ticket
     */
    public function setCreatedAt(?DateTime $created_at): Ticket
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getClosedAt(): ?DateTime
    {
        return $this->closed_at;
    }

    /**
     * @param DateTime|null $closed_at
     * @return Ticket
     */
    public function setClosedAt(?DateTime $closed_at): Ticket
    {
        $this->closed_at = $closed_at;
        return $this;
    }

    /**
     * @return \Objects\User|null
     */
    public function getClosedBy(): ?\Objects\User
    {
        return $this->closed_by;
    }

    /**
     * @param \Objects\User|null $closed_by
     * @return Ticket
     */
    public function setClosedBy(?\Objects\User $closed_by): Ticket
    {
        $this->closed_by = $closed_by;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEvaluation(): ?int
    {
        return $this->evaluation;
    }

    /**
     * @param int|null $evaluation
     * @return Ticket
     */
    public function setEvaluation(?int $evaluation): Ticket
    {
        $this->evaluation = $evaluation;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMessages(): ?array
    {
        return $this->messages;
    }

    /**
     * @param array|null $messages
     * @return Ticket
     */
    public function setMessages(?array $messages): Ticket
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * @param TicketMessage $message
     * @return Ticket
     */
    public function addMessage(TicketMessage $message) : Ticket{
        $this->messages[] = $message;
        return $this;
    }

    /**
     * @param TicketMessage|null $message
     * @param int|null $id
     * @return void
     */
    public function removeMessage(TicketMessage $message = null, int $id = null){
        $remove = array();
        if($message != null){
            for ($i = 0; $i < count($this->messages); $i++) {
                if ($this->messages[$i]->getId() == $message->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->messages); $i++) {
                if ($this->messages[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->messages[$item]);
    }

}
?>