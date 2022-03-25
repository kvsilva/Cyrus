<?php

namespace Objects;

use ReflectionClass;


class TestA {
    private ?int $id = null;
}


class TestB extends TestA
{
    private ?User $user = null;

    /**
     * @throws \ReflectionException
     */
    function __construct(){
        foreach((new ReflectionClass($this))->getProperties() as $key => $value){
            $this->{$key} = (new ReflectionClass(get_class($this->{$key})))->newInstanceArgs(array($value));
        }
    }
}