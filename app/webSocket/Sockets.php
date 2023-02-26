<?php

namespace App;

require_once '../../vendor/autoload.php';

use App\controllers\PvpController;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Sockets implements MessageComponentInterface
{
    private $pvpController;
    private $prova;
    private $clients;
    private $games;
    private $gameNoSecondPlayer;
    public function __construct()
    {
        $this->pvpController = new PvpController();
        $this->clients = new \SplObjectStorage;
        $this->games = array();
        echo "Server Started\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {

        // Store the new connection in $this->clients
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg); //decode the message
        if(isset($data->username))
            $this->pvpController->setUsername($data->username);

        @$status = $data->status;
        @$gameId = $data->gameId;
        @$fen = $data->fen;
        @$chatMsg = $data->chatMsg;



        if ($gameId == null) { //if gameId is null he's just landed on the page
            /**
             * Search the first game that has no second player
             */
            foreach ($this->games as $game) {
                if ($game->players[1] == null) {
                    $this->gameNoSecondPlayer = $game;
                    break;
                }
            }
            /**
             * if the game witout second player is not null it will insert the 
             * player so that the match can start
             */
            if (!is_null($this->gameNoSecondPlayer)) {
                $this->gameNoSecondPlayer->insertPlayer($from);
                $this->games[] = $this->gameNoSecondPlayer;
                if (is_null($this->gameNoSecondPlayer->black))
                    $color = "black";
                else
                    $color = "white";

                $this->gameNoSecondPlayer->players[1]->send(json_encode(array(
                    'color' => $color
                )));
                foreach ($this->gameNoSecondPlayer->players as $player) {
                    $player->send(json_encode(array(
                        'gameId' => $this->gameNoSecondPlayer->getId(),
                        'status' => "ready to play"
                    )));
                }
                //$this->gameNoSecondPlayer->setstatus;
                $this->gameNoSecondPlayer = null;
                //$this->pvpController->addSecondUsername();
                echo "Inserted in an existing game\n";
            }
            /**
             * if the game without a second player is null it means that there are no 
             * games without the second player so it will create a new game.
             */
            else {
                //$this->pvpController->createGame();
                $game = new ChessGame($from);
                $this->games[] = $game;
                if (is_null($game->black))
                    $color = "white";
                else
                    $color = "black";
                $from->send(json_encode(array(
                    "status" => "searching for a second player",
                    "color" => $color
                )));
                echo "Created a new game\n";
            }
        }
        /**
         * if the game id is not null it takes the game with the same unique id 
         * and then sends the updated fen position.
         */
        else {
            foreach ($this->games as $game) {
                if ($game->getId() == $gameId) {
                    $this->prova = $game;
                    break;
                }
            }
            $this->prova->updateFen($fen);
            if ($status != null && $status == "game_over") {
                foreach ($this->prova->players as $player) {
                    $player->send(json_encode(array(
                        "status" => "game terminated"
                    )));
                }
                $this->games = array_filter($this->games, function ($i) use ($gameId) {
                    return $i->getId() != $gameId;
                });
                echo count($this->games);
            }
            else{
                foreach ($this->prova->players as $player) {
                    $player->send(json_encode(array(
                        'gameId' => $this->prova->getId(),
                        'fen' => $this->prova->fen
                    )));
                }
            }
            
        }
    }
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        foreach($this->games as $game)
        {
            $gameId = $game->getGameByConnection($conn);
        }
        $this->games = array_filter($this->games, function ($i) use ($gameId) {
            return $i->getId() != $gameId;
        });
        echo count($this->games);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {

    }
}

class ChessGame
{

    public $id;
    public $players;
    public $fen;
    public $white;
    public $black;
    public $status;


    public function __construct($player)
    {
        $this->id = uniqid();
        $this->players = array($player, null);
        $this->white = null;
        $this->black = null;
        $this->blackOrWhite($player);
    }
    /**
     * Return the id of the chess game
     *
     * @return void
     */
    public function getId()
    {
        return $this->id;
    }
    public function getGameByConnection($conn)
    {
        foreach($this->players as $player)
        {
            if($player == $conn)
                return $this->id;
        }
        return;
    }

    /**
     * Set the status of the game
     *
     * @param string $status
     * @return void
     */
    public function setstatus($status)
    {
        $this->status = $status;
    }
    /**
     * Makes a player randomly black or white.
     *
     * @param ConnectionInterface $player
     * @return void
     */
    private function blackOrWhite($player)
    {
        $rand = rand(0, 1);
        if ($rand == 0)
            $this->white = $player;
        else
            $this->black = $player;
    }

    /**
     * It gets the other player of the match
     *
     * @param ConnectionInterface $player
     * @return void
     */
    public function getOtherPlayer($player)
    {
        if ($this->players[0] === $player) {
            return $this->players[1];
        } elseif ($this->players[1] === $player) {
            return $this->players[0];
        } else {
            return null;
        }
    }
    /**
     * Insert the player in an existing match without a second player
     *
     * @param ConnectionInterface $player
     * @return void
     */
    public function insertPlayer($player)
    {
        $this->players[1] = $player;
    }

    /**
     * Updates the fen provided by the player.
     *
     * @param string $fen
     * @return void
     */
    public function updateFen($fen)
    {
        $this->fen = $fen;
    }
}
