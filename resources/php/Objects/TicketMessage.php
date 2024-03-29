<?php

namespace Objects;

use DateTime;
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
use Objects\Resource;

class TicketMessage extends Entity
{
    // FLAGS
    public const TICKETMESSAGEATTACHMENTS = 2;

    // DEFAULT STRUCTURE

    protected ?User $author = null;
    protected ?String $content = null;
    protected ?DateTime $sent_at = null;

    protected ?Ticket $ticket = null;

    // RELATIONS

    private ?ResourcesArray $attachments = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "ticket_message", id: $id, flags: $flags);
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
        if($this->hasFlag(self::TICKETMESSAGEATTACHMENTS)){
            $this->attachments = new ResourcesArray();
            if($id !== null) {
                $query = $database->query("SELECT resource as 'id' FROM ticket_message_attachment WHERE message = $id;");
                while ($row = $query->fetch_array()) {
                    $this->attachments[] = new Resource($row["id"], array(Entity::ALL));
                }
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
    public function store(?Ticket $ticket = null) : TicketMessage{
        $values = array();
        if($ticket === null) $ticket = $this->ticket;
        $values["ticket"] = $ticket->getId();
        parent::__store(values: $values);
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
        if ($this->hasFlag(self::TICKETMESSAGEATTACHMENTS)) {
            $query = $database->query("SELECT resource as 'id' FROM ticket_message_attachment WHERE message = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->attachments as $attachment) {
                    if ($attachment->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) {
                    (new Resource($row["id"]))->remove();
                    $query = $database->query("DELETE FROM ticket_message_attachment where message = $id AND resource = $row[id];");
                }
            }
            foreach ($this->attachments as $attachment) {
                $attachment->store();
                $database->query("INSERT IGNORE INTO ticket_message_attachment (message, resource) VALUES (". $id .", ". $attachment->getId() .")");
            }
        }
    }

    /**
     * @throws IOException
     */
    public function remove() : TicketMessage{
        parent::__remove();
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, int $ticket = null, string $sql = null, array $flags = [self::NORMAL]) : EntityArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "ticket" => $ticket
        ), table: 'ticket_message', class: 'Objects\TicketMessage', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "author" => "int|mixed|null", "content" => "null|String", "sent_at" => "bool|\DateTime|null"])]
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("ticket_message"),
            "author" => $this->author?->getId(),
            "content" => $this->content,
            "sent_at" => $this->sent_at != null ? Database::convertDateToDatabase($this->sent_at) : null
        );
    }

    /**
     * @param bool $entities
     * @return array
     */
    #[ArrayShape(["id" => "int|mixed", "author" => "array|null", "content" => "null|String", "sent_at" => "null|string"])]
    public function toArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "author" => $this->author?->toArray(false, $entities),
            "content" => $this->content,
            "sent_at" => $this->sent_at?->format(Database::DateFormat)
        );
        // Relations
        if(!$minimal) {
            $array["attachments"] = $this->attachments != null ? array() : null;
            if ($array["attachments"] != null) foreach ($this->attachments as $value) $array["attachments"][] = $value->toArray();
        }
        if($entities){
            $array["ticket"] = $this->ticket?->toArray();
        }
        return $array;
    }

    /**
     * @param bool $minimal
     * @param bool $entities
     * @return array
     */
    #[ArrayShape(["id" => "int|null", "author" => "null|\Objects\User", "content" => "null|String", "sent_at" => "null|string", "attachments" => "array|null"])]
    public function toOriginalArray(bool $minimal = false, bool $entities = false): array
    {
        $array = array(
            "id" => $this->getId(),
            "author" => $this->author,
            "content" => $this->content,
            "sent_at" => $this->sent_at?->format(Database::DateFormat)
        );
        // Relations
        if(!$minimal) {
            $array["attachments"] = $this->attachments != null ? array() : null;
            if ($array["attachments"] != null) foreach ($this->attachments as $value) $array["attachments"][] = $value;
        }
        if($entities){
            $array["ticket"] = $this->ticket;
        }
        return $array;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     * @return TicketMessage
     */
    public function setAuthor(?User $author): TicketMessage
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param String|null $content
     * @return TicketMessage
     */
    public function setContent(?string $content): TicketMessage
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getSentAt(): ?DateTime
    {
        return $this->sent_at;
    }

    /**
     * @param DateTime|null $sent_at
     * @return TicketMessage
     */
    public function setSentAt(?DateTime $sent_at): TicketMessage
    {
        $this->sent_at = $sent_at;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getAttachments(): ?ResourcesArray
    {
        return $this->attachments;
    }

    /**
     * @param ResourcesArray|null $attachments
     * @return TicketMessage
     */
    public function setAttachments(?ResourcesArray $attachments): TicketMessage
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * @param array|\Objects\Resource $attachment
     * @return $this
     * @throws ReflectionException
     */
    public function addAttachment(array|Resource $attachment) : TicketMessage{
        if(is_array($attachment)){
            $resource = new Resource();
            $resource = Entity::arrayToObject($resource, $attachment);
        } else {
            $resource = $attachment;
        }
        $this->attachments[] = $resource;
        return $this;
    }

    /**
     * @param Resource|null $attachment
     * @param int|null $id
     * @return void
     */
    public function removeAttachment(Resource $attachment = null, int $id = null){
        $remove = array();
        if($attachment != null){
            for ($i = 0; $i < count($this->attachments); $i++) {
                if ($this->attachments[$i]->getId() == $attachment->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->attachments); $i++) {
                if ($this->attachments[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->attachments[$item]);
    }

    /**
     * @param int $relation
     * @param mixed $value
     * @return TicketMessage
     */
    public function addRelation(int $relation, mixed $value) : TicketMessage
    {
        switch ($relation) {
            case self::TICKETMESSAGEATTACHMENTS:
                $this->addAttachment($value);
                break;
        }
        return $this;
    }


    /**
     * @param int $relation
     * @param mixed|null $value
     * @param int|null $id
     * @return $this
     */
    public function removeRelation(int $relation, mixed $value = null, int $id = null) : TicketMessage
    {
        switch ($relation) {
            case self::TICKETMESSAGEATTACHMENTS:
                $this->removeAttachment($value, $id);
                break;
        }
        return $this;
    }

}