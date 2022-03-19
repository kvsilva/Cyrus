<?php

namespace Functions;

use JetBrains\PhpStorm\ArrayShape;

class Debug
{
    public static function printFormatted(array $elements){
        echo "<br>";
        echo "----------------------------------------------------------------------------------------------------";
        foreach($elements as $key => $value){
            echo "<br>";
            echo $key . ": " . $value;
        }
        echo "<br>";

    }
}