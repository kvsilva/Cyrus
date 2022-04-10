<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum Maturity: int
{
    case NORMAL = 0;
    case MATURE = 1;

    public function name(): string
    {
        return match ($this) {
            self::NORMAL => 'Normal',
            self::MATURE => 'Mature',
        };
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\Maturity"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this->value
        );
    }

    public static function getItem(?int $num) : ?Maturity {
        return match ($num) {
            0 => self::NORMAL,
            1 => self::MATURE,
            default => null,
        };
    }
}
?>