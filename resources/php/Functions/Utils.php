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



}