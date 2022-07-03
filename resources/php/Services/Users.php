<?php

namespace Services;

use APIObjects\Status;
use DateTime;
use Enumerators\DayOfWeek;
use Exception;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Exceptions\RecordNotFound;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Functions\Database;
use Functions\Utils;
use Objects\Anime;
use Objects\AnimesArray;
use Objects\User;
use Objects\Video;
use ReflectionException;
class Users
{
    /**
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    private const keepWatchVideos = 12;

    public static function getKeepWatchingVideos() : Status{
        if(isset($_SESSION["user"])){
            $id = $_SESSION["user"]->getId();
            $user = new User($id, flags: [User::VIDEOHISTORY]);
            $videos = $user->getVideoHistory();
            $ret = array();
            $original = array();
            for($i = 0; $i < self::keepWatchVideos; $i++){
                if(count($videos) > $i){
                    $original[] = array(
                        "video" => $videos[$i]["video"],
                        "remaining" => $videos[$i]["video"]?->getDuration() - $videos[$i]["watched_until"]
                    );
                    $ret[] = array(
                        "video" => $videos[$i]["video"]?->toArray(),
                        "remaining" => $videos[$i]["video"]?->getDuration() - $videos[$i]["watched_until"]
                    );
                } else break;
            }
            return new Status(isError: false, return: array($ret), bareReturn: array($original));
        } else {
            return new Status(isError: false, return: array(), bareReturn: array());
        }
    }

    /**
     * @param String $currentPassword
     * @param String $newPassword
     * @return Status
     * @throws RecordNotFound
     * @throws ReflectionException
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public static function changePassword(String $currentPassword, String $newPassword) : Status{
        if(isset($_SESSION["user"])){
            $_SESSION["user"] = new User($_SESSION["user"]->getId());
            if($_SESSION["user"]->isPassword($currentPassword)){
                if(strlen($newPassword) >= 8) {
                    $_SESSION["user"]->setPassword($newPassword);
                    $_SESSION["user"]->store();
                    Authentication::logout();
                    return new Status(isError: false, return: array(), bareReturn: array());
                } else return new Status(isError: true, message: "Palavra-Passe inferior ao tamanho permitido (8 caracteres).", return: array(), bareReturn: array());
            } else return new Status(isError: true, message: "Palavra-Passe incorreta.", return: array(), bareReturn: array());
        } else return new Status(isError: true, message: "Nenhuma sessão foi iniciada.", return: array(), bareReturn: array());
    }


    /**
     * @param String $currentPassword
     * @param String $newEmail
     * @return Status
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws RecordNotFound
     * @throws ReflectionException
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public static function changeEmail(String $currentPassword, String $newEmail) : Status{
        if(isset($_SESSION["user"])){
            $_SESSION["user"] = new User($_SESSION["user"]->getId());
            if($_SESSION["user"]->isPassword($currentPassword)){
                if(filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION["user"]->setEmail($newEmail);
                    $_SESSION["user"]->store();
                    return new Status(isError: false, return: array($_SESSION["user"]->toArray(false, false)), bareReturn: array($_SESSION["user"]));
                } else return new Status(isError: true, message: "Email inválido.", return: array(), bareReturn: array());
            } else return new Status(isError: true, message: "Palavra-Passe incorreta.", return: array(), bareReturn: array());
        } else return new Status(isError: true, message: "Nenhuma sessão foi iniciada.", return: array(), bareReturn: array());
    }
}