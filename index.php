<?php
session_start();
require_once (dirname(__FILE__).'/resources/php/Objects/User.php');
require_once (dirname(__FILE__).'/resources/php/Enumerators/User.php');

echo Available::AVAILABLE;


$user = new User(null, [User::PUNISHMENTS, USER::ROLES]);
?>