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
use Objects\Entity;
use Objects\Video;
use ReflectionException;

class Animes
{

    private const returnItems = 12;

    public static function getAnimeList() : Status{
        try {
            $letters_list = array(
                "A",
                "B",
                "C",
                "D",
                "E",
                "F",
                "G",
                "H",
                "I",
                "J",
                "K",
                "L",
                "M",
                "N",
                "O",
                "P",
                "Q",
                "R",
                "S",
                "T",
                "U",
                "V",
                "W",
                "X",
                "Y",
                "Z",
            );

            $bareResult = array();

            $result = array();

            $bareResult["#"] = Anime::find(orderBy: "title", sql: " title REGEXP '^(?!^[a-zA-Z].*$).'");
            $result["#"] = $bareResult["#"]->toArray();
            foreach($letters_list as $letter){
                $bareResult[$letter] = Anime::find(title: $letter . "%", operator: "like", orderBy: "title");
                $result[$letter] = $bareResult[$letter]->toArray();
            }

        } catch (Exception $e) {
            return new Status(isError: true, message: $e->getMessage());
        }
        return new Status(isError: false, return: $result, bareReturn: $bareResult);
    }

    public static function getCalendar(string|null $day = null) : Status
    {
        try {
            $today = new DateTime();
            $days = array(
                DayOfWeek::MONDAY->value => (new DateTime())->modify(DayOfWeek::MONDAY->value - date('w', $today->getTimestamp()) . " days"),
                DayOfWeek::TUESDAY->value => (new DateTime())->modify(DayOfWeek::TUESDAY->value - date('w', $today->getTimestamp()) . " days"),
                DayOfWeek::WEDNESDAY->value => (new DateTime())->modify(DayOfWeek::WEDNESDAY->value - date('w', $today->getTimestamp()) . " days"),
                DayOfWeek::THURSDAY->value => (new DateTime())->modify(DayOfWeek::THURSDAY->value - date('w', $today->getTimestamp()) . " days"),
                DayOfWeek::FRIDAY->value => (new DateTime())->modify(DayOfWeek::FRIDAY->value - date('w', $today->getTimestamp()) . " days"),
                DayOfWeek::SATURDAY->value => (new DateTime())->modify(DayOfWeek::SATURDAY->value - date('w', $today->getTimestamp()) . " days"),
                DayOfWeek::SUNDAY->value => (new DateTime())->modify(DayOfWeek::SUNDAY->value - date('w', $today->getTimestamp()) . " days"),
            );
            $firstDayOfWeek = (new DateTime())->modify(DayOfWeek::MONDAY->value - date('w', $today->getTimestamp()) . " days");
            $calendar = array();
            $bareCalendar = array();
            if($day === null) {
                $MONDAY = Anime::find(orderBy: "launch_time", sql: "launch_day = " . DayOfWeek::MONDAY->value . " AND (end_date IS NULL OR (DATEDIFF(end_date, '". $firstDayOfWeek->format("Y-m-d")."')) >= 0)");
                $TUESDAY = Anime::find(orderBy: "launch_time", sql: "launch_day = " . DayOfWeek::TUESDAY->value . " AND (end_date IS NULL OR (DATEDIFF(end_date, '". $firstDayOfWeek->format("Y-m-d")."')) >= 0)");
                $WEDNESDAY = Anime::find(orderBy: "launch_time", sql: "launch_day = " . DayOfWeek::WEDNESDAY->value . " AND (end_date IS NULL OR (DATEDIFF(end_date, '". $firstDayOfWeek->format("Y-m-d")."')) >= 0)");
                $THURSDAY = Anime::find(orderBy: "launch_time", sql: "launch_day = " . DayOfWeek::THURSDAY->value . " AND (end_date IS NULL OR (DATEDIFF(end_date, '". $firstDayOfWeek->format("Y-m-d")."')) >= 0)");
                $FRIDAY = Anime::find(orderBy: "launch_time", sql: "launch_day = " . DayOfWeek::FRIDAY->value . " AND (end_date IS NULL OR (DATEDIFF(end_date, '". $firstDayOfWeek->format("Y-m-d")."')) >= 0)");
                $SATURDAY = Anime::find(orderBy: "launch_time", sql: "launch_day = " . DayOfWeek::SATURDAY->value . " AND (end_date IS NULL OR (DATEDIFF(end_date, '". $firstDayOfWeek->format("Y-m-d")."')) >= 0)");
                $SUNDAY = Anime::find(orderBy: "launch_time", sql: "launch_day = " . DayOfWeek::SUNDAY->value . " AND (end_date IS NULL OR (DATEDIFF(end_date, '". $firstDayOfWeek->format("Y-m-d")."')) >= 0)");


                $calendar[DayOfWeek::MONDAY->name()] = array("day" => $days[DayOfWeek::MONDAY->value]->format(Database::DateFormatSimplified), "animes" => $MONDAY->toArray());
                $calendar[DayOfWeek::TUESDAY->name()] = array("day" => $days[DayOfWeek::TUESDAY->value]->format(Database::DateFormatSimplified), "animes" => $TUESDAY->toArray());
                $calendar[DayOfWeek::WEDNESDAY->name()] = array("day" => $days[DayOfWeek::WEDNESDAY->value]->format(Database::DateFormatSimplified), "animes" => $WEDNESDAY->toArray());
                $calendar[DayOfWeek::THURSDAY->name()] = array("day" => $days[DayOfWeek::THURSDAY->value]->format(Database::DateFormatSimplified), "animes" => $THURSDAY->toArray());
                $calendar[DayOfWeek::FRIDAY->name()] = array("day" => $days[DayOfWeek::FRIDAY->value]->format(Database::DateFormatSimplified), "animes" => $FRIDAY->toArray());
                $calendar[DayOfWeek::SATURDAY->name()] = array("day" => $days[DayOfWeek::SATURDAY->value]->format(Database::DateFormatSimplified), "animes" => $SATURDAY->toArray());
                $calendar[DayOfWeek::SUNDAY->name()] = array("day" => $days[DayOfWeek::SUNDAY->value]->format(Database::DateFormatSimplified), "animes" => $SUNDAY->toArray());

                $bareCalendar[DayOfWeek::MONDAY->name()] = array("day" => $days[DayOfWeek::MONDAY->value], "animes" => $MONDAY);
                $bareCalendar[DayOfWeek::TUESDAY->name()] = array("day" => $days[DayOfWeek::TUESDAY->value], "animes" => $TUESDAY);
                $bareCalendar[DayOfWeek::WEDNESDAY->name()] = array("day" => $days[DayOfWeek::WEDNESDAY->value], "animes" => $WEDNESDAY);
                $bareCalendar[DayOfWeek::THURSDAY->name()] = array("day" => $days[DayOfWeek::THURSDAY->value], "animes" => $THURSDAY);
                $bareCalendar[DayOfWeek::FRIDAY->name()] = array("day" => $days[DayOfWeek::FRIDAY->value], "animes" => $FRIDAY);
                $bareCalendar[DayOfWeek::SATURDAY->name()] = array("day" => $days[DayOfWeek::SATURDAY->value], "animes" => $SATURDAY);
                $bareCalendar[DayOfWeek::SUNDAY->name()] = array("day" => $days[DayOfWeek::SUNDAY->value], "animes" => $SUNDAY);
            } else {
                if($day === "today") $day = date('w', $today->getTimestamp());
                if($day == 0) $day = DayOfWeek::SUNDAY->value;
                $dayItem = Anime::find(orderBy: "launch_time", sql: "launch_day = " . DayOfWeek::getItem($day)?->value . " AND (end_date IS NULL OR (DATEDIFF(end_date, '". $firstDayOfWeek->format("Y-m-d")."')) >= 0)");
                //$dayItem = Anime::find(launch_day: DayOfWeek::getItem($day), orderBy: "launch_time");

                $calendar[DayOfWeek::getItem($day)->name()] = array("day" => $days[DayOfWeek::getItem($day)->value]->format(Database::DateFormatSimplified), "animes" => $dayItem->toArray());
                $bareCalendar[DayOfWeek::getItem($day)->name()] = array("day" => $days[DayOfWeek::getItem($day)->value], "animes" => $dayItem);
            }
        } catch (Exception $e) {
            return new Status(isError: true, message: $e->getMessage());
        }
        return new Status(isError: false, return: $calendar, bareReturn: $bareCalendar);
    }

