<?php

namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;


class UniqueKey extends Exception
{
    #[Pure]
    public function __construct($argument) {
        parent::__construct("The " . $argument . " field must be unique across the entire database..");
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}