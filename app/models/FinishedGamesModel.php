<?php

namespace App\models;

use App\core\Model;

class FinishedGamesModel extends Model
{
    private string $id;
    private int $idPlayer;
    private ?string $pgn;
    private string $type;
    private int $idWinner;

    public function setId(string $id): void
    {
        $this->id = $id;
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function setIdPlayer(int $idPlayer): void
    {
        $this->idPlayer = $idPlayer;
    }
    public function setWinner(): void
    {
        $query = "UPDATE finished_games SET id_winner = :id_winner WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->bindValue(":id_winner", $this->idPlayer);
        $stmt->execute();
    }
}