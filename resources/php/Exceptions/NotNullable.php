<?php

use JetBrains\PhpStorm\Pure;

class NotNullable extends Exception
{
    #[Pure]
    public function __construct($argument = 'id') {
        parent::__construct("The " . $argument . " field cannot be null.");
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}