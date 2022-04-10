<?php
namespace Enumerators;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

enum Verification: int
{
    case NOT_VERIFIED = 0;
    case VERIFIED = 1;

    public function name(): string
    {
        return match ($this) {
            self::VERIFIED => 'Verified',
            self::NOT_VERIFIED => 'Not Verified',
        };
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\Verification"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this->value
        );
    }

    public static function getItem(?int $num) : ?Verification{
        return match ($num) {
            0 => self::NOT_VERIFIED,
            1 => self::VERIFIED,
            default => null,
        };
    }
}


?>