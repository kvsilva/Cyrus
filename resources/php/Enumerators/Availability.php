<?php
enum Availability: int
{
    case NOT_AVAILABLE = 0;
    case AVAILABLE = 1;

    public function name(): string
    {
        return match ($this) {
            Availability::AVAILABLE => 'Available',
            Availability::NOT_AVAILABLE => 'Not Available',
        };
    }

    public static function getAvailability(int $num) : ?Availability{
        return match ($num) {
            0 => Availability::NOT_AVAILABLE,
            1 => Availability::AVAILABLE,
            default => null,
        };
    }
}
?>