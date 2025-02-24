<?php

use DaguConnect\Services\Env;
use DaguConnect\WebSocket\WebSocketServer;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require 'vendor/autoload.php';
require 'WebSocketServer.php';

new Env();

$host = $_ENV['IP_ADDRESS'] ?? '0.0.0.0'; // Default to 0.0.0.0 if not set
$port = 8000;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketServer()
        )
    ),
    $port // Correctly passing the port instead of a SocketServer instance
);

echo "WebSocket server started on ws://$host:$port\n";

$server->run();
