<?php
session_start();
require_once (dirname(__FILE__).'/resources/php/AutoLoader.php');
AutoLoader::register();
use Enumerators\Availability;
use Enumerators\NightMode;
use Enumerators\Sex;
use Enumerators\Verification;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\MalformedJSON;
use Exceptions\NotNullable;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Functions\Number;
use Objects\Language;
use Objects\Punishment;
use Objects\PunishmentType;
use Exceptions\RecordNotFound;
use Objects\User;
use Functions\Database;
$var = array(
    "user" =>  false,
    "language" => false
);

/*function getUnsignedRange($byte = 1){
    return [0, pow(2, ($byte * 8))-1];
}
function getSigned($byte = 1){
    return [0 - pow(2, ($byte * 8)-1), pow(2, ($byte * 8)-1)-1];
}

function getRequiredByteSize($value, $signed = true){
    $bytes = 0;
    do{
        $bytes++;
        $maximum = $signed ? getSigned($bytes)[1] : getUnsignedRange($bytes)[1];
        $minimum = $signed ? getSigned($bytes)[0] : getUnsignedRange($bytes)[0];
    } while ($maximum < $value || $minimum > $value);
    ECHO "SIGNED: " . $maximum . "<br>";
    ECHO "SIGNED (bytes): " . $bytes . "<br>";
    return $bytes;
}
function calculateBytesForNumberSigned($value){
    $bytes = 1;
    $maximum = pow(2, ($bytes * 4))-1;
    while($value > $maximum){
        $bytes++;
        $maximum = pow(2, ($bytes * 4))-1;
    }
    ECHO "SIGNED: " . $maximum . "<br>";
    ECHO "SIGNED (bytes): " . $bytes . "<br>";
    return $bytes;
    //31 2^31-1 = MAXIMUM SIZE
}

function calculateBytesForNumberUnsigned($value){
    $bytes = 1;
    $maximum = pow(2, ($bytes * 8))-1;
    while($value > $maximum){
        $bytes++;
        $maximum = pow(2, ($bytes * 8))-1;
    }
    ECHO "UNSIGNED: " . $maximum . "<br>";
    ECHO "UNSIGNED (bytes): " . $bytes . "<br>";
    return $bytes;
    //31 2^31-1 = MAXIMUM SIZE
}*/

echo (Database::isNullable(column: 'date___', table: 'data_types') ? 'TRUE' : 'FALSE');
try {
    //$x = 2147483647;
    //echo Number::getRequiredByteSize($x);
    //calculateBytesForNumberUnsigned($x);
    //calculateBytesForNumberSigned($x);


    //echo Database::isWithinColumnSize(value: "2147483647", column: "id", table: "user") ? 'TRUE':'FALSE';
    //echo "<br>";
    //echo Database::isUnique(column: "username", table: "user") ? 'TRUE':'FALSE';
    if($var["user"]) {
        $user = new User(1);
        $user->setEmail("lmao@gmail.com");
        $user->setUsername("yesItIs");
        $user->setPassword("123");
        $user->setBirthdate("2001-10-31 22:00:00");
        $user->setAboutMe("Nothing to say.");
        $user->setNightMode(NightMode::ENABLE);
        $user->setSex(Sex::MALE);
        $user->setStatus("Nothing to say here too.");
        $user->setVerified(Verification::VERIFIED);
        $user->setAvailable(Availability::AVAILABLE);
        $user->store();
    }
    if($var["language"]){
        $language = new Language();
        $language->setName("Portuguese");
        $language->setCode("pt_PT");
        $language->setOriginalName("Português");
        $language->store();
    }
} catch (NotNullable|IOException|UniqueKey|MalformedJSON|RecordNotFound|ColumnNotFound|TableNotFound|InvalidSize $e) {
    echo $e;
}
?>