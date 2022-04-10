<?php
require_once (dirname(__FILE__).'/../../resources/php/AutoLoader.php');
AutoLoader::register();

use API\v1\Handler;
use Exceptions\IOException;
use Exceptions\RecordNotFound;
use Functions\Utils;
use Objects\Request;
use Objects\Response;
use Objects\User;

$body = file_get_contents('php://input');;
if($body && Utils::isJson($body)) {
    try {
        $request = new Request(array: json_decode($body, true));
    } catch (IOException|ReflectionException $e) {
        (new Response(status: false, description: $e))->encode(print: true);
    }
} else {
    require(dirname(__FILE__) . '/../../resources/pages/api/home.php');
}
?>