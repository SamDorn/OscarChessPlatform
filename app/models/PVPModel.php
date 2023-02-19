<?php

namespace App\models;

use Database;

require_once '../config/db_connection.php';

class PVPModel
{
    private $con;

    public function __construct()
    {
        $database = new Database();
        $this->con = $database->getConnection();
    }

    public function createGame($username)
    {
        $query = "INSERT INTO games_pvp (id, username_1, username_2, last_fen, white) VALUES (null, :username_1, null, 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', NULL)";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username_1', $username);
        $stmt->execute();
    }
    /**
     * This function check if there is already a new game with only one player
     *
     * @return bool true if there is
     */
    public function checkNewGame()
    {
        $query = "SELECT * FROM games_pvp WHERE username_2 IS NULL";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result > 0)
            return true;
        else
            return false;
    }

    public function enterGame($username)
    {
        $query = "UPDATE games_pvp SET username_2 = :username_2 WHERE username_2 IS NULL";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username_2', $username);
        $stmt->execute();
    }
}