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
            self::AVAILABLE => 'Available',
            self::NOT_AVAILABLE => 'Not Available',
        };
    }

    public static function getAllItems(): array{
        return array(
            self::NOT_AVAILABLE,
            self::AVAILABLE
        );
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\Availability"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this->value
        );
    }

    public static function getItem(?int $num) : ?Availability {
        return match ($num) {
            0 => self::NOT_AVAILABLE,
            1 => self::AVAILABLE,
            default => null,
        };
    }
}
?>