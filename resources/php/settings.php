<?php
require_once("database.php");
$system_settings = array();
$user = null;
if(!$database->connect_error){
    $result = $database->query("SELECT * FROM GLOBAL_SETTINGS;");
    if($result) {
        while ($row = $result->fetch_array()){
            $system_settings[$row["name"]] = array(
                "value" => $row["value"],
                "value_binary" => $row["value_binary"],
                "data_type" => $row["data_type"]
            );
        }
    }
    $user = null; // object 'USER';
} else {
    $system_settings["maintenance"] = array(
        "value" => true,
        "value_binary" => null,
        "data_type" => "boolean"
    );
}
