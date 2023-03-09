<?php

namespace App\models;

use Database;

@include_once '../../config/db_connection.php';
@include_once '../config/db_connection.php';

class Model
{
    protected $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
}
?>