<?php

namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;


class NotNullable extends Exception
{
    #[Pure]
    public function __construct($argument = 'id') {
        parent::__construct("The " . $argument . " field cannot be null.");
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}