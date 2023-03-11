<?php

namespace App;

require_once '../../vendor/autoload.php';

use App\models\PvpInProgressModel;
use App\models\UserInPvpModel;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chess implements MessageComponentInterface
{
    private $pvpInProgressModel;
    private $userInPvpModel;
    private $connections;
    private $clients;
    public function __construct()
    {
        $this->pvpInProgressModel = new PvpInProgressModel();
        $this->userInPvpModel = new UserInPvpModel;
        $this->clients = new \SplObjectStorage;
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
        $request = $data->request;

        switch ($request) {
            case 'play':
                $username = $data->username;
                echo $username;
                $this->pvpInProgressModel->setUsername($username); //set the username
                //echo $this->pvpInProgressModel->getUsername();

                $id = $this->pvpInProgressModel->checkIsInGame(); // check if the user is in an existing game and returns the id of the game or empty string
                //echo $id;
                if ($id != "") {
                    echo "\nUtente è già in un game esistente";
                    $this->pvpInProgressModel->setId($id); // set the id of the game
                    $resourceConnection = $this->pvpInProgressModel->getConnectionFromUsername();
                    $from->send(json_encode(array( // send the player the position of the game
                        "status" => "Waiting for a second player",
                        "pgn" => $this->pvpInProgressModel->getGameById() //return the pgn of the current game
                    )));
                } else { // if the user is not in an existing game
                    echo "\nUtente non è in nessun game esistente";
                    $id = $this->pvpInProgressModel->getGamesNoSecondPlayer(); // return empty string if there aren'tany games waiting for a second player or the id of the game
                    if ($id != "") {
                        echo "\nLo sto inserendo in un game esistente senza second player";
                        $this->pvpInProgressModel->setId($id); // set the id
                        $this->pvpInProgressModel->setConnection($from->resourceId);
                        $this->pvpInProgressModel->AddSecondPlayer(); // add the second player
                        $players = $this->pvpInProgressModel->getUsernamesFromId(); // get the players
                        $rand = rand(0, 1);
                        if ($rand == 0) {
                            $this->pvpInProgressModel->setWhite($players['username_1']);
                        } else {
                            $this->pvpInProgressModel->setWhite($players['username_2']);
                        }
                        $this->pvpInProgressModel->setTableWhite();
                        $resourceConnections = $this->pvpInProgressModel->getConnectionsFromId();
                        foreach ($this->clients as $client) {
                            if ($client->resourceId == $resourceConnections['connection_1']) {
                                echo "vero";
                                $this->connections = array(
                                    1 => $client
                                );
                            }
                        }
                        foreach ($this->clients as $client) {
                            if ($client->resourceId == $resourceConnections['connection_2']) {
                                $this->connections = array_merge($this->connections, array(2 => $client));
                            }
                        }
                        foreach($this->connections as $connection){
                            $connection->send(json_encode(array(
                                'status' => 'ready to play'
                            )));
                        }


                        echo "\nFatto";
                    } else {
                        echo "\nSto creando un nuovo game";
                        $this->pvpInProgressModel->setConnection($from->resourceId);
                        echo $from->resourceId;
                        $this->pvpInProgressModel->createGame();
                        echo "ook";
                        $from->send(json_encode(array(
                            "status" => "Waiting for a second player"
                        )));
                    }
                }

                break;
        }
    }
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
}
?>