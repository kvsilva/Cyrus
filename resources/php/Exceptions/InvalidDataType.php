<?php

use JetBrains\PhpStorm\Pure;

class InvalidDataType extends Exception
{
    #[Pure]
    public function __construct($argument, $data_type) {
        parent::__construct("Data type for field " . $argument . " is invalid, expected data type: " . $data_type . ".");
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}