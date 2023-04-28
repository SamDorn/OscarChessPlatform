<?php

namespace App\controllers;

use Google;
use App\utilities\Jwt;
use App\utilities\Email;
use App\models\UserModel;

class GoogleController
{
    private Google\Client $googleClient;
    private ?string $code;
    private UserModel $userModel;

    public function __construct()
    {
        $this->googleClient = new Google\Client();
        $this->googleClient->setClientId($_ENV['CLIENT_ID']);
        $this->googleClient->setClientSecret($_ENV['CLIENT_SECRET']);
        $this->googleClient->setRedirectUri($_ENV['REDIRECT_URI']);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
        $this->code = $_GET['code'] ?? null;
        $this->userModel = new UserModel();
    }

    public function handleLogin(): void
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->code);
        $this->googleClient->setAccessToken($token);
        $googleService = new Google\Service\Oauth2($this->googleClient);
        $data = $googleService->userinfo->get();

        $this->userModel->setUsername($data->name);
        $this->userModel->setEmail($data->email);
        $this->userModel->setPassword(null);
        $this->userModel->setAvatar($data->picture);
        $this->userModel->setVerified(true);

        /**
         * Checks if the user has already made the google login before
         */
        if (!$this->userModel->checkUser('google')) {
            $this->userModel->addUser('google');
            $this->userModel->checkUser('google'); //set the id of the user to create the jwt token
            Email::sendEmail($data->email, "google", null);
        }

        Jwt::createToken($this->userModel);

        header("Location: home");
    }
    /**
     * Returns the authentication URL for a Google client.
     * The URL is generated using the `createAuthUrl()` method of the
     * object.
     * 
     * @return string String of the URL for Google
     * authentication. 
     */
    public function getUrl(): string
    {
        return $this->googleClient->createAuthUrl();
    }
}
