<?php

use Enumerators\Availability;
use Enumerators\NightMode;
use Enumerators\Sex;
use Enumerators\Verification;
use Functions\Database;
use Objects\Punishment;
use Objects\User;

session_start();
require_once(dirname(__FILE__) . '/../php/AutoLoader.php');
AutoLoader::register();
$user = new User();
$user->setEmail("lmao@gmail.com");
$user->setUsername("yesItIs");
$user->setPassword("123");
$user->setBirthdate("2001-10-31 22:00:00");
$user->setAboutMe("Nothing to say.");
$user->setNightMode(NightMode::ENABLE);
$user->setSex(Sex::MALE);
$user->setStatus("Nothing to say here too.");
$user->setVerified(Verification::VERIFIED);
$user->setAvailable(Availability::AVAILABLE);
$punishment = new Punishment();
$punishment->setReason("Default Punishment");
$punishment->setPerformedBy(new User(2));
$date = new DateTime();
$date->add(new DateInterval('P30D'));
$punishment->setLastsUntil($date);
$user->addPunishment($punishment);
$user->store();


?>
