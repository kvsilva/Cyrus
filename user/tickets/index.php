<?php
require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');
AutoLoader::register();

if(isset($_GET["ticket"])){
    require_once("item/index.php");
} else {
    require_once("main/index.php");
}



?>