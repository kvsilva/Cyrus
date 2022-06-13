<?php

namespace Functions;

use Others\Routing;

class Utils
{
    public const BASE_URL = "https://localhost/Cyrus/";

    public static function isJson(String $string): bool
    {
        self::initialize();
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function getURL() : String
    {
        //return "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        self::initialize();
        return static::BASE_URL;
    }

    private static bool $initialized = false;

    private static function initialize() : void{
        if(!self::$initialized){
            self::$initialized = true;
            self::$dependencies = array(
                "JQuery" => (new Dependency("JQuery", Routing::resources["dependencies"], "3.6.0"))->addImport(path: "jquery-3.6.0.min.js"),
                "Popper" => (new Dependency("Popper", Routing::resources["dependencies"], "2.9.2"))->addImport(path: "popper.min.js"),
                "FontAwesome" => (new Dependency("FontAwesome", Routing::resources["dependencies"], "6.1.1"))->addImport(path: "css/all.css", extension: "css"),
                "Bootstrap" => (new Dependency("Bootstrap", Routing::resources["dependencies"], "5.0.2"))->addImport(path: "js/bootstrap.bundle.min.js")->addImport(path: "css/bootstrap.css", extension: "css"),
                "Personal" => (new Dependency("Personal", self::BASE_URL . "animes/"))->addImport(path: "assets/js/personal.js")->addImport(path: "assets/css/personal.css", extension: "css"),
                "Cyrus" => (new Dependency("resources", self::BASE_URL))->addImport(path: "js/cyrus.js")->addImport(path: "css/cyrus.css", extension: "css")->addImport("images/logo.png", "logo")->addImport("images/logo_white.png", "icon"),
            );
        }
    }

    public static array $dependencies;

    public static function getDependencies(String $name, $extension = "js") : ?String
    {
        self::initialize();
        $path = isset(self::$dependencies[$name]) ? self::$dependencies[$name]->getImport($extension) : "";
        echo $path;
        return file_exists($path) ? $path : "";
    }
}