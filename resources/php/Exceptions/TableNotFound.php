<?php
namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;

class TableNotFound extends Exception
{
    #[Pure]
    public function __construct($table) {
        parent::__construct("No table was found that matches the name " . $table . ".");
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}