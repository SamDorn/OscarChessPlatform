<?php

namespace App\utilities;

use App\models\UserModel;
use ReallySimpleJWT\Token;


class Jwt
{

    private const SECRET = '0$c@rCh3$$Pl@tf0rm';
    private const ISSUER = 'localhost';
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
        setcookie("jwt", $token, time()+ 3600, '/', 'localhost', true, true);
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
                header("Location: login?error=03");
                return false;
            }
            else{
                return true;
            }
        }
        return false;
        
    }
    /**
     * Returns the payload of a given token
     * 
     * @param string token The parameter "token" is a string variable that represents a token. This
     * function is used to get the payload of the token.
     * 
     * @return the payload of a token, which is obtained by calling the `getPayload` method of the
     * `Token` class with the provided token as its argument.
     */
    public static function getPayload(?string $token)
    {
        return Token::getPayload($token);
    }
}