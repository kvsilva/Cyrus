<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum Availability: int
{
    case NOT_AVAILABLE = 0;
    case AVAILABLE = 1;

    public function name(): string
    {
        return match ($this) {
            Availability::AVAILABLE => 'Available',
            Availability::NOT_AVAILABLE => 'Not Available',
        };
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\Availability"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this
        );
    }

    public static function getAvailability(?int $num) : ?Availability {
        return match ($num) {
            0 => Availability::NOT_AVAILABLE,
            1 => Availability::AVAILABLE,
            default => null,
        };
    }
}
?>