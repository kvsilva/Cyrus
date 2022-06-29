<?php

require_once(dirname(__DIR__) . '\\..\\resources\\php\\settings.php');

use APIObjects\Response;
use Functions\Routing;
use Functions\Utils;

$fi = new FilesystemIterator(Utils::correctURL(Utils::getBasePath() . "/resources/site/"), FilesystemIterator::SKIP_DOTS);
$amount = iterator_count($fi);
$data = array();

if(isset($_FILES["files"])) {
    if (!is_countable($_FILES["files"]["name"])) {
        foreach ($_FILES["files"] as $key => $item) {
            $_FILES["files"][$key] = array($item);
        }
    }

    for ($i = 0; $i < count($_FILES["files"]["name"]); $i++) {
        $tmp_name = $_FILES["files"]["tmp_name"][$i];
        $tmp_name_only = explode("\\", $_FILES["files"]["tmp_name"][$i]);
        $tmp_name_only = $tmp_name_only[count($tmp_name_only) - 1];
        $extension = explode(".", $_FILES["files"]["name"][$i]);
        $extension = $extension[count($extension) - 1];
        $system_path = Utils::correctURL(Utils::getBasePath() . "/resources/site/tmp_files/temp_" . $amount . "_" . $tmp_name_only . "_" . bin2hex(random_bytes(22)) . "." . $extension);
        $webURL = Utils::correctURL(Routing::getResource("site") . "tmp_files/temp_" . $amount . "_" . $tmp_name_only ."_" . bin2hex(random_bytes(22)) . "." . $extension, true);
        move_uploaded_file($tmp_name, $system_path);
        $file = array(
            "name" => $_FILES["files"]["name"][$i],
            "type" => $_FILES["files"]["type"][$i],
            "size" => $_FILES["files"]["size"][$i],
            "tmp_name" => $_FILES["files"]["tmp_name"][$i],
            "error" => $_FILES["files"]["error"][$i],
            "full_path" => $_FILES["files"]["full_path"][$i],
            "system_path" => $system_path,
            "web_path" => $webURL,
        );
        $data[] = $file;
    }
    (new Response(status: true, data: $data))->encode(true);
} else (new Response(status: false, description: "Nenhum Ficheiro detectado."))->encode(true);
