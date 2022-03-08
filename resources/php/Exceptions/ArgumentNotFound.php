<?php

use JetBrains\PhpStorm\Pure;

class ArgumentNotFound extends Exception
{
    #[Pure]
    public function __construct($argument = 'id') {
        parent::__construct("The " . $argument . " field could not be found in the database.");
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}