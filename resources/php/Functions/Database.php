<?php

namespace Functions;
use Exceptions\ColumnNotFound;
use Exceptions\IOException;
use Exceptions\TableNotFound;
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

    private static ?Mysqli $database = null;

    /**
     * @throws IOException
     */
    public static function getConnection() :  Mysqli{
        if(self::$database == null || !self::$database->stat()) {
            self::$database = new mysqli(
                hostname: self::$database_settings["address"],
                username: self::$database_settings["username"],
                password: self::$database_settings["password"],
                port: self::$database_settings["port"]);
            if (self::$database->connect_error) {
                throw new IOException(address: self::$database_settings["address"], port: self::$database_settings["port"]);
            } else {
                self::$database->select_db(self::$database_settings["database"]);
                self::$database->set_charset(self::$database_settings["charset"]);
            }
        }
        return self::$database;
    }
    public static function getNextIncrement($table) : int {
        try {
            return self::getConnection()->query("SELECT auto_increment as 'val' FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '$table'")->fetch_array()["val"];
        } catch (IOException $e){
            return -1;
        }
    }
    private static array $data_types_text = array(
        "char" => 1,
        "varchar" => 255,
        "binary" => 1,
        "varbinary" => 255,
        "text" => 65535,
        "blob" => 65535
    );
    private static array $data_types_non_fractional_number = array(
        "bit" => 0,
        "tinyint" => 1,
        "smallint" => 2,
        "mediumint" => 3,
        "int" => 4,
        "bigint" => 8
    );
    private static array $data_types_fractional_number = array(
        "float" => 4,
        "double" => 8,
        "decimal" => 4.9
    );
    public static function isUniqueKey($column, $table, $schema = "cyrus") : Bool{
        try {
            $row = self::getConnection()->query("SELECT column_name,COLUMN_KEY FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = '$table' AND column_name = '$column' ")->fetch_array();
            return (strtolower($row["COLUMN_KEY"]) == "uni");
        } catch (IOException $e){
            return false;
        }
    }
    public static function isPrimaryKey($column, $table, $schema = "cyrus") : Bool{
        try {
            $row = self::getConnection()->query("SELECT column_name,COLUMN_KEY FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = '$table' AND column_name = '$column' ")->fetch_array();
            return (strtolower($row["COLUMN_KEY"]) == "pri");
        } catch (IOException $e){
            return false;
        }
    }
    public static function isUniqueValue(String $column, String $table, $value, array $ignore_record = null, $schema = "cyrus") : Bool{
        try {
            $sql = "";
            if($ignore_record != null) {
                foreach($ignore_record as $key => $v){
                    $sql .= " AND (" . $key . " <> '" . $v . "')";
                }
            }
            return self::getConnection()->query("SELECT $column FROM $table where $column = '$value' " . $sql)->num_rows <= 0;
        } catch (IOException $e){
            return false;
        }
    }

    public static function isNullable(String $column, String $table, $schema = "cyrus"): Bool{
        try {
            $query = self::getConnection()->query("SELECT column_name, column_default, is_nullable FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = '$table' and column_name = '$column'");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                return strtolower($row["is_nullable"]) == 'no' && $row["column_default"] != "";
            } else {
                return false;
            }
        } catch (IOException $e){
            return false;
        }
    }

    /**
     * @throws ColumnNotFound
     * @throws TableNotFound
     */
    public static function getColumnSize($column, $table, $schema = "cyrus") : Size{
        try {
            $query = self::getConnection()->query("SELECT column_name, DATA_TYPE, COLUMN_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE, DATETIME_PRECISION FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = '$table' AND column_name = '$column' ");
            if($query->num_rows == 0){
                if(self::getConnection()->query("SELECT column_name FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = '$table' ")->num_rows > 0){
                    throw new ColumnNotFound(column: $column, table: $table);
                } else {
                    throw new TableNotFound(table: $table);
                }
            }
            $row = $query->fetch_array();
            $precision = $row["NUMERIC_PRECISION"];
            $scale = $row["NUMERIC_SCALE"] != "" ?? 0;
            if(isset(self::$data_types_text[$row["DATA_TYPE"]])){
                $size = $row["COLUMN_TYPE"];
                $regex = '/(?:\[[^][]*]|\([^()]*\)|{[^{}]*})(*SKIP)(*F)|[^][(){}]+/m';
                $result = str_replace("(", "", preg_replace($regex, '', $size));
                $size = str_replace(")", "", $result);
                return new Size($size);
            } else if (isset(self::$data_types_non_fractional_number[$row["DATA_TYPE"]])) {
                $maximum_bytes = self::$data_types_non_fractional_number[$row["DATA_TYPE"]];
                return Number::getSigned($maximum_bytes);
            } else if (isset(self::$data_types_fractional_number[$row["DATA_TYPE"]])) {
                if (strtolower(self::$data_types_fractional_number[$row["DATA_TYPE"]]) != "decimal") {
                    $maximum_bytes = self::$data_types_non_fractional_number[$row["DATA_TYPE"]];
                } else {
                    $value = str_repeat("9", $precision - $scale);
                    if ($scale > 0) {
                        $value .= ".";
                        $value .= str_repeat("9", $scale);
                    }
                    $maximum_bytes = ceil(strlen($value) / 9) * 4;;
                }
                return Number::getSigned($maximum_bytes);
            }
        } catch (IOException $e){
            return new Size(-1, -1);
        }
        return new Size(-1, -1);
    }

    /**
     * @throws ColumnNotFound
     * @throws TableNotFound
     */
    public static function isWithinColumnSize($value, $column, $table, $schema = "cyrus") : Bool{
        if(!isset($column) || $column == null || $column != "") return true;
        try {
            $query = self::getConnection()->query("SELECT column_name, DATA_TYPE, COLUMN_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE, DATETIME_PRECISION FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = '$table' AND column_name = '$column' ");
            if($query->num_rows == 0){
                if(self::getConnection()->query("SELECT column_name FROM information_schema.columns WHERE table_schema = '$schema' AND table_name = '$table' ")->num_rows > 0){
                    throw new ColumnNotFound(column: $column, table: $table);
                } else {
                    throw new TableNotFound(table: $table);
                }
            }
            $row = $query->fetch_array();
            $precision = $row["NUMERIC_PRECISION"];
            $scale = $row["NUMERIC_SCALE"] != "" ?? 0;
            if(isset(self::$data_types_text[$row["DATA_TYPE"]])){
                $size = $row["COLUMN_TYPE"];
                $regex = '/(?:\[[^][]*]|\([^()]*\)|{[^{}]*})(*SKIP)(*F)|[^][(){}]+/m';
                $result = str_replace("(", "", preg_replace($regex, '', $size));
                $size = str_replace(")", "", $result);
                return strlen($value . "") <= $size;
            } else if (isset(self::$data_types_non_fractional_number[$row["DATA_TYPE"]])) {
                if (strlen($value . "") <= $precision) {
                    $maximum_bytes = self::$data_types_non_fractional_number[$row["DATA_TYPE"]];
                    $bytes_range = Number::getSigned($maximum_bytes);
                    return ($value <= $bytes_range->getMaximum() && $value >= $bytes_range->getMinimum());
                } else return false;
            } else if (isset(self::$data_types_fractional_number[$row["DATA_TYPE"]])) {
                $before_comma = $scale > 0 ? explode(",", $value . "")[0] : $value . "";
                $after_comma = $scale > 0 ? explode(",", $value . "")[1] : "";
                if (strlen($before_comma) <= $precision && strlen($after_comma) <= $scale) {
                    if (strtolower(self::$data_types_fractional_number[$row["DATA_TYPE"]]) != "decimal") {
                        $maximum_bytes = self::$data_types_non_fractional_number[$row["DATA_TYPE"]];
                    } else {
                        $maximum_bytes = ceil(strlen($value . "") / 9) * 4;;
                    }
                    $bytes_range = Number::getSigned($maximum_bytes);
                    return ($value <= $bytes_range->getMaximum() && $value >= $bytes_range->getMinimum());
                }
            } else return true;
        } catch (IOException $e){
            return false;
        }
        return false;
    }
}