    /**
     * @throws ReflectionException
     */
    public static function getLastReleasedVideos() : Status{

        $videos = Video::find(limit: self::returnItems, order: "release_date");

        return new Status(isError: false, return: array($videos->toArray()), bareReturn: array($videos));
    }

    public static function getRandomizedList() : Status{
        $animes = Anime::find(limit: self::returnItems, orderBy: "RAND()");
        return new Status(isError: false, return: array($animes->toArray()), bareReturn: array($animes));
    }

    public static function getNumberOfNextEpisode(int $anime) : Status{

        $anime = new Anime(id: $anime, flags: [Entity::ALL]);
        $value = null;
        $today = new DateTime();
        $firstDayOfWeek = (new DateTime())->modify(DayOfWeek::MONDAY->value - date('w', $today->getTimestamp()) . " days");
        if($anime->getSeasons()->size() > 0){
            foreach($anime->getSeasons() as $season){
                $highestNum = 0;
                foreach($season->getVideos() as $video){
                    if($highestNum < $video?->getNumeration()) $highestNum = $video?->getNumeration();
                }
                if($season->getEndDate() === null || ($season->getEndDate()->getTimestamp() > $firstDayOfWeek->getTimestamp())){
                    $value = $highestNum + 1;
                } else if($season->getEndDate() !== null && $season->getEndDate()->getTimestamp() < $firstDayOfWeek->getTimestamp()){
                    $value = $highestNum;
                }
            }
        } else {
            $highestNum = 0;
            foreach($anime->getVideos() as $video){
                if($highestNum < $video?->getNumeration()) $highestNum = $video?->getNumeration();
            }
            if($anime->getEndDate() === null || ($anime->getEndDate()->getTimestamp() > $firstDayOfWeek->getTimestamp())){
                $value = $highestNum + 1;
            } else if($anime->getEndDate()->getTimestamp() <= $firstDayOfWeek->getTimestamp()){
                $value = $highestNum;
            }
        }


        return new Status(isError: false, return: array("numeration" => $value), bareReturn: array("numeration" => $value));
    }

}