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
            Verification::VERIFIED => 'Verified',
            Verification::NOT_VERIFIED => 'Not Verified',
        };
    }

    #[Pure]
    #[ArrayShape(["name" => "string", "value" => "\Verification"])]
    public function toArray() : array
    {
        return array(
            "name" => $this::name(),
            "value" => $this
        );
    }

    public static function getVerification(int $num) : ?Verification{
        return match ($num) {
            0 => Verification::NOT_VERIFIED,
            1 => Verification::VERIFIED,
            default => null,
        };
    }
}


?>