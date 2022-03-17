<?php

namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;


class DependentArgument extends Exception
{
    #[Pure]
    public function __construct($dependentArgument, $dependencyArgument) {
        parent::__construct("The $dependentArgument argument is dependent on $dependencyArgument argument. To use one of the two, the other must be used as well.");
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}