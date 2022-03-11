<?php
session_start();
require_once (dirname(__FILE__).'/resources/php/AutoLoader.php');
AutoLoader::register();
use Enumerators\Availability;
use Exceptions\UniqueKey;
use Objects\Punishment;
use Objects\PunishmentType;
use Exceptions\RecordNotFound;
use Objects\User;

echo Availability::AVAILABLE->name();


try {
    $punishment = new Punishment(2);
    $punishment->setReason("OMG");
    $punishment->store();
    /*$punishment = new Punishment();
    $punishment->setAvailable(Availability::AVAILABLE)->setPerformedBy((new User(1)));
    $punishment->setPunishmentType((new PunishmentType())->setName("teste de punição"))->setUser(new User(2));
    $punishment->setReason("asdadaad");
    $punishment->store();*/
} catch (UniqueKey $e) {
    echo $e;
}
?>