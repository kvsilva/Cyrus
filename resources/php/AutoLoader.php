<?php

class AutoLoader
{
    private static bool $registered = false;

    private static array $directories;

    public static function register()
    {
        if(!static::$registered) {
            static::$registered = true;
            spl_autoload_register(function ($class) {
                $debug = false;
                self::$directories = array(
                    // NOTE: This must be the directory without the namespace, and when the Class is imported,
                    //  it will be from this directory that the namespace will be added.
                    (dirname(__FILE__)),
                    (dirname(__FILE__) . "\\"),
                    (__DIR__ . "\\..\\..\\")
                );
                foreach (self::$directories as $directory) {
                    if($debug) {
                        echo "directory: " . $directory . "\n";
                        print_r(scandir($directory));
                    }
                    if (file_exists($directory . $class . '.php')) {
                        require_once($directory . (str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php');
                        return true;
                    }
                }
                return false;
            });
        }
    }


    public static function getDirectories() : array{
        return self::$directories;
    }
}
