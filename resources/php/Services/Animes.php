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

class Animes
{
        public static function getCalendar() : Status{
            try {

                $MONDAY = Anime::find(launch_day: DayOfWeek::MONDAY, orderBy: "launch_time");
                $TUESDAY = Anime::find(launch_day: DayOfWeek::TUESDAY, orderBy: "launch_time");
                $WEDNESDAY = Anime::find(launch_day: DayOfWeek::WEDNESDAY, orderBy: "launch_time");
                $THURSDAY = Anime::find(launch_day: DayOfWeek::THURSDAY, orderBy: "launch_time");
                $FRIDAY = Anime::find(launch_day: DayOfWeek::FRIDAY, orderBy: "launch_time");
                $SATURDAY = Anime::find(launch_day: DayOfWeek::SATURDAY, orderBy: "launch_time");
                $SUNDAY = Anime::find(launch_day: DayOfWeek::SUNDAY, orderBy: "launch_time");


                $today = new DateTime();

                
                $days = array(
                    DayOfWeek::MONDAY->value => (new DateTime())->modify(DayOfWeek::MONDAY->value -  date('w', $today->getTimestamp()) . " days"),
                    DayOfWeek::TUESDAY->value => (new DateTime())->modify(DayOfWeek::TUESDAY->value -  date('w', $today->getTimestamp()) . " days"),
                    DayOfWeek::WEDNESDAY->value => (new DateTime())->modify(DayOfWeek::WEDNESDAY->value -  date('w', $today->getTimestamp()) . " days"),
                    DayOfWeek::THURSDAY->value => (new DateTime())->modify(DayOfWeek::THURSDAY->value -  date('w', $today->getTimestamp()) . " days"),
                    DayOfWeek::FRIDAY->value => (new DateTime())->modify(DayOfWeek::FRIDAY->value -  date('w', $today->getTimestamp()) . " days"),
                    DayOfWeek::SATURDAY->value => (new DateTime())->modify(DayOfWeek::SATURDAY->value -  date('w', $today->getTimestamp()) . " days"),
                    DayOfWeek::SUNDAY->value => (new DateTime())->modify(DayOfWeek::SUNDAY->value -  date('w', $today->getTimestamp()) . " days"),
                );

                $calendar = array(
                    DayOfWeek::MONDAY->name() => array("day" => $days[DayOfWeek::MONDAY->value]->format(Database::DateFormatSimplified),"animes" => $MONDAY->toArray()),
                    DayOfWeek::TUESDAY->name() => array("day" => $days[DayOfWeek::TUESDAY->value]->format(Database::DateFormatSimplified),"animes" => $TUESDAY->toArray()),
                    DayOfWeek::WEDNESDAY->name() => array("day" => $days[DayOfWeek::WEDNESDAY->value]->format(Database::DateFormatSimplified),"animes" => $WEDNESDAY->toArray()),
                    DayOfWeek::THURSDAY->name() => array("day" => $days[DayOfWeek::THURSDAY->value]->format(Database::DateFormatSimplified),"animes" => $THURSDAY->toArray()),
                    DayOfWeek::FRIDAY->name() => array("day" => $days[DayOfWeek::FRIDAY->value]->format(Database::DateFormatSimplified),"animes" => $FRIDAY->toArray()),
                    DayOfWeek::SATURDAY->name() => array("day" => $days[DayOfWeek::SATURDAY->value]->format(Database::DateFormatSimplified),"animes" => $SATURDAY->toArray()),
                    DayOfWeek::SUNDAY->name() => array("day" => $days[DayOfWeek::SUNDAY->value]->format(Database::DateFormatSimplified),"animes" => $SUNDAY->toArray()),
                );
                $bareCalendar = array(
                    DayOfWeek::MONDAY->name() => array("day" => $days[DayOfWeek::MONDAY->value],"animes" => $MONDAY),
                    DayOfWeek::TUESDAY->name() => array("day" => $days[DayOfWeek::TUESDAY->value],"animes" => $TUESDAY),
                    DayOfWeek::WEDNESDAY->name() => array("day" => $days[DayOfWeek::WEDNESDAY->value],"animes" => $WEDNESDAY),
                    DayOfWeek::THURSDAY->name() => array("day" => $days[DayOfWeek::THURSDAY->value],"animes" => $THURSDAY),
                    DayOfWeek::FRIDAY->name() => array("day" => $days[DayOfWeek::FRIDAY->value],"animes" => $FRIDAY),
                    DayOfWeek::SATURDAY->name() => array("day" => $days[DayOfWeek::SATURDAY->value],"animes" => $SATURDAY),
                    DayOfWeek::SUNDAY->name() => array("day" => $days[DayOfWeek::SUNDAY->value],"animes" => $SUNDAY),
                );
            } catch (Exception $e) {
                return new Status(isError: true, message: $e->getMessage());
            }
            return new Status(isError: false, return: $calendar, bareReturn: $bareCalendar);
        }
}