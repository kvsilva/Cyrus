<?php

namespace Objects;

use ArrayObject;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReturnTypeWillChange;

class EntityArray extends ArrayObject
{
    private ?Entity $object = null;
    private ?String $entity;

    /**
     * @throws ReflectionException
     */
    public function __construct(?String $entity = "Objects\Entity", $array = [], $flags = 0, $iteratorClass = "ArrayIterator")
    {
        parent::__construct($array, $flags, $iteratorClass);
        $this->entity = $entity === null ? "Objects\Entity" : $entity;
        $obj = null;
        if($entity !== null) $obj = (new ReflectionClass($this->entity))->newInstanceWithoutConstructor();
        if($this->object === null) $this->object = $obj;
    }

    #[ReturnTypeWillChange]
    public function offsetSet($key, $value) {
        if (is_subclass_of(get_class($value), "Objects\Entity") || ($this->object !== null && $value instanceof $this->object)) {
            parent::offsetSet($key, $value);
            return;
        }
        throw new InvalidArgumentException('The data type must be ' . $this->entity . '.');
    }

    public function isArrayOf() : String{
        return $this->entity;
    }

    public function size() : int{
        return sizeof($this);
    }

    public function addAll(EntityArray $entities) : EntityArray{
        foreach($entities as $entity){
            $this[] = $entity;
        }
        return $this;
    }

    public function sort($callback){
        $this->uasort($callback);
    }

    public function toArray(){
        $array = array();

        foreach($this as $item){
            $array[] = $item?->toArray();
        }
        return $array;

    }
}