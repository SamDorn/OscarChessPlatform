<?php

namespace App\controllers;

use App\models\UserModel;
use Google;

class GoogleController
{
    private const CLIENT_ID = '';
    private const CLIENT_SECRET = '';
    private const REDIRECT_URI = 'http://localhost/google';
    private Google\Client $googleClient;
    private ?string $code;
    private UserModel $userModel;

    public function __construct()
    {
        $this->googleClient = new Google\Client();
        $this->googleClient->setClientId(self::CLIENT_ID);
        $this->googleClient->setClientSecret(self::CLIENT_SECRET);
        $this->googleClient->setRedirectUri(self::REDIRECT_URI);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
        $this->code = $_GET['code'] ?? null;
        $this->userModel = new UserModel();
    }

    public function handleLogin() : void
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->code);
        $this->googleClient->setAccessToken($token);
        $googleService = new Google\Service\Oauth2($this->googleClient);
        $data = $googleService->userinfo->get();
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        $this->userModel->setEmail($data->email);
        $this->userModel->setUsername($data->name);
        $this->userModel->setAvatar($data->picture);
        $this->userModel->setVerified(true);
        
        
        //header("Location: home");
    }
    public function getUrl() : string
    {
        return $this->googleClient->createAuthUrl();
    }

}