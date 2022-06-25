<?php

namespace Services;

use APIObjects\Status;
use JetBrains\PhpStorm\Pure;
use Functions\Routing;

class Utilities
{
    #[Pure] public static function getRouting() : Status{
        return new Status(isError: false, return: Routing::getRoutingInfo(), bareReturn: Routing::getRoutingInfo());

    }

}