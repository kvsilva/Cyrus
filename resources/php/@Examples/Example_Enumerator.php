<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum Example_Enumerator: int
{
    case VALUE_1 = 0;
    case VALUE_2 = 1;

    public function name(): string
    {
        return match ($this) {
            self::VALUE_1 => 'Value_1',
            self::VALUE_2 => 'Value_2',
        };
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\Example_Enumerator"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this
        );
    }

    public static function getExampleEnumerator(?int $num) : ?Example_Enumerator {
        return match ($num) {
            0 => self::VALUE_1,
            1 => self::VALUE_2,
            default => null,
        };
    }
}
?>