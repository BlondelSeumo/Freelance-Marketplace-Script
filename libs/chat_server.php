<?php
include_once('../sockets/Chat.php');
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;

    require '../vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        3000
    );
    $server->run();
    
?>