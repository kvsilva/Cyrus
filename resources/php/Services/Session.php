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
use Objects\User;

class Session
{
    public static function getSession() : Status{
        return new Status(isError: false, return: isset($_SESSION["user"]) ? array($_SESSION["user"]?->toArray()) : array(), bareReturn: isset($_SESSION["user"]) ? array($_SESSION["user"]) : array());
    }
    public static function updateSession() : Status{
        if(isset($_SESSION["user"])){
            $_SESSION["user"] = new User($_SESSION["user"]->getId(), flags: [User::ROLES]);
            return new Status(isError: false, return: array(), bareReturn: array());
        } else return new Status(isError: true, message: "Nenhuma sessão foi iniciada.", return: array(), bareReturn: array());
    }
}