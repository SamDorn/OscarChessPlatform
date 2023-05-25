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
use Throwable;

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
        try {
            $data =  json_decode($msg); //decode the message
            $request = htmlspecialchars($data->request);
            @$token = htmlspecialchars($data->jwt);

            try {
                @$idUser = Jwt::getPayload($token)['user_id'];
            } catch (Throwable $th) {
                $idUser = null;
            }

            if ($token) {
                $this->userModel->setId($idUser);
                $this->updateUser($from);
                $this->userModel->setLast_ConnectionId($from->resourceId);
                $this->userModel->setStatus("online");
            }
            switch ($request) {
                case 'home':

                    break;

                case 'vsComputer':
                    $fen = '"' . htmlspecialchars($data->fen) . '"';
                    $fileName = htmlspecialchars($data->username) . ".txt";
                    $skill = htmlspecialchars($data->skill);
                    if (!isset($idUser)) {
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
                    $this->handlePvp($data, $from, $idUser);
                    break;

                default:

                    break;
            }
        } catch (Throwable $e) {
            $from->send(json_encode(array(
                'error' => $e->getMessage()
            )));
        }
    }



    /**
     * Updates the last connection ID of a user and disconnects any other device that may
     * be connected with the same account.
     * 
     * @param ConnectionInterface Object representing the current client connection that
     * triggered the `updateUser` function. It is used to get the current client's resource ID, which
     * is then compared with the last connection ID stored in the database for the user. If they are
     * different, it means the user has made a login from a different device. If it is so a message will be
     * sent triggering the disconnection of the user from that device
     * 
     * @return void 
     */
    private function updateUser(ConnectionInterface $from): void //update the last_ConnectionId and if the user makes a login from another device it disconnects the other
    {
        $connectionId = $this->userModel->getLast_ConnectionId(); //get the last connection id

        if ($connectionId == $from->resourceId) //if it is the same nothing happens
            return;
        /**
         * Check every client's connection id and if it matches a message containing 'disconnect'
         * will be sent to that user
         */
        foreach ($this->clients as $client) {
            if ($client->resourceId === $connectionId) {
                $client->send(json_encode(array(
                    'disconnect' => 'disconnect'
                )));
                break;
            }
        }
        $this->userModel->setLast_ConnectionId($from->resourceId); //set the last_connectionId to the new one
        $this->userModel->updateConnectionId(); //updates the new connectionId
    }
    private function handlePvp(mixed $data, ConnectionInterface $from, string $idUser): void
    {
        $this->gamesPvpInProgressModel->setIdPlayer($idUser); //sets the id of the player playing that game

        /**
         * Every time a player sends a message with a request 'vsPlayer' it sends along with it
         * a state. Every time a player refresh a page or lands on a page the first thing that 
         * is sent is 'NEW GAME'. For updating the game 'UPDATE'. 'FINISH' when a game is over
         * and delete when a user is still in the loading screen and decides to go back to the menu
         */
        switch ($data->state) {
            case 'new game':
                /**
                 * Needs to check if the user is already in an existing game.
                 */
                if ($this->gamesPvpInProgressModel->isUserInGame()) {
                    $idGame = $this->gamesPvpInProgressModel->getIdFromUser(); //gets the id of the game he was playing in 
                    $this->gamesPvpInProgressModel->setId($idGame);

                    /**
                     * Sends a message containg all the information about the game.
                     */
                    $from->send(json_encode(array(
                        'id_game' => $idGame,
                        'color' => $this->gamesPvpInProgressModel->getColorById(),
                        'pgn' => $this->gamesPvpInProgressModel->getPgnFromId() ?? null,
                        'status' => 'ready to play',
                        'last_move' => $this->gamesPvpInProgressModel->getLastMove(),
                        'id_opponent' => $this->gamesPvpInProgressModel->getOtherPlayer()
                    )));
                }
                /**
                 * If he wasn't in an existing game there could be a player waiting for someone to join
                 */
                else {
                    if ($this->gamesPvpInProgressModel->getGamesNoSecondPlayer()) { //checks if there are any games with second player NULL
                        $this->gamesPvpInProgressModel->insertExistingGame(); //insert the player in that game

                        /**
                         * Sends a message containg all the information about the game.
                         */
                        $from->send(json_encode(array(
                            'id_game' => $this->gamesPvpInProgressModel->getId(),
                            'color' => $this->gamesPvpInProgressModel->getColorById(),
                            'status' => 'ready to play',
                            'id_opponent' => $this->gamesPvpInProgressModel->getOtherPlayer()
                        )));
                        /**
                         * We also need to teel the other player that a second player was found
                         */
                        $idUserOpponent = $this->gamesPvpInProgressModel->getOtherPlayer();
                        $this->userModel->setId($idUserOpponent);
                        $opponentConnectionId = $this->userModel->getLast_ConnectionId();
                        foreach ($this->clients as $client) {
                            if ($client->resourceId === $opponentConnectionId) {
                                $client->send(json_encode(array(
                                    'id_game' => $this->gamesPvpInProgressModel->getId(),
                                    'status' => 'ready to play',
                                    'id_opponent' => $idUser,
                                )));
                                break;
                            }
                        }
                    }
                    /**
                     * If there aren't any player waiting it creates a new game.
                     */
                    else {
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
                $this->gamesPvpInProgressModel->updatePgn(); //updates the pgn column 
                $this->gamesPvpInProgressModel->updateLastMove(); //updates the lastMove column 
                $idUserOpponent = $this->gamesPvpInProgressModel->getOtherPlayer(); //get the otherPlayer id
                $this->userModel->setId($idUserOpponent);
                $opponentConnectionId = $this->userModel->getLast_ConnectionId(); //get the other player connectionId

                /**
                 * Search for that player and sends the updated chessboard.
                 */
                foreach ($this->clients as $client) {
                    if ($client->resourceId === $opponentConnectionId) {
                        $client->send(json_encode(array(
                            'pgn' => $this->gamesPvpInProgressModel->getPgn(),
                            'id_game' => $data->id,
                            'move' => $data->move
                        )));
                        break;
                    }
                }
                break;

            case 'finish':
                /**
                 * If the game was a draw the winner will be set to null otherwise
                 * to the id of the winner
                 */
                $msg = $data->msg;
                $this->gamesPvpInProgressModel->setId($data->id);
                $idUserOpponent = $this->gamesPvpInProgressModel->getOtherPlayer();


                $this->userModel->setId($idUserOpponent);
                $opponentConnectionId = $this->userModel->getLast_ConnectionId();
                

                $this->gamesPvpInProgressModel->endGame(); //deletes the game from the table
                if ($msg === 'win') {
                    $this->finishedGamesModel->setId($data->id);
                    $this->finishedGamesModel->setIdPlayer($idUser);
                    $this->finishedGamesModel->setWinner(); //set the winner of the games table
                } elseif ($msg === 'lost') {
                    $this->finishedGamesModel->setId($data->id);
                    $this->finishedGamesModel->setIdPlayer($idUserOpponent);
                    $this->finishedGamesModel->setWinner(); //set the winner of the games table
                    foreach ($this->clients as $client) {
                        if ($client->resourceId === $opponentConnectionId) {
                            $client->send(json_encode(array(
                                'id_game' => $data->id,
                                'state' => "Opponent resigned"
                            )));
                            break;
                        }
                    }
                } else {
                    foreach ($this->clients as $client) {
                        if ($client->resourceId === $opponentConnectionId) {
                            $client->send(json_encode(array(
                                'id_game' => $data->id,
                                'state' => "Opponent accepted the draw"
                            )));
                            break;
                        }
                    }
                }

                break;
            case 'request-draw':
                $this->gamesPvpInProgressModel->setId($data->id);
                $idUserOpponent = $this->gamesPvpInProgressModel->getOtherPlayer();

                $this->userModel->setId($idUserOpponent);
                $opponentConnectionId = $this->userModel->getLast_ConnectionId();
                foreach ($this->clients as $client) {
                    if ($client->resourceId === $opponentConnectionId) {
                        $client->send(json_encode(array(
                            'id_game' => $data->id,
                            'state' => "Opponent requested draw"
                        )));
                        break;
                    }
                }
                break;

                /**
                 * Will happen when a user who is waiting for a game to start and clicks back to menu.
                 * A trigger is set to listen to a delete of a game
                 * and when it happens it will takes some information and put it in the games table
                 */
            case 'delete':
                $this->gamesPvpInProgressModel->deleteGameNoSecondPlayer();
                break;
        }
    }

    /**
     * When the connection is closed it detach the element from the array and set the status
     * of the player to offline. 
     *
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn); //removes from $clients
        try {
            $this->userModel->setLast_ConnectionId($conn->resourceId); //set the connection id
            $this->userModel->setStatus("offline"); //set the status to offline
        } catch (Throwable $e) {
        }


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
            //$this->gamesPveInProgressModel->setIdPlayer($idUser);
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
}
