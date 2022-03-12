<?php
namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;

class IOException extends Exception
{
    #[Pure]
    public function __construct($address = null, $port = null, $path = null, $message = null) {
        parent::__construct($message != null ? $message : ($path == null ? "Couldn't reach the server ($address:$port)." : "Couldn't open the file ($path)."));
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}