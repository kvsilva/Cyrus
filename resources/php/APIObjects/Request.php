<?php

namespace APIObjects;

use Constants\API_MESSAGES;
use DateTime;
use Enumerators\Availability;
use Exception;
use Exceptions\InvalidDataType;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Functions\Database;
use Functions\Models;
use Functions\Utils;
use Objects\Entity;
use Objects\EntityArray;
use Objects\User;
use ReflectionClass;
use ReflectionException;

class Request
{

    private ?String $type = null;
    private ?ReflectionClass $object = null;

    private ?String $action = null;
    private ?String $service = null;

    private ?array $data = null;
    private ?User $user = null;
    private ?array $flags = null;

    private ?DateTime $time = null;

    private ?array $raw = null;

    private ?array $dataTypes = null;

    private bool $hasRelations = false;

    private ?bool $minimal = null;
    private ?bool $entities = null;
    private String $orderBy;
    private String $operator;

    /**
     * @param array $array
     * @param User|null $user
     * @throws ReflectionException
     */
    public function __construct(array $array, ?User $user = null)
    {
        session_start();
        try {
            if (!Database::getConnection()->stat()) (new Response(status: false, description: API_MESSAGES::DATABASE_OFFLINE))->encode(print: true);
        } catch (IOException $e){
            (new Response(status: false, description: API_MESSAGES::DATABASE_OFFLINE))->encode(print: true);
            return;
        }
        $this->raw = $array;
        $this->type = $array["type"] ?? null;
        $this->service = $array["service"] ?? null;
        $this->minimal = $array["minimal"] ?? null;
        $this->operator = $array["operator"] ?? "=";
        $this->entities = $array["entities"] ?? null;
        $this->orderBy = $array["orderBy"] ?? "id";
        $this->dataTypes = isset($array["dataTypes"]) && $array["dataTypes"] ? array() : null;
        $this->setAction($array["action"]);
        $this->data = isset($array["data"]) && $array["data"] != null ? $array["data"] : array();
        $this->user = $user;
        $this->time = new DateTime();
        $object_name = "Objects\\" . ucwords($this->type);
        $errors = array();
        $success = array();
        if($this->type !== null && class_exists($object_name)){
            $this->object = new ReflectionClass($object_name);
            $this->flags = array(Entity::NORMAL);
            if(isset($this->raw["flags"])){
                foreach($this->raw["flags"] as $flag){
                    foreach($this->object->getConstants() as $constant => $value){
                        if(strtoupper($flag) == $constant) {
                            if (!in_array($value, $this->flags)) {
                                $this->flags[] = $value;
                            }
                        }
                    }
                }
            }
            foreach($this->data as $key => $value){
                try {
                    $relations = array();
                    //$flags = array(Entity::NORMAL);
                    if(isset($this->data[$key]["relations"])){
                        $this->hasRelations = true;
                        foreach($this->data[$key]["relations"] as $flag => $relation){
                            if($this->object->hasConstant(strtoupper($flag))){
                                $this->flags[] = $this->object->getConstant(strtoupper($flag));

                                $relations[$flag] = $relation;
                            }
                        }
                        unset($this->data[$key]["relations"]);
                    }
                    $objects = $this->handleClass(object_name: $object_name, data: $this->data[$key], relations: $relations, flags: $this->flags);
                    $this->dataTypes = array();
                    $minimal = $this->minimal !== null  ? $this->minimal : ($this->getAction() !== "query");
                    $entities = $this->entities !== null  ? $this->entities : ($this->getAction() !== "query");
                    foreach($objects as $object){
                        $this->dataTypes[] = $object;
                        $success[] = $object->toArray($minimal, $entities);
                    }
                    if($this->dataTypes !== null) $this->dataTypes = self::getDataTypes($this->dataTypes);
                } catch (Exception $e){
                    $errors[] = array("error" => $e->getMessage(), "data" => $this->data[$key]);
                }
            }

            if(sizeof($errors) > 0){
                // Erro: n dá output das relações no retorno de erro.
                (new Response(status: false, description: API_MESSAGES::GENERIC_ERROR_DATA, data: $success, errors: $errors))->encode(print: true);
            } else {
                (new Response(status: true, data: $success, dataTypes: $this->dataTypes))->encode(print: true);
            }
        } else if($this->service !== null) {
            if(isset($this->raw["flags"])) {
                $this->flags = array();
                foreach ($this->raw["flags"] as $flag) {
                    $this->flags[] = $flag;
                }
            }
            $this->handleService(data: $this->data, flags: $this->flags);
        } else {
            (new Response(status: true))->encode(print: true);
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
     * @throws InvalidDataType
     */
    private function handleClass(String $object_name, array $data = array(), array $relations = array(), array $flags = array(Entity::NORMAL)) : ?EntityArray{
        $id = $data["id"] ?? null;
        if($id !== null && !is_numeric($id)){
            throw new InvalidDataType("id", "int");
        } else if (is_numeric($id)){
            $id = intval($id);
        }
        $obj = (new ReflectionClass($object_name))->newInstanceArgs();
        $objects = new EntityArray(entity: $object_name);
        switch ($this->getAction()){
            case "query":

                $objects_find = Entity::__find(fields: $data, table: $obj->getTable(), class: $object_name, operator: $this->operator, flags: $flags);
                foreach($objects_find as $object1){
                    $objects[] = $object1;
                }
                break;
            case "update":
                if($id == null) throw new NotNullable("id");
                $objects_find = Entity::__find(fields: array("id" => $id, "available" => Availability::BOTH), table: $obj->getTable(), class: $object_name, flags: $flags);
                if($objects_find->size() > 0) {
                    $objects_find[0]->arrayObject($data);
                    $objects_find[0]->arrayRelations($relations);
                    //$object = Entity::arrayToObject(object: $objects_find[0], array: $data, id: $id, flags: $flags);
                    $objects_find[0]->store();
                    $object = Entity::__find(fields: array("id" => $objects_find[0]->getId(),  "available" => Availability::BOTH), table: $obj->getTable(), class: $object_name, flags: $flags)[0];
                    $objects[] = $object;
                }
                break;
            case "insert":
                $object = Entity::arrayToObject(object: $object_name, array: $id != null ? array() : $data, id: $id, flags: $flags);
                $object = Entity::arrayToRelations($object, $relations);
                $object->store();
                $object = Entity::__find(fields: array("id" => $object->getId()), table: $obj->getTable(), class: $object_name, flags: $flags)[0];
                $objects[] = $object;
                break;
            case "remove":
                if($id == null) throw new NotNullable("id");
                $objects_find = Entity::__find(fields: array("id" => $id, "available" => Availability::BOTH), table: $obj->getTable(), class: $object_name, flags: $flags);
                if($objects_find->size() > 0) {
                    $object = $objects_find[0];
                    $object = Entity::arrayToRelations($object, $relations, remove: true);
                    if (!$this->hasRelations) {
                        $object = $object->remove();
                    } else {
                        $object->store();
                    }
                    $objects[] = $object;
                }
                break;
            default:
                (new Response(status: false, description: API_MESSAGES::ACTION_UNKNOWN))->encode(print: true);
                exit;
        }
        return $objects;
    }

    /**
     * @throws ReflectionException
     */
    private function handleService(array $data = array(), ?array $flags = null){
        $serviceName = "Services\\" . $this->service;
        if($flags === null) $flags = array(Entity::NORMAL);
        if(!class_exists($serviceName)){
            (new Response(status: false, description: API_MESSAGES::SERVICE_UNKNOWN))->encode(print: true);
            return;
        }
        $service = new ReflectionClass($serviceName);
        if(!$service->hasMethod($this->getAction())){
            (new Response(status: false, description: API_MESSAGES::ACTION_UNKNOWN))->encode(print: true);
            return;
        }

        $method = $service->getMethod($this->getAction());
        $parameters = array();

        if($data === null || count($data) == 0 || $method->getNumberOfRequiredParameters() > count($data[0])){

            (new Response(status: false, description: API_MESSAGES::MISSING_FIELDS_GENERIC))->encode(print: true);
            return;
        }
        foreach ($method->getParameters() as $param){
            if(isset($data[0][$param->getName()])){
                $parameters[$param->getName()] = $data[0][$param->getName()];
            } else if ($param->getName() == "flags"){
                $parameters[$param->getName()] = $flags;
            }
        }

        $ret = $service->getMethod($this->getAction())->invokeArgs(object: null, args: $parameters);
        $this->dataTypes = ($this->dataTypes !== null) ? self::getDataTypes($ret->getBareReturn()) : array();
        (new Response(status: !$ret->isError(), description: $ret->getMessage(), data: $ret->getReturn(), dataTypes: $this->dataTypes))->encode(print: true);
    }


    public static function getDataTypes(mixed $items){
        if(!is_array($items) && get_class($items) !== "Objects\EntityArray" && get_parent_class($items) !== "Objects\EntityArray") {
            return array();
        }
        $ret = array();
        foreach($items as $key => $element){
            if(is_object($element)) {
                if (str_ends_with(get_class($element), "sArray") || str_ends_with(get_class($element), "EntityArray")) {
                    $type = self::getDataTypes($element);
                    $name = str_replace("Objects\\" , "", get_class($element));
                    $name = str_replace("EntityArray" , "", $name);
                    $name = str_replace("sArray" , "", $name);
                } else {
                    $type = str_replace("Objects\\" , "", get_class($element));
                    foreach(Models::REPLACE_TO as $itemKey => $itemValue){
                        $type = str_replace($itemKey, $itemValue["value"], $type);
                    }
                }
                $ret[$key] = $type;
            } else if (is_array($element)) {
                $type = self::getDataTypes($element);
                $ret[$key] = $type;
                //$ret[] = str_replace("Objects\\" , "", get_class($element));
            } else if (is_bool($element)){
                $ret[$key] = "bool";
            } else if (is_string($element)){
                $ret[] = "string";
            } else if (is_double($element) || is_int($element) || is_numeric($element) || is_float($element)){
                $ret[$key] = "number";
            } else {
                $ret[$key] = "Unknown";
            }
        }
        return $ret;
    }

    /**
     * @return mixed|String
     */
    public function getType(): mixed
    {
        return $this->type;
    }

    /**
     * @param mixed|String $type
     * @return Request
     */
    public function setType(mixed $type): Request
    {
        $this->type = $type;
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

    /**
     * @return mixed|String|null
     */
    public function getService(): mixed
    {
        return $this->service;
    }

    /**
     * @param mixed|String|null $service
     * @return Request
     */
    public function setService(mixed $service): Request
    {
        $this->service = $service;
        return $this;
    }


}