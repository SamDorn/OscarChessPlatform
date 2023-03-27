<?php

namespace App\models;

use Database;

@include_once '../../config/db_connection.php';
@include_once '../config/db_connection.php';

class Model
{
    protected \PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }
}
