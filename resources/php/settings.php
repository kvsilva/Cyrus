<?php

use Services\Session;

require_once(dirname(__DIR__) . '\\..\\resources\\php\\AutoLoader.php');
AutoLoader::register();
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
Session::updateSession()
?>
