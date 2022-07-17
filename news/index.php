<?php
require_once (dirname(__DIR__, 1)).'\\resources\\php\\settings.php';
AutoLoader::register();

if(isset($_GET["news"])){
    require_once("item/index.php");
} else {
    require_once("list/index.php");
}



?>