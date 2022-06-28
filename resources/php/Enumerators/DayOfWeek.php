<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum DayOfWeek: int
{
    case MONDAY = 1;
    case TUESDAY = 2;
    case WEDNESDAY = 3;
    case THURSDAY = 4;
    case FRIDAY = 5;
    case SATURDAY = 6;
    case SUNDAY = 7;
    public function name(): string
    {
        return match ($this) {
            self::MONDAY => 'Segunda',
            self::TUESDAY => 'Terça',
            self::WEDNESDAY => 'Quarta',
            self::THURSDAY => 'Quinta',
            self::FRIDAY => 'Sexta',
            self::SATURDAY => 'Sábado',
            self::SUNDAY => 'Domingo'
        };
    }

    public static function getAllItems(): array{
        return array(
            self::MONDAY,
            self::TUESDAY,
            self::WEDNESDAY,
            self::THURSDAY,
            self::FRIDAY,
            self::SATURDAY,
            self::SUNDAY
        );
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\DayOfWeek"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this->value
        );
    }

    public static function getItem(?int $num) : ?DayOfWeek {
        return match ($num) {
            1 => self::MONDAY,
            2 => self::TUESDAY,
            3 => self::WEDNESDAY,
            4 => self::THURSDAY,
            5 => self::FRIDAY,
            6 => self::SATURDAY,
            7 => self::SUNDAY,
            default => null,
        };
    }

    public static function getItemByName(?String $str) : ?DayOfWeek {
        return match ($str) {
            self::MONDAY->name() => self::MONDAY,
            self::TUESDAY->name() => self::TUESDAY,
            self::WEDNESDAY->name() => self::WEDNESDAY,
            self::THURSDAY->name() => self::THURSDAY,
            self::FRIDAY->name() => self::FRIDAY,
            self::SATURDAY->name() => self::SATURDAY,
            self::SUNDAY->name() => self::SUNDAY,
            default => null,
        };
    }
}
?>