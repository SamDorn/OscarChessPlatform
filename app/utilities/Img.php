<?php

namespace App\utilities;

use App\core\Application;

class Img
{
    public static function saveImg(string $url): mixed
    {
        $filename = basename($url);
        $img = file_get_contents($url);
        $folder_path = Application::$ROOT_DIR . "/app/images/$filename.png";
        return file_put_contents($folder_path, $img);

    }
}