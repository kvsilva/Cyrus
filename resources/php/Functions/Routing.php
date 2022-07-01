<?php
namespace Functions;

use Functions\Utils;

class Routing
{

    private static bool $initialized = false;

    private static ?array $routing = null;

    private static ?array $resources = null;

    private static function initialize(){
        if(!self::$initialized){

            self::$routing = array(
                "animes" => (Utils::getURL() . "animes/"),
                "calendar" => (Utils::getURL() . "calendar/"),
                "news" => (Utils::getURL() . "news/"),
                "episode" => (Utils::getURL() . "episode/"),
                "search" => (Utils::getURL() . "search/"),
                "account" => (Utils::getURL() . "user/account/"),
                "home" => (Utils::getURL()),
                "root" => (Utils::getURL()),
                "backoffice" => (Utils::getURL() . "backoffice/"),
                "API" => (Utils::getURL() . "api/v1/"),
            );

           self::$resources = array(
                "css" => (Utils::getURL() . "resources/css/"),
                "dependencies" => (Utils::getURL() . "resources/dependencies/"),
                "images" => (Utils::getURL() . "resources/images/"),
                "js" => (Utils::getURL() . "resources/js/"),
                "pages" => (Utils::getURL() . "resources/pages/"),
                "php" => (Utils::getURL() . "resources/php/"),
                "site" => (Utils::getURL() . "resources/site/"),
                "home" => (Utils::getURL() . "resources/"),
            );

            self::$initialized = true;
        }
    }



    public static function getRouting(String $name) : ?String{
        self::initialize();
        return self::$routing[$name] ?? null;
    }

    public static function getRoutingInfo() : array{
        self::initialize();
        return self::$routing;
    }

    public static function getResource(String $name) : ?String{
        self::initialize();
        return self::$resources[$name] ?? null;
    }

    public static function getResourceInfo() : array{
        self::initialize();
        return self::$resources;
    }

}