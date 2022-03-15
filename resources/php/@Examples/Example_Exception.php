<?php
namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;

class Example_Exception extends Exception
{
    #[Pure]
    public function __construct($argument1) {
        parent::__construct("Error message: " . $argument1);
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}