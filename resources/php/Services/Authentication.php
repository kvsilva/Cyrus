<?php

namespace Services;

use APIObjects\Response;
use APIObjects\Status;
use Constants\API_MESSAGES;
use JetBrains\PhpStorm\Pure;
use Objects\User;
use ReflectionException;

class Authentication
{
    /**
     * @throws ReflectionException
     */
    #[Pure]
    public static function login($password, $email = null, $username = null) : Status{
        if(($email === null && $username === null)) return new Status(isError: true, message: API_MESSAGES::MISSING_FIELDS . "username/email.");
        if($password == null) return new Status(isError: true, message: API_MESSAGES::MISSING_FIELDS . "passowrd.");
        $users = User::find(email: $email, username: $username);
        if($users->size() > 0){
            if($users[0]->isPassword($password)){
                $_SESSION["user"] = $users[0];
                return new Status(isError: false, message: "Welcome back, " . $users[0]->getUsername());
            } else {
                return new Status(isError: true, message: "The credentials entered do not match.");
            }
        } else {
            return new Status(isError: true, message: "The user could not be found.");
        }
    }

    public static function logout() : Status{
        $_SESSION["user"] = null;
        return new Status(isError: false, message: "We hope to see you soon.");
    }

}