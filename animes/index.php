<?php
require_once (dirname(__DIR__, 1)).'\\resources\\php\\settings.php';
AutoLoader::register();

if(isset($_GET["anime"])){
    require_once("personal/index.php");
} else {
    require_once("list/index.php");
}



?>