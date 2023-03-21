<?php

/**
 * TO REDESIGN
 */
namespace App\models;

class UserInPvpModel extends Model
{
    private $id;
    private $idGame;
    private $username;
    private $connection;

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * Undocumented function
     *
     * @param [type] $idGame
     * @return void
     */
    public function setIdGame($idGame)
    {
        $this->idGame = $idGame;
    }
    /**
     * Undocumented function
     *
     * @param [type] $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function addUsername()
    {
        $query = "INSERT INTO users_in_games_pvp_in_progress (id, id_game, username, connection)
            VALUES (null, :idGame, :username, :connection)
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idGame', $this->idGame);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':connection', $this->connection);
        $stmt->execute();

    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getConnectionByUsername()
    {
        $query = "SELECT connection FROM users_in_games_pvp_in_progress WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        $result = $stmt->fetch();
        $this->connection = $result['connection'];
    }

}
?>