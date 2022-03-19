<?php

use Enumerators\Availability;
use Enumerators\NightMode;
use Enumerators\Sex;
use Enumerators\Verification;
use Functions\Database;
use Objects\Punishment;
use Objects\PunishmentType;
use Objects\User;

session_start();
require_once(dirname(__FILE__) . '/../php/AutoLoader.php');
AutoLoader::register();
$user = new User(10, [USER::ALL]);
$punishment = new Punishment();
$punishment->setReason("Default Punishment");
$punishment->setPerformedBy(new User(2));
$punishment->setPunishmentType(new PunishmentType(18));
$date = new DateTime();
$date->add(new DateInterval('P30D'));
$punishment->setLastsUntil($date);
$user->addPunishment($punishment);
$user->store();


?>
