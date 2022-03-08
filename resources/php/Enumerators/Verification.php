<?php
enum Verification: int
{
    case NOT_VERIFIED = 0;
    case VERIFIED = 1;

    public function name(): string
    {
        return match ($this) {
            Verification::VERIFIED => 'Verified',
            static::NOT_VERIFIED => 'Not Verified',
        };
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