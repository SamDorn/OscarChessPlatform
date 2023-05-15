<?php

namespace App\utilities;

use App\models\UserModel;
use ReallySimpleJWT\Token;


class Jwt
{

    private const SECRET = '0$c@rCh3$$Pl@tf0rm';
    private const ISSUER = '192.168.1.16';
    /**
     * Creates a JWT token for a user and sets it as a cookie with a one hour expiration
     * time.
     * 
     * @param UserModel userModel  Is an instance of the UserModel class, which represents a
     * user in the system. It contains information about the userand is used to create a JWT token for the user.
     */
    public static function createToken(UserModel $userModel): void
    {
        $token = Token::create($userModel->getId(), self::SECRET, time()+ 3600, self::ISSUER);
        setcookie("jwt", $token, time()+ 3600, '/', '', true, true);
    }
    /**
     * Validates a jwt and if the jwt isn't valid it redirects the user
     * to the login page
     * 
     * @param string jwt The jwt that needs to be validated.
     * 
     * @return bool Returns true on validation and false on error
     */
    public static function validate(?string $token): bool
    {
        if($token){
            if(!Token::validate($token, self::SECRET)){
                unset($_COOKIE['jwt']);
                setcookie('jwt', null, -1, '/'); 
                return false;
            }
            else{
                return true;
            }
        }
        return false;
        
    }
    public static function deleteToken(): void
    {
        unset($_COOKIE['jwt']);
        setcookie('jwt', null, -1, '/');
    }
    /**
     * Returns the payload of a given token
     * 
     * @param string token The parameter "token" is a string variable that represents a token. This
     * function is used to get the payload of the token.
     * 
     * @return array payload of a token, which is obtained by calling the `getPayload` method of the
     * `Token` class with the provided token as its argument.
     */
    public static function getPayload(?string $token): array
    {
        return Token::getPayload($token);
    }
    public static function updateTime(string $token): void
    {
        setcookie("jwt", $token, time()+ 3600, '/', 'localhost', true, true);
    }
}