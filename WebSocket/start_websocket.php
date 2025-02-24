<?php

use Workerman\Worker;
use DaguConnect\WebSocket\WebSocketServer;

require 'vendor/autoload.php';
require 'WebSocketServer.php';

// WebSocket server configuration
$host = $_ENV['IP_ADDRESS'] ?? '0.0.0.0';
$port = 8000;

$ws_server = new Worker("websocket://$host:$port");

// Attach WebSocketServer as the handler for connections
$ws_server->onConnect = function ($connection) {
    WebSocketServer::onOpen($connection);
};

$ws_server->onMessage = function ($connection, $message) {
    WebSocketServer::onMessage($connection, $message);
};

$ws_server->onClose = function ($connection) {
    WebSocketServer::onClose($connection);
};

$ws_server->onError = function ($connection, $code, $message) {
    WebSocketServer::onError($connection, $code, $message);
};

echo "WebSocket server started on ws://$host:$port\n";

// Run the server
Worker::runAll();
