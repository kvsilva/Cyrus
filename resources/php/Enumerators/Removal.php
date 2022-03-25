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

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\Availability"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this
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