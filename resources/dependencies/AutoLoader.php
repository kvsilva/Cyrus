<?php

$directories = array("Psr", "Psr7", "League", "PHPMailer", "GuzzleHTTP");

foreach($directories as $directory) {

    $it = new RecursiveDirectoryIterator("C:\\xampp\\htdocs\\Cyrus\\resources\dependencies\\" . $directory);

// Loop through files
    foreach (new RecursiveIteratorIterator($it) as $file) {
        if ($file->getExtension() == 'php') {
            //echo $file . "<br><br>";
            include_once $file;
            //require_once($file);
        }
    }

}


