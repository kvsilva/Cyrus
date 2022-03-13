<?php
namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;

class ColumnNotFound extends Exception
{
    #[Pure]
    public function __construct($column, $table) {
        parent::__construct("There is no column named " . $column . " in the " . $table . ".");
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}