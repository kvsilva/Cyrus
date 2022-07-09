<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Objects\Anime;
use Objects\Ticket;

enum TicketStatus: int
{
    case OPEN = 1;
    case CLOSED = 2;
    case AWAITING_YOUR_RESPONSE = 3;
    public function name(): string
    {
        return match ($this) {
            self::OPEN => 'Aberto',
            self::CLOSED => 'Fechado',
            self::AWAITING_YOUR_RESPONSE => 'Aguardando a tua resposta',
        };
    }

    public static function getAllItems(): array{
        return array(
            self::OPEN,
            self::CLOSED,
            self::AWAITING_YOUR_RESPONSE,
        );
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

    public static function getItem(?int $num) : ?TicketStatus {
        return match ($num) {
            1 => self::OPEN,
            2 => self::CLOSED,
            3 => self::AWAITING_YOUR_RESPONSE,
            default => null,
        };
    }

    #[Pure]
    public static function getItemByName(?String $str) : ?TicketStatus {
        return match ($str) {
            self::OPEN->name() => self::OPEN,
            self::CLOSED->name() => self::CLOSED,
            self::AWAITING_YOUR_RESPONSE->name() => self::AWAITING_YOUR_RESPONSE,
            default => null,
        };
    }
}
?>