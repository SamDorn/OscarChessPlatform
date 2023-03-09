<?php

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

use App\Chess;

require_once 'Chess.php';

require_once '../../vendor/autoload.php';


$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chess()
        )
    ),
    8080
);

$server->run();
?>

