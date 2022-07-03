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
    public const ATTACHMENTS = 2;

    // DEFAULT STRUCTURE

    protected ?User $author = null;
    protected ?String $content = null;
    protected ?DateTime $sent_at = null;

    // RELATIONS

    private ?array $attachments = null;

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
        if($this->hasFlag(self::ATTACHMENTS)){
            $this->attachments = array();
            $query = $database->query("SELECT resource as 'id' FROM ticket_message_attachment WHERE message = $id;");
            while($row = $query->fetch_array()){
                $this->attachments[] = new Resource($row["id"], array(Entity::ALL));
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
    public function store(Ticket $ticket) : TicketMessage{
        parent::__store(values: array("ticket" => $ticket->getId()));
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
        if ($this->hasFlag(self::ATTACHMENTS)) {
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
        return $array;
    }

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
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /**
     * @param array|null $attachments
     * @return TicketMessage
     */
    public function setAttachments(?array $attachments): TicketMessage
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * @param Resource $attachment
     * @return $this
     */
    public function addAttachment(Resource $attachment) : TicketMessage{
        $this->attachments[] = $attachment;
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

}