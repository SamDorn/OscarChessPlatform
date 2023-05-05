<?php

namespace App\models;

use App\core\Model;
use Google\Service\ShoppingContent\ReturnShipment;

class GamesPvpModel extends Model
{
    private int $id;
    private int $id_player1;
    private ?int $id_player2;
    private ?string $status;
    private ?string $pgn;

    public function createGame(): void
    {
        $this->id = uniqid("", true);
        $query = "INSERT INTO games_pvp (id, id_player1, id_player2, status, pgn) VALUES
            (:id, :id_player1, :id_player2, :status, null)
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->bindValue(":id_player1", $this->id_player1);
        $stmt->bindValue(":id_player2", $this->id_player2);
        $stmt->bindValue(":status", $this->status);
        $stmt->execute();
    }
    public function searchGameByUserId(): ?array
    {
        $query = "SELECT id_player1, id_player2 FROM games_pvp WHERE id = :id AND status NOT 'finished'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id_player1);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
}