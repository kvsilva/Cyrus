<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum NightMode: int
{
    case DISABLE = 0;
    case ENABLE = 1;

    public function name(): string
    {
        return match ($this) {
            self::ENABLE => 'Enable',
            self::DISABLE => 'Disable',
        };
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\NightMode"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this->value
        );
    }

    public static function getItem(?int $num) : ?NightMode{
        return match ($num) {
            0 => self::DISABLE,
            1 => self::ENABLE,
            default => null,
        };
    }
}


?>