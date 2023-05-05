<?php

use Dotenv\Dotenv;
use App\web_socket\Chess;
use Ratchet\Http\HttpServer;

use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;


require_once '../../vendor/autoload.php';


$dotenv = Dotenv::createImmutable("C:/xampp/htdocs/OscarChessPlatform");
$dotenv->load();

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

