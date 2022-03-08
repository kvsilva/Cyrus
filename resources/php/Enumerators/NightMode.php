<?php

enum NightMode: int
{
    case DISABLE = 0;
    case ENABLE = 1;

    public function name(): string
    {
        return match ($this) {
            NightMode::ENABLE => 'Enable',
            NightMode::DISABLE => 'Disable',
        };
    }

    public static function getNightMode(int $num) : ?NightMode{
        return match ($num) {
            0 => NightMode::DISABLE,
            1 => NightMode::ENABLE,
            default => null,
        };
    }
}


?>