<?php

namespace App\models;

use App\database\Database;



class Model
{
    protected \PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }
}
