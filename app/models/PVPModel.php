<?php

namespace App\models;

use Database;

require_once "../../config/db_connection.php";

class PvpModel
{
    private $con;

    public function __construct()
    {
        $database = new Database();
        $this->con = $database->getConnection();
    }
    public function createGame($username)
    {
        $query = "INSERT INTO games_pvp (id, username_1, username_2, last_fen, white, status)
            VALUES (null, :username_1, null, null, null, 'Waiting for a second player')";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username_1', $username);
        $stmt->execute();
    }
    public function addUsername($username_1, $username_2)
    {
        $query = "UPDATE games_pvp SET username_2 = :username_2, status = 'ready to play'
            WHERE username_1 = :username_1";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username_1', $username_1);
        $stmt->bindParam(':username_2', $username_2);
        $stmt->execute();
    }
}
