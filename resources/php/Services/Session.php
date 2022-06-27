<?php

namespace Services;

use APIObjects\Status;
use DateTime;
use Enumerators\DayOfWeek;
use Exception;
use Functions\Database;
use Functions\Utils;
use Objects\Anime;
use Objects\AnimesArray;

class Session
{
    public static function getSession() : Status{
        return new Status(isError: false, return: isset($_SESSION["user"]) ? array($_SESSION["user"]?->toArray()) : array(), bareReturn: isset($_SESSION["user"]) ? array($_SESSION["user"]) : array());
    }
}