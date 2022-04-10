<?php

namespace Objects;

use Constants\API_MESSAGES;
use DateTime;
use Exception;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Functions\Database;
use ReflectionClass;
use ReflectionException;

class Request
{

    private String $target;
    private ?ReflectionClass $object = null;

    private String $action;

    private array $data;
    private ?User $user;
    private array $flags;

    private DateTime $time;

    private array $raw;

    /**
     * @param array $array
     * @param User|null $user
     */
    public function __construct(array $array, ?User $user = null)
    {
        try {
            if (!Database::getConnection()->stat()) (new Response(status: false, description: API_MESSAGES::DATABASE_OFFLINE))->encode(print: true);
            //Database::getConnection()->query("DELETE FROM USER;");
        } catch (IOException $e){
            (new Response(status: false, description: API_MESSAGES::DATABASE_OFFLINE))->encode(print: true);
            return;
        }
        $this->raw = $array;
        $this->target = $array["target"] ?? null;
        $this->setAction($array["action"]);
        $this->data = $array["data"] ?? array();
        $this->user = $user;
        $this->time = new DateTime();
        $object_name = "Objects\\" . ucwords($this->target);
        $errors = array();
        $success = array();
        if(class_exists($object_name)){
            $this->object = new ReflectionClass($object_name);
            $this->flags = array(Entity::NORMAL);
            if(isset($this->raw["flags"])){
                echo "1";
                foreach($this->raw["flags"] as $flag){
                    foreach($this->object->getConstants() as $constant => $value){
                        if(strtoupper($flag) == $constant) if(!in_array($value, $this->flags)) $this->flags[] = $value;
                    }
                }
            }
            foreach($this->data as $key => $value){
                try {
                    $relations = array();
                    $flags = array(Entity::NORMAL);
                    if(isset($this->data[$key]["relations"])){
                        foreach($this->data[$key]["relations"] as $flag => $relation){
                            if($this->object->hasConstant(strtoupper($flag))){
                                $flags[] = $this->object->getConstant(strtoupper($flag));
                                $relations[$flag] = $relation;
                            }
                        }
                        unset($this->data[$key]["relations"]);
                    }
                    $obj = $this->handle(object_name: $object_name, data: $this->data[$key], relations: $relations, flags: $flags);
                    $success[] = $obj?->toArray();
                } catch (Exception $e){
                    $errors[] = array("error" => $e->getMessage(), "data" => $this->data[$key]);
                }
            }
        }
        if(sizeof($errors) > 0){
            (new Response(status: false, description: API_MESSAGES::GENERIC_ERROR_DATA, data: $success, errors: $errors))->encode(print: true);
        } else {
            (new Response(status: true, data: $success))->encode(print: true);
        }
    }

    /*
     * INSERT: Se tiver relations vai apenas adicionar as relações às já existentes (se tiver)
     * UPDATE: Se tiver relations vai dar o override nas que já existem, ou seja, apagar elas todas.
     * REMOVE: Se tiver relations vai apagar as que venhem no relations
     *
     * */
    /**
     * @throws ReflectionException
     * @throws NotNullable
     */
    private function handle(String $object_name, array $data = array(), array $relations = array(), array $flags = array(Entity::NORMAL)) : ?Entity{
        $id = $data["id"] ?? null;
        $object = null;
        switch ($this->getAction()){
            case "query":
                break;
            case "update":
                if($id == null) throw new NotNullable("id");
                $object = Entity::arrayToObject(object: $object_name, array: $data, id: $id, flags: $flags);
                $object->store();
                break;
            case "insert":
                $flags = array_values(array_unique(array_merge($this->flags, $flags)));
                $object = Entity::arrayToObject(object: $object_name, array: $id != null ? array() : $data, id: $id, flags: $flags);
                $object = Entity::arrayToRelations($object, $relations);
                $object->store();
                break;
            case "remove":
                if($id == null) throw new NotNullable("id");
                $object = Entity::arrayToObject(object: $object_name, id: $id);
                $object = $object->remove();
                break;
            default:
                (new Response(status: false, description: API_MESSAGES::DATABASE_OFFLINE))->encode(print: true);
                break;
        }
        return $object;
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
     * @return ReflectionClass|null
     */
    public function getObject(): ?ReflectionClass
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