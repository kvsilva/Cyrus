<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum Removal: int
{
    case DELETE = 0;
    case AVAILABILITY = 1;

    public function name(): string
    {
        return match ($this) {
            self::DELETE => 'Delete',
            self::AVAILABILITY => 'Availability',
        };
    }

    public static function getAllItems(): array{
        return array(
            self::DELETE,
            self::AVAILABILITY
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

    public static function getItem(?int $num) : ?Removal {
        return match ($num) {
            0 => self::DELETE,
            1 => self::AVAILABILITY,
            default => null
        };
    }
}
?>