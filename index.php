<?php
session_start();
require_once (dirname(__FILE__).'/resources/php/AutoLoader.php');
AutoLoader::register();
use Enumerators\Availability;
use Enumerators\NightMode;
use Enumerators\Sex;
use Enumerators\Verification;
use Exceptions\IOException;
use Exceptions\MalformedJSON;
use Exceptions\NotNullable;
use Exceptions\UniqueKey;
use Objects\Language;
use Objects\Punishment;
use Objects\PunishmentType;
use Exceptions\RecordNotFound;
use Objects\User;
use Functions\Database;
$var = array(
    "user" => true,
    "language" => false
);

try {
    if($var["user"]) {
        $user = new User(10);
        $user->setEmail("vesilva24a@gmail.com");
        $user->setUsername("Kurookami");
        $user->setPassword("123");
        $user->setBirthdate("2001-10-31 22:00:00");
        $user->setAboutMe("Nothing to say.");
        $user->setNightMode(NightMode::ENABLE);
        $user->setSex(Sex::MALE);
        $user->setStatus("Nothing to say here too.");
        $user->setVerified(Verification::VERIFIED);
        $user->setAvailable(Availability::AVAILABLE);
        $user->setDisplayLanguage((new Language())->setName("English")->setCode("en")->setOriginalName("English"));
        $user->setTranslationLanguage((new Language())->setName("English, Canada")->setCode("en_CA")->setOriginalName("English, Canada"));
        $user->setEmailCommunicationLanguage((new Language())->setName("French")->setCode("fr")->setOriginalName("Français"));
        $user->store();
    }
    if($var["language"]){
        $language = new Language();
        $language->setName("Portuguese");
        $language->setCode("pt_PT");
        $language->setOriginalName("Português");
        $language->store();
    }
} catch (NotNullable|IOException|UniqueKey $e) {
    echo $e;
} catch (MalformedJSON|RecordNotFound $e) {
}
?>