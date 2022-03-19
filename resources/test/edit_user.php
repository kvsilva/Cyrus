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
$user = new User(11);
$user->setUsername("kekw");
$user->store();


?>
