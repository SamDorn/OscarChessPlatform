<?php

namespace App\utilitis;

use App\models\UserModel;
use ReallySimpleJWT\Token;


class Jwt
{

    private const SECRET = '0$c@rCh3$$Pl@tf0rm';
    private const ISSUER = 'localhost';
    public static function createToken(UserModel $userModel): void
    {
        $token = Token::create($userModel->getId(), self::SECRET, time()+ 3600, self::ISSUER);
        setcookie("jwt", $token, time()+ 3600, '/', 'localhost', true, true);
    }
    public static function validate(string $token)
    {
        return Token::validate($token, self::SECRET);
    }
    public static function getPayload(string $token)
    {
        return Token::getPayload($token);
    }
}