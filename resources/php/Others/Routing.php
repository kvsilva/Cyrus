<?php
namespace Others;

use Functions\Utils;

class Routing
{

    public const routing = array(
        "animes" => (Utils::BASE_URL . "animes/"),
        "calendar" => (Utils::BASE_URL . "calendar/"),
        "news" => (Utils::BASE_URL . "news/")
    );

    public const social = array(
        "twitter" => "https://www.twitter.com",
        "facebook" => "https://www.facebook.com",
        "instagram" => "https://www.instagram.com"
    );

    public const resources = array(
          "css" => (Utils::BASE_URL . "resources/css/"),
          "dependencies" => (Utils::BASE_URL . "resources/dependencies/"),
          "images" => (Utils::BASE_URL . "resources/images/"),
          "js" => (Utils::BASE_URL . "resources/js/"),
          "pages" => (Utils::BASE_URL . "resources/pages/"),
          "php" => (Utils::BASE_URL . "resources/php/"),
    );



    public static function getRouting(String $name) : ?String{
        return self::routing[$name] ?? null;
    }

    public static function getResource(String $name) : ?String{
        return self::resources[$name] ?? null;
    }

}