<?php

/**
 * TO REDESIGN
 */

namespace App\web_socket;

require_once '../../vendor/autoload.php';

use App\utilitis\Jwt;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Chess implements MessageComponentInterface
{
    private $clients;
    public function __construct()
    {
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
        $token = $data->jwt;
        
        echo Jwt::getPayload($token)['user_id'];


        switch ($request) {
            case 'vsComputer':
                $fen = '"' . $data->fen . '"';
                $fileName = "$data->username.txt";
                $skill = $data->skill;

                $move = $this->getMove($fileName, $fen, $skill);
                $from->send(json_encode(array(
                    'move' => $move
                )));
                break;
            case 'vsPlayer':
                


            default:

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
    /**
     * This function runs the python script found in app/python/main.py.
     * After the python script will create a file where it stores the new position
     * with the move made by the chess engine, this function will read the file
     * content inside that file and will store the new position in $new_fen which
     * will be returned. Need to implement if the user is logged in. Infact if the user
     * is logged in it needs to connect to the database to update the current game
     *
     * @param string filename
     * @param string the fen string
     * @param string skill level
     * @return string the move generated
     */
    private function getMove(string $fileName, string $fen, string $skill): string
    {
        $fileName = escapeshellcmd($fileName);
        $fen = escapeshellcmd($fen);
        $skill = escapeshellcmd($skill);

        exec("py ../python/main.py $fileName $fen $skill"); //execute the python script

        $file = fopen("../generated_files/$fileName", "r"); //open the file created by the script

        $move = fread($file, filesize("../generated_files/$fileName")); //assign to a variable the content

        unlink("../generated_files/$fileName"); //delete the file created by the python script

        return $move; //return the new position

    }
}
