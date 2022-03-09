<?php
class AutoLoader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $directories = array(
                (dirname(__FILE__) . "\\")
            );
            foreach($directories as $directory) {
                if (file_exists($directory . $class . '.php')) {
                    require_once($directory . (str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php');
                    return true;
                }
            }
            return false;
        });
    }
}
