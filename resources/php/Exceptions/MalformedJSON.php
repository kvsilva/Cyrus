<?php

namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;

class MalformedJSON extends Exception
{
    #[Pure]
    public function __construct() {
        parent::__construct("The received value is not a JSON structure.");
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}