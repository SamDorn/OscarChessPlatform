<?php

/**
 * TO REDESIGN
 */

namespace App\web_socket;

require_once '../../vendor/autoload.php';

use App\utilities\Jwt;
use App\models\UserModel;
use Ratchet\ConnectionInterface;
use App\models\FinishedGamesModel;
use Ratchet\MessageComponentInterface;
use App\models\GamesPveInProgressModel;
use App\models\GamesPvpInProgressModel;

class Chess implements MessageComponentInterface
{
    private $clients;
    private UserModel $userModel;
    private GamesPvpInProgressModel $gamesPvpInProgressModel;
    private GamesPveInProgressModel $gamesPveInProgressModel;
    private FinishedGamesModel $finishedGamesModel;
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->userModel = new UserModel();
        $this->gamesPvpInProgressModel = new GamesPvpInProgressModel();
        $this->gamesPveInProgressModel = new GamesPveInProgressModel();
        $this->finishedGamesModel = new FinishedGamesModel();
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


        $data =  json_decode($msg); //decode the message
        $request = htmlspecialchars($data->request);
        $token = htmlspecialchars($data->jwt);
        if ($token) {
            $this->userModel->setId(Jwt::getPayload($token)['user_id']);
            $this->updateUser($from);
            $this->userModel->setLast_ConnectionId($from->resourceId);
            $this->userModel->setStatus("online");
        }
        switch ($request) {
            case 'home':

                break;

            case 'vsComputer':
                $fen = '"' . $data->fen . '"';
                $fileName = "$data->username.txt";
                $skill = $data->skill;
                if (!isset($token)) {
                    $move = $this->getMove($fileName, $fen, $skill);

                    $from->send(json_encode(array(
                        'move' => $move,
                        'best_move' => true
                    )));
                } else {
                    $move = $this->getMove($fileName, $fen, $skill);

                    $from->send(json_encode(array(
                        'move' => $move
                    )));
                }



                //$this->handlePve($data, $from, $fileName, $fen, $skill);

                break;

            case 'vsPlayer':
                $this->handlePvp($data, $from);
                break;

            default:

                break;
        }
    }


    /**
     * TO REDISIGN
     *
     * @param [type] $data
     * @param [type] $from
     * @param [type] $fileName
     * @param [type] $fen
     * @param [type] $skill
     * @return void
     */
    private function handlePve($data, $from, $fileName, $fen, $skill): void
    {
        if ($data->jwt) {
            $this->gamesPveInProgressModel->setIdPlayer(Jwt::getPayload($data->jwt)['user_id']);
            $this->gamesPveInProgressModel->setSkill($data->skill);
            switch ($data->state) {
                case 'newGame':
                    if ($this->gamesPveInProgressModel->isUserInGame()) {
                        $from->send(json_encode(array(
                            'pgn' => $this->gamesPveInProgressModel->getPgn(),
                            'move' => $this->gamesPveInProgressModel->getLastMove(),
                            'color' => $this->gamesPveInProgressModel->getColor()
                        )));
                    } else {
                        $pgn = isset($data->pgn) ? $data->pgn : null;
                        if ($pgn) {
                            $this->gamesPveInProgressModel->createGame(Jwt::getPayload($data->jwt)['user_id']);
                            $from->send(json_encode(array(
                                'pgn' => $this->getMove($fileName, $fen, $skill)
                            )));
                        } else {
                            $this->gamesPveInProgressModel->createGame(null);
                        }
                    }
                    break;

                case 'update':
                    $this->gamesPveInProgressModel->setPgn($data->pgn);
                    $this->gamesPveInProgressModel->setLastMove($data->move);
                    $this->gamesPveInProgressModel->updateGame();
                    $this->getMove($fen, $fileName, $skill);
                    break;
                default:
                    # code...
                    break;
            }
        }
    }
    private function updateUser($from): void //update the last_ConnectionId and if the user makes a login from another device it disconnects the other
    {
        $connectionId = $this->userModel->getLast_ConnectionId(); //need to disconnect the device if it exist in $this->clients

        if ($connectionId == $from->resourceId)
            return;
        foreach ($this->clients as $client) {
            if ($client->resourceId === $connectionId) {
                $client->send(json_encode(array(
                    'disconnect' => 'disconnect'
                )));
                break;
            }
        }
        $this->userModel->setLast_ConnectionId($from->resourceId);
        $this->userModel->updateConnectionId();
    }
    private function handlePvp($data, $from): void
    {
        $this->gamesPvpInProgressModel->setIdPlayer(Jwt::getPayload($data->jwt)['user_id']);

        switch ($data->state) {
            case 'new game':
                if ($this->gamesPvpInProgressModel->isUserInGame()) {
                    $id = $this->gamesPvpInProgressModel->getIdFromUser();
                    $this->gamesPvpInProgressModel->setId($id);

                    $from->send(json_encode(array(
                        'id_game' => $id,
                        'color' => $this->gamesPvpInProgressModel->getColorById(),
                        'pgn' => $this->gamesPvpInProgressModel->getPgnFromId() ?? null,
                        'status' => 'ready to play',
                        'last_move' => $this->gamesPvpInProgressModel->getLastMove(),
                        'id_opponent' => $this->gamesPvpInProgressModel->getOtherPlayer()
                    )));
                } else {
                    if ($this->gamesPvpInProgressModel->getGamesNoSecondPlayer()) {
                        $this->gamesPvpInProgressModel->insertExistingGame();
                        $from->send(json_encode(array(
                            'id_game' => $this->gamesPvpInProgressModel->getId(),
                            'color' => $this->gamesPvpInProgressModel->getColorById(),
                            'status' => 'ready to play',
                            'id_opponent' => $this->gamesPvpInProgressModel->getOtherPlayer()
                        )));
                        $idUser = $this->gamesPvpInProgressModel->getOtherPlayer();
                        $this->userModel->setId($idUser);
                        $opponentConnectionId = $this->userModel->getLast_ConnectionId();
                        foreach ($this->clients as $client) {
                            if ($client->resourceId === $opponentConnectionId) {
                                $client->send(json_encode(array(
                                    'id_game' => $this->gamesPvpInProgressModel->getId(),
                                    'status' => 'ready to play',
                                    'id_opponent' => Jwt::getPayload($data->jwt)['user_id'],
                                )));
                                break;
                            }
                        }
                    } else {
                        $this->gamesPvpInProgressModel->createGame();
                        $from->send(json_encode(array(
                            'id_game' => $this->gamesPvpInProgressModel->getId(),
                            'color' => $this->gamesPvpInProgressModel->getColorById(),
                            'status' => 'waiting for a second player'
                        )));
                    }
                }
                break;
            case 'update':
                $this->gamesPvpInProgressModel->setLastMove($data->move);
                $this->gamesPvpInProgressModel->setPgn($data->pgn);
                $this->gamesPvpInProgressModel->setId($data->id);
                $this->gamesPvpInProgressModel->updatePgn();
                $this->gamesPvpInProgressModel->updateLastMove();
                $idUser = $this->gamesPvpInProgressModel->getOtherPlayer();
                $this->userModel->setId($idUser);
                $opponentConnectionId = $this->userModel->getLast_ConnectionId();
                foreach ($this->clients as $client) {
                    if ($client->resourceId === $opponentConnectionId) {
                        $client->send(json_encode(array(
                            'pgn' => $this->gamesPvpInProgressModel->getPgn(),
                            'id_game' => $data->id,
                            'move' => $data->move
                        )));
                    }
                }
                break;

            case 'finish':
                $msg = $data->msg;
                $this->gamesPvpInProgressModel->setId($data->id);
                $this->gamesPvpInProgressModel->endGame();
                if ($msg !== 'draw') {
                    $this->finishedGamesModel->setId($data->id);
                    $this->finishedGamesModel->setIdPlayer(Jwt::getPayload($data->jwt)['user_id']);
                    $this->finishedGamesModel->setWinner();
                }

                break;

            case 'delete':
                $this->gamesPvpInProgressModel->deleteGameNoSecondPlayer();
                break;
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $this->userModel->setLast_ConnectionId($conn->resourceId);
        $this->userModel->setStatus("offline");

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
    /**
     * Executes a Python script with given arguments, reads the output file, deletes
     * the file, and returns the content as a string.
     * 
     * @param string fileName The name of the file that will be generated by the Python script and used
     * to store the output (i.e. the move made by the chess engine).
     * @param string fen FEN (Forsyth–Edwards Notation) is a standard notation for describing a
     * particular board position of a chess game. It describes the position of all pieces on the board,
     * which player has the move and castling availability
     * @param string skill The skill level of the chess engine being used.
     * 
     * @return string a string that represents the move played by the engine
     */
    private function getMove(string $fileName, string $fen, string $skill): string
    {
        $fileName = escapeshellcmd($fileName);
        $fen = escapeshellcmd($fen);
        $skill = escapeshellcmd($skill);

        exec("py ../python/main.py $fileName $fen $skill"); //execute the python script

        $file = fopen("../generated_files/$fileName", "r"); //open the file created by the script

        $move = fread($file, filesize("../generated_files/$fileName")); //assign to a variable the move

        unlink("../generated_files/$fileName"); //delete the file created by the python script

        return $move; //return the move generated
    }
}
