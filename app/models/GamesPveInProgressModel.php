<?php

namespace App\models;

use App\core\Model;

class GamesPveInProgressModel extends Model
{
    private string $id;
    private int $idPlayer;
    private string $pgn;
    private int $skill;
    private string $lastMove;

    public function setId(string $id): void
    {
        $this->id = $id;
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function setPgn(string $pgn): void
    {
        $this->pgn = $pgn;
    }
    public function setSkill(int $skill): void
    {
        $this->skill = $skill;
    }
    public function getPgn(): string
    {
        $query = "SELECT pgn FROM games_pve_in_progress WHERE id_player = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->idPlayer);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['pgn'];
    }
    public function setIdPlayer(int $idPlayer): void
    {
        $this->idPlayer = $idPlayer;
    }
    public function setLastMove(string $lastMove): void
    {
        $this->lastMove = $lastMove;
    }

    public function createGame(?int $color)
    {
        $query = "INSERT INTO games_pve_in_progress (id, id_player, id_player_white, pgn, skill, last_move)
            VALUES (null, :id_player, :id_player_white, :pgn, :skill, :last_move)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id_player", $this->idPlayer);
        $stmt->bindValue(":id_player_white", $color);
        $stmt->bindValue(":pgn", $this->pgn);
        $stmt->bindValue(":skill", $this->skill);
        $stmt->bindValue(":last_move", $this->lastMove);
        $stmt->execute();
    }
    public function isUserInGame(): bool
    {
        $query = "SELECT id FROM games_pve_in_progress WHERE id_player1 = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->idPlayer);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result == false ? false  : true;
    }
    public function getLastMove(): string
    {
        $query = "SELECT last_move FROM games_pve_in_progress WHERE id_player = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->idPlayer);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['last_move'];
    }
    public function updateGame(): void
    {
        $query = "UPDATE games_pve_in_progress SET pgn = :pgn, last_move = :last_move WHERE id_player = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->idPlayer);
        $stmt->bindValue(":pgn", $this->pgn);
        $stmt->bindValue(":last_move", $this->lastMove);
        $stmt->execute();
    }
    public function getColor(): string
    {
        $query = "SELECT id_player_white FROM games_pve_in_progress WHERE id_player = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->idPlayer);
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['id_player_white'] === $this->idPlayer ? 'white' : 'black';
    }
}
