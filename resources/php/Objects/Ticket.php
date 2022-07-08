<?php
namespace Objects;
/*
 * Class imports
 */

use DateTime;
use Enumerators\TicketStatus;
use Exceptions\NotInitialized;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exceptions\IOException;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\TableNotFound;
use Exceptions\NotNullable;

/*
 * Enumerator Imports
 */

/*
 * Others
 */
use Functions\Database;
use ReflectionException;

class Ticket extends Entity {

    // FLAGS
    public const TICKETMESSAGES = 2;

    // DEFAULT STRUCTURE
    protected ?String $subject = null;
    protected ?TicketStatus $status = null;
    protected ?User $responsible = null;
    protected ?DateTime $created_at = null;
    protected ?DateTime $closed_at = null;
    protected ?User $closed_by = null;
    protected ?int $evaluation = null;


    // FK

    protected ?User $user = null;

    // RELATIONS

    private ?TicketMessagesArray $messages = null;

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
        if($this->hasFlag(self::TICKETMESSAGES)){
            $this->messages = new TicketMessagesArray();
            $query = $database->query("SELECT id as 'id' FROM ticket_message WHERE ticket = $id;");
            while($row = $query->fetch_array()){
                $this->messages[] = new TicketMessage($row["id"], array(Entity::ALL));
            }
        }
        parent::buildRelations();
    }

    /**
     * @param User|null $user
     * @return $this
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store(?User $user = null) : Ticket{
        if($user === null) $user = $this->user;
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
        parent::updateRelations();
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
    public static function find(int $id = null, int $user = null, int $closed_by = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "user" => $user,
            "closed_by" => $closed_by
        ), table: 'ticket', class: 'Objects\Ticket', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|null", "subject" => "null|String", "status" => "int|null", "responsible" => "int|null", "created_at" => "bool|\DateTime|null", "closed_at" => "bool|\DateTime|null", "closed_by" => "int|null", "evaluation" => "int|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("ticket"),
            "subject" => $this->subject,
            "status" => $this->status?->value,
            "responsible" => $this->responsible?->getId(),
            "created_at" => $this->created_at != null ? Database::convertDateToDatabase($this->created_at) : $this->created_at,
            "closed_at" => $this->closed_at != null ? Database::convertDateToDatabase($this->closed_at) : $this->closed_at,
            "closed_by" => $this->closed_by?->getId(),
            "evaluation" => $this->evaluation
        );
    }

    /**
     * @param bool $entities
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "status" => "array", "created_at" => "null|string", "closed_at" => "null|string", "closed_by" => "array|null", "evaluation" => "int|null"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "status" => $this->status->toArray(),
            "created_at" => $this->created_at?->format(Database::DateFormat),
            "closed_at" => $this->closed_at?->format(Database::DateFormat),
            "closed_by" => $this->closed_by?->toArray(false, $entities),
            "evaluation" => $this->evaluation
        );
        if(!$minimal) {
            // Relations
            $array["messages"] = $this->messages != null ? array() : null;
            if ($array["messages"] != null) foreach ($this->messages as $value) $array["messages"][] = $value->toArray();
        }
        return $array;
    }

    #[ArrayShape(["id" => "int|null", "status" => "\Enumerators\TicketStatus|null", "created_at" => "null|string", "closed_at" => "null|string", "closed_by" => "null|\Objects\User", "evaluation" => "int|null", "messages" => "array|null"])]
    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "status" => $this->status,
            "created_at" => $this->created_at?->format(Database::DateFormat),
            "closed_at" => $this->closed_at?->format(Database::DateFormat),
            "closed_by" => $this->closed_by,
            "evaluation" => $this->evaluation
        );
        if(!$minimal) {
            // Relations
            $array["messages"] = $this->messages != null ? array() : null;
            if ($array["messages"] != null) foreach ($this->messages as $value) $array["messages"][] = $value;
        }
        return $array;
    }

    /**
     * @return String|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     * @return Ticket
     */
    public function setSubject(?string $subject): Ticket
    {
        $this->subject = $subject;
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
    public function getMessages(): ?TicketMessagesArray
    {
        return $this->messages;
    }

    /**
     * @param TicketMessagesArray|null $messages
     * @return Ticket
     */
    public function setMessages(?TicketMessagesArray $messages): Ticket
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

    /**
     * @return User|null
     */
    public function getResponsible(): ?User
    {
        return $this->responsible;
    }

    /**
     * @param User|null $responsible
     * @return Ticket
     */
    public function setResponsible(?User $responsible): Ticket
    {
        $this->responsible = $responsible;
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
     * @return Ticket
     */
    public function setUser(?User $user): Ticket
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param int $relation
     * @param mixed $value
     * @return Ticket
     */
    public function addRelation(int $relation, mixed $value) : Ticket
    {
        switch ($relation) {
            case self::TICKETMESSAGES:
                $this->addMessage($value);
                break;
        }
        return $this;
    }

    /**
     */
    public function removeRelation(int $relation, mixed $value = null, int $id = null) : Ticket
    {
        switch ($relation) {
            case self::TICKETMESSAGES:
                $this->removeMessage($value, $id);
                break;
        }
        return $this;
    }

}
?>