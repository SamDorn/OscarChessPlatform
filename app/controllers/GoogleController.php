<?php

namespace App\controllers;

use App\models\UserModel;
use Google;

class GoogleController
{
    private const CLIENT_ID = '3016701451-ijgbje181303kr9m7b49gq0ocqcfiqaj.apps.googleusercontent.com';
    private const CLIENT_SECRET = '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';
    private const REDIRECT_URI = 'http://localhost/google';
    private Google\Client $googleClient;
    private ?string $code;
    private UserModel $userModel;

    public function __construct($code)
    {
        $this->googleClient = new Google\Client();
        $this->googleClient->setClientId(self::CLIENT_ID);
        $this->googleClient->setClientSecret(self::CLIENT_SECRET);
        $this->googleClient->setRedirectUri(self::REDIRECT_URI);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
        $this->code = $code;
        $this->userModel = new UserModel();
    }

    public function handleLogin() : void
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->code);
        $this->googleClient->setAccessToken($token);
        $googleService = new Google\Service\Oauth2($this->googleClient);
        $data = $googleService->userinfo->get();
        var_dump($data);
        $this->userModel->setEmail($data->email);
        $this->userModel->setUsername($data->name);
        $this->userModel->setAvatar($data->picture);
        
        //header("Location: home");
    }
    public function getUrl() : string
    {
        return $this->googleClient->createAuthUrl();
    }

}