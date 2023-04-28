<?php

namespace App\core;

use PDO;

class Database
{
    private static PDO $connectionInstance;

    private function __construct()
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        self::$connectionInstance = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";", $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $options);
    }

    /**
     * Uses singleton pattern so that there is only one PDO instance
     * throughout the application.
     *
     * @return PDO instance
     */
    public static function getConnection(): PDO
    {
        if (!isset(self::$connectionInstance))
            new Database;
        return self::$connectionInstance;
    }
}
