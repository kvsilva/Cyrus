<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum Month: int
{
    case JANUARY = 1;
    case FEBRUARY = 2;
    case MARCH = 3;
    case APRIL = 4;
    case MAY = 5;
    case JUNE = 6;
    case JULY = 7;
    case AUGUST = 8;
    case SEPTEMBER = 9;
    case OCTOBER = 10;
    case NOVEMBER = 11;
    case DECEMBER = 12;
    public function name(): string
    {
        return match ($this) {
            self::JANUARY => 'Janeiro',
            self::FEBRUARY => 'Fevereiro',
            self::MARCH => 'Março',
            self::APRIL => 'Abril',
            self::MAY => 'Maio',
            self::JUNE => 'Junho',
            self::JULY => 'Julho',
            self::AUGUST => 'Agosto',
            self::SEPTEMBER => 'Setembro',
            self::OCTOBER => 'Outubro',
            self::NOVEMBER => 'Novembro',
            self::DECEMBER => 'Dezembro'
        };
    }

    public static function getAllItems(): array{
        return array(
            self::JANUARY,
            self::FEBRUARY,
            self::MARCH,
            self::APRIL,
            self::MAY,
            self::JUNE,
            self::JULY,
            self::AUGUST,
            self::SEPTEMBER,
            self::OCTOBER,
            self::NOVEMBER,
            self::DECEMBER,
        );
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\Month"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this->value
        );
    }

    public static function getItem(?int $num) : ?Month {
        return match ($num) {
            1 => self::JANUARY,
            2 => self::FEBRUARY,
            3 => self::MARCH,
            4 => self::APRIL,
            5 => self::MAY,
            6 => self::JUNE,
            7 => self::JULY,
            8 => self::AUGUST,
            9 => self::SEPTEMBER,
            10 => self::OCTOBER,
            11 => self::NOVEMBER,
            12 => self::DECEMBER,
            default => null,
        };
    }
}
?>