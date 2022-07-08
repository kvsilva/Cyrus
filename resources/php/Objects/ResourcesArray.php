<?php

namespace Objects;

use ReflectionException;

class ResourcesArray extends EntityArray
{
    /**
     * @param $array
     * @param $flags
     * @param $iteratorClass
     * @throws ReflectionException
     */
    public function __construct($array = [], $flags = 0, $iteratorClass = "ArrayIterator")
    {
        parent::__construct(entity: "Objects\Resource", array: $array, flags: $flags, iteratorClass: $iteratorClass);
    }
}