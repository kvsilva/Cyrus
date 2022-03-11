<?php

namespace Functions;
use Exceptions\IOException;
use mysqli;

require_once (dirname(__FILE__).'/../database.php');
class Database
{
    private static array $database_settings = array(
        "address" => "localhost",
        "port" => 3306,
        "username" => "root",
        "password" => "",
        "database" => "cyrus",
        "charset" => "utf8"
    );

    /**
     * @throws IOException
     */
    public static function getConnection() :  Mysqli{
        $database = new mysqli(
            hostname: self::$database_settings["address"],
            username: self::$database_settings["username"],
            password: self::$database_settings["password"],
            port: self::$database_settings["port"]);
        if ($database->connect_error) {
            throw new IOException(address: self::$database_settings["address"], port: self::$database_settings["port"]);
        } else {
            $database->select_db(self::$database_settings["database"]);
            $database->set_charset(self::$database_settings["charset"]);
        }
        return $database;
    }
    public static function getNextIncrement($table) : int {
        try {
            return self::getConnection()->query("SELECT auto_increment as 'val' FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '$table'")->fetch_array()["val"];
        } catch (IOException $e){
            return -1;
        }
    }
}