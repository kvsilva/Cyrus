<?php

namespace Objects;

use ReflectionException;

class Example_ObjectArray extends EntityArray
{
    /**
     * @param $array
     * @param $flags
     * @param $iteratorClass
     * @throws ReflectionException
     */
    public function __construct($array = [], $flags = 0, $iteratorClass = "ArrayIterator")
    {
        parent::__construct(entity: "Objects\Example_object", array: $array, flags: $flags, iteratorClass: $iteratorClass);
    }
}