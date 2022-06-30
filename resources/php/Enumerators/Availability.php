<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum Availability: int
{
    case NOT_AVAILABLE = 0;
    case AVAILABLE = 1;
    case BOTH = 2;

    public function name(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Disponível',
            self::NOT_AVAILABLE => 'Indisponível',
            self::BOTH => 'Ambos',
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
            2 => self::BOTH,
            default => null,
        };
    }
}
?>