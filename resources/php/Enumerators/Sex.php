<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum Sex: int
{
    case MALE = 1;
    case FEMALE = 2;
    case OTHER = 3;

    public function name(): string
    {
        return match ($this) {
            self::MALE => 'Male',
            self::FEMALE => 'Female',
            self::OTHER => 'Other'
        };
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\Sex"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this->value
        );
    }

    public static function getItem(?int $num) : ?Sex{
        return match ($num) {
            1 => self::MALE,
            2 => self::FEMALE,
            3 => self::OTHER,
            default => null,
        };
    }
}


?>

