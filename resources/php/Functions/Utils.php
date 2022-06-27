<?php

namespace Functions;

use AutoLoader;
use Functions\Routing;
use JetBrains\PhpStorm\Pure;

class Utils
{
    private static String $BASE_URL = "localhost/Cyrus/";   // Não incluir o protocolo

    private static String $BASE_PATH;

    /**
     * @return String
     */
    public static function getBasePath(): string
    {
        self::initialize();
        return self::$BASE_PATH;
    }

    public static function correctURL(String $str, $web = false) : string{
        if(!$web){
            return str_replace("/", "\\",$str);
        }
        return str_replace("\\", "/",$str);
    }

    public static function isJson(String $string): bool
    {
        self::initialize();
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function getURL() : String
    {
        self::initialize();
        return static::$BASE_URL;
    }

    private static bool $initialized = false;

    private static function url_origin( $s, $use_forwarded_host = false ): string
    {
        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ($s['HTTP_HOST'] ?? null);
        $host     = $host ?? $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    #[Pure]
    private static function full_url($s, $use_forwarded_host = false ): string
    {
        return self::url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
    }

    private static function initialize() : void{
        if(!self::$initialized){
            static::$BASE_URL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' . static::$BASE_URL : 'http://' . static::$BASE_URL;
            self::$BASE_PATH = dirname(__DIR__,3);
            self::$initialized = true;
            self::$dependencies = array(
                "JQuery" => (new Dependency("JQuery", Routing::getResource("dependencies"), "3.6.0"))->addImport(path: "jquery-3.6.0.min.js"),
                "Popper" => (new Dependency("Popper", Routing::getResource("dependencies"), "2.9.2"))->addImport(path: "popper.min.js"),
                "FontAwesome" => (new Dependency("FontAwesome", Routing::getResource("dependencies"), "6.1.1"))->addImport(path: "css/all.css", extension: "css"),
                "Bootstrap" => (new Dependency("Bootstrap", Routing::getResource("dependencies"), "5.0.2"))->addImport(path: "js/bootstrap.bundle.min.js")->addImport(path: "css/bootstrap.css", extension: "css"),
                "Personal" => (new Dependency("Personal", self::$BASE_URL . "animes/"))->addImport(path: "assets/js/personal.js")->addImport(path: "assets/css/personal.css", extension: "css"),
                "Episode" => (new Dependency("Episode", self::$BASE_URL))->addImport(path: "assets/js/episode.js")->addImport(path: "assets/css/episode.css", extension: "css"),
                "Search" => (new Dependency("Search", self::$BASE_URL))->addImport(path: "assets/js/search.js")->addImport(path: "assets/css/search.css", extension: "css"),
                "Calendar" => (new Dependency("Calendar", self::$BASE_URL))->addImport(path: "assets/js/calendar.js")->addImport(path: "assets/css/calendar.css", extension: "css"),
                "List" => (new Dependency("List", self::$BASE_URL . "animes/"))->addImport(path: "assets/js/list.js")->addImport(path: "assets/css/list.css", extension: "css"),
                "Home" => (new Dependency("Home", self::$BASE_URL))->addImport(path: "assets/js/home.js")->addImport(path: "assets/css/home.css", extension: "css"),
                "Account" => (new Dependency("Account", self::$BASE_URL . "user/"))->addImport(path: "assets/js/account.js")->addImport(path: "assets/css/account.css", extension: "css"),
                "Cyrus" => (new Dependency("resources", self::$BASE_URL))->addImport(path: "js/cyrus.js")->addImport(path: "css/cyrus.css", extension: "css")->addImport("images/logo.png", "logo")->addImport("images/icon.png", "icon")->addImport("html/header.php", "header")->addImport("html/footer.php", "footer")->addImport("html/head.php", "head")->addImport("js/models.js", "models")->addImport("js/request.js", "request")->addImport("js/routing.js", "routing"),
            );
        }
    }

    public static function goTo(String $location) : void{
        header('Location: ' . Routing::getRouting($location));
    }

    public static array $dependencies;

    public static function getDependencies(String $name, $extension = "js", $relative = false) : ?String
    {
        self::initialize();
        $path = isset(self::$dependencies[$name]) ? ($relative ? self::$BASE_PATH . "/" . self::$dependencies[$name]->getImport($extension, true) : self::$dependencies[$name]->getImport($extension)) : "";
        return $path;
    }

    public static function getClassesInNamespace($namespace)
    {
        $files = scandir(dirname((__DIR__ . "\\")) . "\\" . $namespace . "\\");

        $classes = array_map(function($file) use ($namespace){
            return $namespace . '\\' . str_replace('.php', '', $file);
        }, $files);
        return array_filter($classes, function($possibleClass){
            return class_exists($possibleClass);
        });
    }

}