<?php
namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;

class InvalidDataType extends Exception
{
    #[Pure]
    public function __construct($argument, $data_type) {
        parent::__construct("Data type for field " . $argument . " is invalid, expected data type: " . $data_type . ".");
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}