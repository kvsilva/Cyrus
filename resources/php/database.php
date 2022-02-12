<?php
/** Database Values **/

$database_settings = array(
    "address" => "localhost",
    "port" => 3306,
    "username" => "root",
    "password" => "",
    "database" => "cyrus",
    "charset" => "utf_8"
);

$database = new mysqli($database_settings["address"], $database_settings["username"], $database_settings["password"], $database_settings["port"]);

if ($database->connect_error) {
    // Couldn't connect
    die("Connection failed: " . $database->connect_error);
} else {
    $database->select_db($database_settings["database"]);
    $database->set_charset($database_settings["charset"]);
}
?>
