<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use FastVip\FastPort;
echo dirname(__FILE__);
require dirname(__FILE__)."../vendor/autoload.php";


    $server = IoServer::factory(
    new HttpServer(new WsServer(
        new FastPort())),
        8081
    );

    $server->run();

