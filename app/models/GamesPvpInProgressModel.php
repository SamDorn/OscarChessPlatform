<?php

namespace App\models;

use App\core\Model;

class GamesPvpInProgressModel extends Model
{
    private string $id;
    private int $idPlayer;
    private ?string $status;
    private ?string $pgn;
    private ?string $lastMove;

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
    public function getPgn(): string
    {
        return $this->pgn;
    }
    public function setIdPlayer(int $idPlayer): void
    {
        $this->idPlayer = $idPlayer;
    }
    public function setLastMove(string $lastMove): void
    {
        $this->lastMove = $lastMove;
    }
    public function getLastMove(): ?string
    {
        $query = "SELECT last_move FROM games_pvp_in_progress WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result == false ? "" : $result['last_move'];
    }
    /**
     * Creates a new chess game, sets the second player to null, the color to either null or to the player's id
     *
     * @return void
     */
    public function createGame(): void
    {
        $this->setNewId();
        $query = "INSERT INTO games_pvp_in_progress (id, id_player1, id_player2, id_player_white, status, pgn, last_move) VALUES
            (:id, :id_player1, null, :id_player_white, 'waiting a second player', null, null)
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->bindValue(":id_player1", $this->idPlayer);
        $stmt->bindValue(":id_player_white", $this->randomColor());
        $stmt->execute();
    }
    public function endGame()
    {
        $query = "DELETE FROM games_pvp_in_progress WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();
    }
    /**
     * Check if the user is already in an existing game
     *
     * @return boolean true if he exist, false if it doesn't
     */
    public function isUserInGame(): bool
    {
        $query = "SELECT id FROM games_pvp_in_progress WHERE id_player1 = :id1 OR id_player2 = :id2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id1", $this->idPlayer);
        $stmt->bindValue(":id2", $this->idPlayer);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result == false ? false  : true;
    }
    /**
     * Sets a unique id for the game. Uses recursive function to check if that unique id already exists.
     *
     * @return void
     */
    private function setNewId(): void
    {
        $this->id = uniqid();
        $query = "SELECT id FROM games_pvp_in_progress";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        $result = $result == false ? []  : $result;
        foreach ($result as $id) {
            if ($this->id === $id) {
                $this->setNewId();
            } else
                return;
        }
    }
    /**
     * Returns the id of the game based on a id of the player
     *
     * @return string id of the game
     */
    public function getIdFromUser(): string
    {
        $query = "SELECT id FROM games_pvp_in_progress WHERE id_player1 = :id1 OR id_player2 = :id2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id1", $this->idPlayer);
        $stmt->bindValue(":id2", $this->idPlayer);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['id'];
    }
    /**
     * Returns the pgn of the match or null on empty pgn
     *
     * @return string|null pgn of the game
     */
    public function getPgnFromId(): ?string
    {
        $query = "SELECT pgn FROM games_pvp_in_progress WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['pgn'];
    }
    /**
     * Returns true if there are existing games with no second player
     *
     * @return boolean true if there are, false if there aren't
     */
    public function getGamesNoSecondPlayer(): bool
    {
        $query = "SELECT id FROM games_pvp_in_progress WHERE id_player2 IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        if (!$result)
            return false;

        $this->setId($result['id']);
        return true;
    }
    public function updateLastMove(): void
    {
        $query = "UPDATE games_pvp_in_progress SET last_move = :last_move WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":last_move", $this->lastMove);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();
    }
    /**
     * Insert the user in an existing game(second player = NULL) and sets the color to either the id of the player
     * or leave it there as is.
     *
     * @return void
     */
    public function insertExistingGame(): void
    {
        $query = "UPDATE games_pvp_in_progress SET id_player2 = :id2, status = 'playing', id_player_white = CASE WHEN id_player_white IS NULL THEN :id3 ELSE id_player_white END WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id2", $this->idPlayer);
        $stmt->bindValue(":id3", $this->idPlayer);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();
    }
    /**
     * Return the color of the player
     *
     * @return string color of the player
     */
    public function getColorById(): string
    {
        $query = "SELECT id_player_white FROM games_pvp_in_progress WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result['id_player_white'] == $this->idPlayer)
            return "white";

        return "black";
    }
    /**
     * Updates the pgn of the match
     *
     * @return void
     */
    public function updatePgn(): void
    {
        $query = "UPDATE games_pvp_in_progress SET pgn = :pgn WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->bindValue(":pgn", $this->pgn);
        $stmt->execute();
    }
    /**
     * Get the id of the opponent
     *
     * @return integer id of the player
     */
    public function getOtherPlayer(): ?int
    {
        $query = "SELECT id_player1, id_player2 FROM games_pvp_in_progress WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result['id_player1'] === $this->idPlayer)
            return $result['id_player2'];
        return $result['id_player1'];
    }
    /**
     * Delete the game with no second user. It is used when the player clicks
     * return home while trying to find a player to play with
     *
     * @return void
     */
    public function deleteGameNoSecondPlayer(): void
    {
        $query = "DELETE FROM games_pvp_in_progress WHERE id_player1 = :id1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id1", $this->idPlayer);
        $stmt->execute();
    }
    private function randomColor(): ?int
    {
        $num = rand(0,1);
        if($num == 0)
            return $this->idPlayer;
        return null;
    }
    
}
