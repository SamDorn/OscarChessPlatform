<?php

namespace App\models;

use App\models\Model;

class PvpInProgressModel extends Model
{
    private $id;
    private $username;
    private $connection;
    private $status;
    private $pgn;
    private $white;

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
     * @return void
     */
    public function getId()
    {
        return $this->id;
    }
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }
    /**
     * Undocumented function
     *
     * @param [type] $username_1
     * @param [type] $color
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /**
     * Undocumented function
     *
     * @param [type] $status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function setWhite($white)
    {
        $this->white = $white;
    }
    /**
     * Undocumented function
     *
     * @param [type] $pgn
     * @return void
     */
    public function setPgn($pgn)
    {
        $this->pgn = $pgn;
    }
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function checkIsInGame()
    {
        $query = "SELECT id FROM games_pvp_in_progress WHERE username_1 = :username_1 OR username_2 = :username_2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username_1', $this->username);
        $stmt->bindParam(':username_2', $this->username);
        $stmt->execute();

        $result = $stmt->fetch();
        if($result > 0){
            return $result['id'];
        }
        else{
            return "";
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getGameById()
    {
        $query = "SELECT pgn FROM games_pvp_in_progress WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result > 0){
            return $result['pgn'];
        }
        else{
            return "";
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getGamesNoSecondPlayer()
    {
        $query = "SELECT * FROM `games_pvp_in_progress` WHERE username_2 IS NULL";
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch();
   
            if($result > 0){
                return $result['id'];
            }
            else{
                return "";
            }
        
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function AddSecondPlayer()
    {
        $query = "UPDATE games_pvp_in_progress SET username_2 = :username, status = 'ready to play', connection_2 = :connection WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':connection', $this->connection);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }
    /**
     * Undocumented function
     *
     * @return array
     */
    public function getConnectionsFromId() : array
    {
        $query = "SELECT connection_1, connection_2 FROM games_pvp_in_progress WHERE id= :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $result = $stmt->fetch();
        return array("connection_1" => $result['connection_1'], "connection_2" => $result['connection_2']);
    }
    /**
     * Undocumented function
     *
     * @return array
     */
    public function getUsernamesFromId()
    {
        $query = "SELECT username_1, username_2 FROM games_pvp_in_progress WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $result = $stmt->fetch();
        return array('username_1' => $result['username_1'], 'username_2' => $result['username_2']);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function SetTableWhite()
    {
        $query = "UPDATE games_pvp_in_progress SET white = :white WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':white', $this->white);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

    }

    /**
     * Undocumented function
     *
     * @param [type] $username
     * @return void
     */
    public function createGame()
    {
        $query = "INSERT INTO games_pvp_in_progress (id, username_1, username_2,connection_1, connection_2, status, pgn, white)
            VALUES (null, :username, null, :connection, null, 'Waiting for a second player', null, null)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':connection', $this->connection);
        $stmt->execute();
    }
}
?>
