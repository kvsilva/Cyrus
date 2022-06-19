<?php

namespace Objects;

use ReflectionException;

class VideosArray extends EntityArray
{
    /**
     * @param $array
     * @param $flags
     * @param $iteratorClass
     * @throws ReflectionException
     */
    public function __construct($array = [], $flags = 0, $iteratorClass = "ArrayIterator")
    {
        parent::__construct(entity: "Objects\Video", array: $array, flags: $flags, iteratorClass: $iteratorClass);
    }
}