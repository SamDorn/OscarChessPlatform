<?php

namespace App\controllers;

use App\models\UserModel;
use Google;

class GoogleController
{
    private $clientId = '';
    private $clientSecret = '';
    private $redirectUri = 'http://localhost/oscarchessplatform/public/google';
    private $googleClient;
    private $code;
    private $userModel;

    public function __construct($code)
    {
        $this->googleClient = new Google\Client();
        $this->googleClient->setClientId($this->clientId);
        $this->googleClient->setClientSecret($this->clientSecret);
        $this->googleClient->setRedirectUri($this->redirectUri);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
        $this->code = $code;
        $this->userModel = new UserModel();
    }

    public function handleLogin()
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
    public function getUrl()
    {
        return $this->googleClient->createAuthUrl();
    }

}
?>