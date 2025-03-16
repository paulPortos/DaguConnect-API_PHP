<?php

namespace DaguConnect\WebSocket;

use DaguConnect\Includes\config;
use Exception;
use PDO;
use PDOException;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;

class WebSocketServer {
    private array $offlineMessages = [];
    private PDO $db;
    private string $clientsFile = __DIR__ . '/clients.json';
    private Worker $worker;
    protected string $table = 'chats';
    protected string $message = 'messages';

    public function __construct(config $config, Worker $worker) {
        $this->db = $config->db;
        $this->worker = $worker;
        if (!file_exists($this->clientsFile)) {
            file_put_contents($this->clientsFile, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    public function onOpen(TcpConnection $connection): void {
        echo "New connection ($connection->id)\n";
    }

    private function saveClientToJson(int $connection_id, ?string $user_id): void {
        $clientsData = $this->loadClientsFromJson();
        $clientsData[$user_id] = [
            'user_id' => $user_id,
            'connection_id' => $connection_id
        ];
        file_put_contents($this->clientsFile, json_encode($clientsData, JSON_PRETTY_PRINT));
    }

    private function removeClientFromJson(int $connection_id): void {
        $clientsData = $this->loadClientsFromJson();
        foreach ($clientsData as $user_id => $client) {
            if ($client['connection_id'] === $connection_id) {
                unset($clientsData[$user_id]);
                break;
            }
        }
        file_put_contents($this->clientsFile, json_encode($clientsData, JSON_PRETTY_PRINT));
    }

    private function loadClientsFromJson(): array {
        return json_decode(file_get_contents($this->clientsFile), true) ?: [];
    }

    public function onMessage(TcpConnection $connection, $msg): void {
        echo "Received message: $msg\n";
        $data = json_decode($msg, true);

        if (!isset($data['type'])) {
            echo "Invalid message format. Ignoring.\n";
            $connection->send(json_encode(['error' => 'Invalid message format']));
            return;
        }

        // Handle authentication
        if ($data['type'] === 'auth' && isset($data['user_id'])) {
            $user_id = $data['user_id'];
            echo "Authenticating user: $user_id\n";

            // Remove any previous connection for the user
            $clientsData = $this->loadClientsFromJson();
            foreach ($clientsData as $existingUserId => $client) {
                if ($existingUserId === $user_id) {
                    unset($clientsData[$existingUserId]);
                }
            }
            file_put_contents($this->clientsFile, json_encode($clientsData, JSON_PRETTY_PRINT));

            // Store new connection
            $this->saveClientToJson($connection->id, $user_id);

            // Persist to database
            $stmt = $this->db->prepare("
            INSERT INTO websocket_clients (user_id, connection_id) 
            VALUES (:user_id, :connection_id) 
            ON DUPLICATE KEY UPDATE connection_id = :connection_id_update
            ");
            $stmt->execute([
                ':user_id' => $user_id,
                ':connection_id' => $connection->id,
                ':connection_id_update' => $connection->id
            ]);

            echo "User $user_id authenticated and stored.\n";

            // Send queued messages
            if (isset($this->offlineMessages[$user_id])) {
                foreach ($this->offlineMessages[$user_id] as $queuedMessage) {
                    $connection->send($queuedMessage);
                }
                unset($this->offlineMessages[$user_id]);
            }
            $connection->send(json_encode(['status' => 'authenticated', 'user_id' => $user_id]));
            return;
        }

        // Handle regular message
        if ($data['type'] === 'message' && isset($data['user_id']) && isset($data['receiver_id']) && isset($data['message'])) {
            $user_id = $data['user_id'];
            $receiver_id = $data['receiver_id'];
            $message = $data['message'];

            if (empty($user_id) || empty($receiver_id)) {
                $connection->send(json_encode(['error' => 'Invalid user ID or receiver ID']));
                return;
            }

            if (empty($message) || trim($message) === "") {
                $connection->send(json_encode(['error' => 'Cannot send empty message']));
                return;
            }

            $message_id = $this->addToDatabaseMessage($user_id, $receiver_id, $message);

            date_default_timezone_set('UTC');
            $messageData = [
                'id' => $message_id,
                'user_id' => $user_id,
                'receiver_id' => $receiver_id,
                'message' => $message,
                'is_read' => 0,
                'created_at' => gmdate('Y-m-d H:i:s')
            ];

            $isSent = $this->sendMessage($receiver_id, $messageData);

            $response = $isSent
                ? ['status' => 'sent', 'message' => 'Message sent successfully in real-time']
                : ['status' => 'queued', 'message' => 'Message stored, recipient offline'];
            $connection->send(json_encode($response));
            return;
        }

        if ($data['type'] === 'notification' && isset($data['client']) && $data['client'] === true &&
            isset($data['resume_id']) && isset($data['notification_title']) && isset($data['notificationType']) && isset($data['message'])) {
            $resume_id = $data['resume_id'];
            $tradesman_id = $this->getTradesmanIdFromResume($resume_id);

            if (!$tradesman_id) {
                $connection->send(json_encode(['error' => 'Tradesman not found for resume ID']));
                return;
            }

            $notificationData = [
                'resume_id' => $resume_id,
                'notification_title' => $data['notification_title'],
                'notification_type' => $data['notificationType'],
                'message' => $data['message'],
                'created_at' => gmdate('Y-m-d H:i:s')
            ];

            if ($this->saveNotificationToDatabase($tradesman_id, $notificationData)) {
                echo "Notification saved to database\n";
            } else {
                echo "Failed to save notification to database\n";
                return;
            }

            $notified = $this->sendNotification($tradesman_id, $notificationData);
            $response = $notified
                ? ['status' => 'sent', 'message' => 'Notification sent to tradesman']
                : ['status' => 'queued', 'message' => 'Notification queued, tradesman offline'];
            $connection->send(json_encode($response));
            return;
        }

        // Handle notification to client
        if ($data['type'] === 'notification' && isset($data['tradesman']) && $data['tradesman'] === true &&
            isset($data['client_id']) && isset($data['notification_title']) && isset($data['notificationType']) && isset($data['message'])) {
            $client_id = $data['client_id'];
            $notificationData = [
                'client_id' => $client_id,
                'notification_title' => $data['notification_title'],
                'notification_type' => $data['notificationType'],
                'message' => $data['message'],
                'created_at' => gmdate('Y-m-d H:i:s')
            ];

            if ($this->saveNotificationToDatabase($client_id, $notificationData)) {
                echo "Notification saved to database\n";
            } else {
                echo "Failed to save notification to database\n";
                return;
            }

            $notified = $this->sendNotification($client_id, $notificationData);
            $response = $notified
                ? ['status' => 'sent', 'message' => 'Notification sent to client']
                : ['status' => 'queued', 'message' => 'Notification queued, client offline'];
            $connection->send(json_encode($response));
            return;
        }

        // If no matching type is found
        echo "Unknown message type: {$data['type']}\n";
        $connection->send(json_encode(['error' => 'Unknown message type']));
    }

    public function onClose(TcpConnection $connection): void {
        echo "Closing connection (id: $connection->id)\n";
        $this->removeClientFromJson($connection->id);

        try {
            $stmt = $this->db->prepare("DELETE FROM websocket_clients WHERE connection_id = :connection_id");
            $stmt->execute([':connection_id' => $connection->id]);
        } catch (Exception $e) {
            error_log("Error removing WebSocket connection: " . $e->getMessage());
        }
    }

    public function onError(TcpConnection $connection, int $code, string $message): void {
        echo "Error [$code]: $message\n";
        $connection->close();
    }

    public function sendMessage(int $receiver_id, array $data): bool {
        $clientsData = $this->loadClientsFromJson();
        $messageJson = json_encode(['type' => 'message', 'data' => $data]);
        $onlineUsers = $this->getUserIds();

        if (!in_array($receiver_id, $onlineUsers)) {
            echo "User $receiver_id is offline; queuing message.\n";
            $this->offlineMessages[$receiver_id][] = $messageJson;
            return false;
        }

        if (!isset($clientsData[$receiver_id]['connection_id'])) {
            echo "No connection ID found in JSON for user $receiver_id\n";
            return false;
        }
        $json_connection_id = (int) $clientsData[$receiver_id]['connection_id'];
        echo "Fetched connection_id for user $receiver_id from JSON: $json_connection_id\n";

        $stmt = $this->db->prepare("SELECT connection_id FROM websocket_clients WHERE user_id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => $receiver_id]);
        $db_connection_id = (int) $stmt->fetchColumn();

        if (!$db_connection_id || $json_connection_id !== $db_connection_id) {
            echo "Connection ID mismatch or not found in database\n";
            return false;
        }

        if (!isset($this->worker->connections[$db_connection_id])) {
            echo "WebSocket connection for ID $db_connection_id not found.\n";
            return false;
        }

        $websocket_connection = $this->worker->connections[$db_connection_id];
        try {
            $websocket_connection->send($messageJson);
            echo "Message sent to user $receiver_id via WebSocket\n";
            echo "Message: $messageJson\n \n \n";
            return true;
        } catch (Exception $e) {
            echo "Failed to send message: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public function sendNotification(string $receiver_id, array $data): bool {
        $clientsData = $this->loadClientsFromJson();
        $notificationJson = json_encode(['type' => 'notification', 'data' => $data]);
        $onlineUsers = $this->getUserIds();

        if (!in_array($receiver_id, $onlineUsers)) {
            echo "User $receiver_id is offline; queuing notification.\n";
            $this->offlineMessages[$receiver_id][] = $notificationJson;
            return false;
        }

        if (!isset($clientsData[$receiver_id]['connection_id'])) {
            echo "No connection ID found in JSON for user $receiver_id\n";
            return false;
        }
        $json_connection_id = (int) $clientsData[$receiver_id]['connection_id'];
        echo "Fetched connection_id for user $receiver_id from JSON: $json_connection_id\n";

        $stmt = $this->db->prepare("SELECT connection_id FROM websocket_clients WHERE user_id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => $receiver_id]);
        $db_connection_id = (int) $stmt->fetchColumn();

        if (!$db_connection_id || $json_connection_id !== $db_connection_id) {
            echo "Connection ID mismatch or not found in database\n";
            return false;
        }

        if (!isset($this->worker->connections[$db_connection_id])) {
            echo "WebSocket connection for ID $db_connection_id not found.\n";
            return false;
        }

        $websocket_connection = $this->worker->connections[$db_connection_id];
        try {
            $websocket_connection->send($notificationJson);
            echo "Notification sent to user $receiver_id via WebSocket\n";
            echo "Notification: $notificationJson\n \n \n";
            return true;
        } catch (Exception $e) {
            echo "Failed to send notification: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public function getUserIds(): array {
        $stmt = $this->db->query("SELECT user_id FROM websocket_clients");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function addToDatabaseMessage(int $user_id, int $receiver_id, string $message): int {
        $chat_id = $this->ensureChatExists($user_id, $receiver_id, $message);
        $this->changeLatestMessage($chat_id, $message, $user_id);
        $this->sendMessages($user_id, $receiver_id, $message, $chat_id);
        return $this->getLastMessageId();
    }

    public function sendMessages($user_id, $receiver_id, $message, $chat_id):bool{
        try{
            $query = "INSERT INTO $this->message (user_id, receiver_id, chat_id, message)
                        VALUES (:user_id, :receiver_id, :chat_id, :message)";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':chat_id', $chat_id);
            $this->update_updatedAt($chat_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Chat message insertion error: " . $e->getMessage());
            return false;
        }
    }

    public function update_updatedAt($chat_id): bool
    {
        try {
            $query = "UPDATE $this->table SET updated_at = CURRENT_TIMESTAMP WHERE id = :chat_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e){
            error_log("Error updating updatedAt: ". $e->getMessage());
            return false;
        }
    }

    public function ensureChatExists($user_id, $receiver_id, $message): ?int {
        try {
            // Check if a chat already exists between the two users
            $query = "SELECT id FROM $this->table 
            WHERE (user1_id = :user1_id AND user2_id = :receiver_id1) 
            OR (user1_id = :receiver_id2 AND user2_id = :user2_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user1_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':receiver_id1', $receiver_id, PDO::PARAM_INT);
            $stmt->bindParam(':user2_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':receiver_id2', $receiver_id, PDO::PARAM_INT);
            $stmt->execute();

            $chatId = $stmt->fetchColumn();

            // If no chat exists, create a new one
            if (!$chatId) {
                $insertQuery = "INSERT INTO $this->table (user1_id, user2_id, latest_message) 
                                VALUES (:user1_id, :user2_id, :latest_message)";
                $insertStmt = $this->db->prepare($insertQuery);
                $insertStmt->bindParam(':user1_id', $user_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':user2_id', $receiver_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':latest_message', $message);
                $insertStmt->execute();

                $chatId = $this->db->lastInsertId();
            }
            return (int)$chatId;
        } catch (PDOException $e) {
            error_log("Error ensuring chat exists: " . $e->getMessage());
            return null;
        }
    }

    public function changeLatestMessage($chat_id, $message, $user_id): bool {
        try {
            $query = "UPDATE $this->table 
                  SET latest_message = :latest_message,
                      last_sender_id = :last_sender_id
                      WHERE id = :chat_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':latest_message', $message);
            $stmt->bindParam(':last_sender_id', $user_id);
            $stmt->bindParam(':chat_id', $chat_id);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error changing latest message: " . $e->getMessage());
            return false;
        }
    }

    public function getLastMessageId(): ?int
    {
        try {
            $query = "SELECT id FROM messages ORDER BY id DESC LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? (int) $result['id'] : null;
        } catch (PDOException $e) {
            error_log("Error fetching last message ID: " . $e->getMessage());
            return null;
        }
    }

    public function getTradesmanIdFromResume($resume_id): ?int {
        try {
            $query = "SELECT user_id FROM tradesman_resume WHERE id = :resume_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':resume_id', $resume_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error fetching tradesman ID: " . $e->getMessage());
            return null;
        }
    }

    public function saveNotificationToDatabase(int $receiver_id, array $data): bool {
        try {
            $query = "INSERT INTO notification (user_id, notification_title, notification_type, message) 
                      VALUES (:user_id, :notification_title, :notification_type, :message)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $receiver_id, PDO::PARAM_INT);
            $stmt->bindParam(':notification_title', $data['notification_title']);
            $stmt->bindParam(':notification_type', $data['notification_type']);
            $stmt->bindParam(':message', $data['message']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving notification: " . $e->getMessage());
            return false;
        }
    }

    public function getUserIdFromClientProfile($clientProfile_id){
        try {
            $query = "SELECT user_id FROM client_profile WHERE id = :clientProfile_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':clientProfile_id', $clientProfile_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error fetching user ID from client profile: " . $e->getMessage());
            return null;
        }
    }
}