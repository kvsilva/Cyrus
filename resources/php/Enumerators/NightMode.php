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
            NightMode::ENABLE => 'Enable',
            NightMode::DISABLE => 'Disable',
        };
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\NightMode"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this
        );
    }

    public static function getNightMode(?int $num) : ?NightMode{
        return match ($num) {
            0 => NightMode::DISABLE,
            1 => NightMode::ENABLE,
            default => null,
        };
    }
}


?>