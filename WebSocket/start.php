<?php

use DaguConnect\Services\Env;
use Workerman\Worker;
use DaguConnect\WebSocket\WebSocketServer;

require 'vendor/autoload.php';
require 'WebSocketServer.php';
Env::load();

// WebSocket server configuration
$host = $_ENV['IP_ADDRESS'] ?? '0.0.0.0';
$port = 8080;

$ws_server = new Worker("websocket://$host:$port");
$webSocketHandler = new WebSocketServer(new DaguConnect\Includes\config(), $ws_server);

// Event handlers
$ws_server->onConnect = function ($connection) use ($webSocketHandler) {
    $webSocketHandler->onOpen($connection);
    echo "New connection established: $connection->id\n";
};

$ws_server->onMessage = function ($connection, $message) use ($webSocketHandler) {
    $webSocketHandler->onMessage($connection, $message);
};

$ws_server->onClose = function ($connection) use ($webSocketHandler) {
    $webSocketHandler->onClose($connection);
};

$ws_server->onError = function ($connection, $code, $message) use ($webSocketHandler) {
    $webSocketHandler->onError($connection, $code, $message);
};

//echo "WebSocket server started on ws://$host:$port with Workerman\n";

// Run the server
Worker::runAll();