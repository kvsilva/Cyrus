<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Objects\Anime;

enum AnimeStatus: int
{
    case FAVOURITE = 1;
    case LIKE = 2;
    case DONT_LIKE = 3;
    case WATCH_LATER = 4;
    public function name(): string
    {
        return match ($this) {
            self::FAVOURITE => 'Favorito',
            self::LIKE => 'Gosto',
            self::DONT_LIKE => 'Não Gosto',
            self::WATCH_LATER => 'Ver mais tarde',
        };
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\AnimeStatus"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this->value
        );
    }

    public static function getItem(?int $num) : ?AnimeStatus {
        return match ($num) {
            1 => self::FAVOURITE,
            2 => self::LIKE,
            3 => self::DONT_LIKE,
            4 => self::WATCH_LATER,
            default => null,
        };
    }

    #[Pure]
    public static function getItemByName(?String $str) : ?AnimeStatus {
        return match ($str) {
            self::FAVOURITE->name() => self::FAVOURITE,
            self::LIKE->name() => self::LIKE,
            self::DONT_LIKE->name() => self::DONT_LIKE,
            self::WATCH_LATER->name() => self::WATCH_LATER,
            default => null,
        };
    }
}
?>