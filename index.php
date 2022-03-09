<?php
session_start();
require_once (dirname(__FILE__).'/resources/php/AutoLoader.php');
AutoLoader::register();
use Enumerators\Availability;
use Objects\User;
use Exceptions\RecordNotFound;

echo Availability::AVAILABLE->name();


try {
    $user = new User(null, [User::PUNISHMENTS, USER::ROLES]);
} catch (RecordNotFound $e) {

}
?>