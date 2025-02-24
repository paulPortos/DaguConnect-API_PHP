<?php

namespace DaguConnect\WebSocket;

use Workerman\Connection\TcpConnection;
use Workerman\Worker;

class WebSocketServer {
    protected static array $clients = [];

    public static function onOpen(TcpConnection $connection): void {
        self::$clients[$connection->id] = $connection;
        echo "New connection ({$connection->id})\n";
    }

    public static function onMessage(TcpConnection $connection, $msg): void {
        echo "Received message: $msg\n";

        // Broadcast the message to all connected clients
        foreach (self::$clients as $client) {
            if ($client !== $connection) {
                $client->send($msg);
            }
        }
    }

    public static function onClose(TcpConnection $connection): void {
        unset(self::$clients[$connection->id]);
        echo "Connection {$connection->id} has disconnected\n";
    }

    public static function onError(TcpConnection $connection, int $code, string $message): void {
        echo "Error [$code]: $message\n";
        $connection->close();
    }

    /**
     * Sends a WebSocket message to all connected clients.
     */
    public static function broadcastMessage(array $messageData): void {
        $messageJson = json_encode($messageData);

        foreach (self::$clients as $client) {
            $client->send($messageJson);
        }
    }
}
