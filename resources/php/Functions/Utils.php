<?php

namespace Functions;

class Utils
{
    public const BASE_URL = "https://localhost/Cyrus";

    public static function isJson(String $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private const URL = "https://localhost/Cyrus";
    public static function getURL() : String
    {
        //return "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        return static::URL;
    }
}