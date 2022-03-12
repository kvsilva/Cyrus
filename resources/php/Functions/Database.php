<?php

namespace Functions;
use Exceptions\IOException;
use mysqli;

require_once (dirname(__FILE__).'/../database.php');
class Database
{
    public const DateFormat = "Y-m-d H:i:s";

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
    private static array $data_types = array(
        "char" => 1.0,
        "varchar" => 255.0,
        "binary" => 1.0,
        "varbinary" => 255.0,
        "text" => 65535.0,
        "blob" => 65535.0,
        "bit" => 1.0,
        "tinyint" => 4.0,
        "smallint" => 6.0,
        "mediumint" => 9.0,
        "int" => 11.0,
        "bigint" => 20.0,
        "float" => ,
        "double" => ,
        "decimal" => 10.0,
    );
    public static function getColumnSize($column, $table, $schema = "cyrus"){
        try {
            $row = self::getConnection()->query("SELECT column_name, DATA_TYPE, COLUMN_TYPE FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = '$table' AND column_name = '$column' ")->fetch_array();
            if($row["data_type"])
        } catch (IOException $e){
            return -1;
        }
    }
}