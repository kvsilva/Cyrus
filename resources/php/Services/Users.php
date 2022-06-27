<?php

namespace Services;

use APIObjects\Status;
use DateTime;
use Enumerators\DayOfWeek;
use Exception;
use Exceptions\RecordNotFound;
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
}