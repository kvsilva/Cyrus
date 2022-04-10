<?php

namespace Objects;

use Constants\API_MESSAGES;
use DateTime;
use Exceptions\IOException;
use Functions\Database;
use http\Message;
use ReflectionException;

class Request
{

    private String $target;
    private ?Entity $object = null;

    private String $action;

    private array $data;
    private ?User $user;

    private DateTime $time;

    private array $raw;

    /**
     * @param array $array
     * @param User|null $user
     * @throws ReflectionException
     */
    public function __construct(array $array, ?User $user = null)
    {
        $this->raw = $array;
        $this->target = $array["target"];
        $this->action = $array["action"];
        $this->data = $array["data"];
        $this->user = $user;
        $this->time = new DateTime();
        $object_name = "Objects\\" . ucwords($this->target);
        if(class_exists($object_name)){
            $this->object = Entity::arrayToObject(object: $object_name, array: $this->data, id: $array["id"] ?? null);
            $this->object->store();
        }
        try {
            if (!Database::getConnection()->stat()) (new Response(status: false, description: API_MESSAGES::DATABASE_OFFLINE))->encode(print: true);
        } catch (IOException $e){
            (new Response(status: false, description: API_MESSAGES::DATABASE_OFFLINE))->encode(print: true);
        }
    }

    /**
     * @return mixed|String
     */
    public function getTarget(): mixed
    {
        return $this->target;
    }

    /**
     * @param mixed|String $target
     * @return Request
     */
    public function setTarget(mixed $target): Request
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return Entity|null
     */
    public function getObject(): ?Entity
    {
        return $this->object;
    }

    /**
     * @param Entity|null $object
     * @return Request
     */
    public function setObject(?Entity $object): Request
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return String
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param String $action
     * @return Request
     */
    public function setAction(string $action): Request
    {
        $this->action = $action == null ? "query" : strtolower($action);
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Request
     */
    public function setData(array $data): Request
    {
        $this->data = $data;
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
     * @return Request
     */
    public function setUser(?User $user): Request
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @return array
     */
    public function getRaw(): array
    {
        return $this->raw;
    }
}