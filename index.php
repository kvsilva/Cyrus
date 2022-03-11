<?php
session_start();
require_once (dirname(__FILE__).'/resources/php/AutoLoader.php');
AutoLoader::register();
use Enumerators\Availability;
use Enumerators\NightMode;
use Enumerators\Sex;
use Enumerators\Verification;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Exceptions\UniqueKey;
use Objects\Punishment;
use Objects\PunishmentType;
use Exceptions\RecordNotFound;
use Objects\User;
use Functions\Database;

echo Availability::AVAILABLE->name();


try {
    $user = new User();
    $user->setEmail("vesilva24a@gmail.com");
    $user->setUsername("Kurookami");
    $user->setPassword("123");
    $user->setBirthdate("2001-10-31 22:00:00");
    $user->setAboutMe("Nothing to say.");
    $user->setNightMode(NightMode::ENABLE);
    $user->setSex(Sex::MALE);
    $user->setStatus("Nothing to say here too.");
    $user->setVerified(Verification::VERIFIED);
    $user->setAvailable(Availability::AVAILABLE);
    $user->store();
} catch (NotNullable|IOException|UniqueKey $e) {
    echo $e;
}
?>