<?php
require_once (dirname(__FILE__).'/../../resources/php/AutoLoader.php');
AutoLoader::register();

use Exceptions\IOException;
use Functions\Utils;
use APIObjects\Request;
use APIObjects\Response;

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