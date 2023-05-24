<?php

namespace App\utilities;

use App\models\UserModel;
use ReallySimpleJWT\Token;


class Jwt
{

    /**
     * Creates a JWT token for a user and sets it as a cookie with a one hour expiration
     * time.
     * 
     * @param UserModel userModel  Is an instance of the UserModel class, which represents a
     * user in the system. It contains information about the userand is used to create a JWT token for the user.
     */
    public static function createToken(UserModel $userModel): void
    {
        $token = Token::create($userModel->getId(), $_ENV['JWT_SECRET'], time() + 54000, $_ENV['ISSUER']);
        setcookie("jwt", $token, time() + 54000, '/', '', true, true);
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
        if (!$token) {
            return false;
        }
        if (!Token::validate($token, $_ENV['JWT_SECRET'])) {
            self::deleteToken();
            return false;
        }
        return true;
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
    /**
     * Updates the time token for a given user by deleting the existing token and creating
     * a new one.
     * 
     * @param UserModel Instance of the UserModel class, which
     * contains information about a user such as their ID, name, email, and other relevant data. This
     * parameter is used to update the time token for the user, which is a security measure to ensure
     * that the user's session remains active
     */
    public static function updateTimeToken(UserModel $userModel): void
    {
        self::deleteToken();
        self::createToken($userModel);
    }
}
