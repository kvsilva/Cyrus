<?php
class AutoLoader
{
    private static bool $registered = false;

    public static function register()
    {
        if(!static::$registered) {
            static::$registered = true;
            spl_autoload_register(function ($class) {
                $debug = false;
                $directories = array(
                    // NOTE: This must be the directory without the namespace, and when the Class is imported,
                    //  it will be from this directory that the namespace will be added.
                    (dirname(__FILE__) . "\\"),
                    (__DIR__ . "\\..\\..\\")
                );
                foreach ($directories as $directory) {
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
}
