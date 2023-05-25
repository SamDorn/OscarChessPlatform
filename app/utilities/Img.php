<?php

namespace App\utilities;

use App\core\Application;

class Img
{
    public static function saveImg(string $username, string $url): void
    {

        $avatarData = file_get_contents($url);
        $avatarPath = Application::$ROOT_DIR . "/public/images/avatars/$username.png";
        file_put_contents($avatarPath, $avatarData);

    }
}