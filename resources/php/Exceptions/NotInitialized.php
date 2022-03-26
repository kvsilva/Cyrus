<?php
namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;

class NotInitialized extends Exception
{
    #[Pure]
    public function __construct($variable, $relation = true) {
        $message = !$relation ? "The variable $variable has not been initialized." : "The $variable relation has not been initialized.";
        parent::__construct($message);
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}