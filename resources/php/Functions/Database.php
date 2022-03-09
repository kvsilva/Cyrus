<?php

namespace Functions;
require_once (dirname(__FILE__).'/../database.php');
class Database
{
    public static function getNextIncrement($table) : int {
        GLOBAL $database;
        return $database->query("SELECT auto_increment as 'val' FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '$table'")->fetch_array()["val"];
    }
}