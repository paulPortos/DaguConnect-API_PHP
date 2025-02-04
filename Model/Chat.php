<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;
use PDOException;

class Chat extends BaseModel
{

    protected string $table = 'chats';
    protected string $message = 'messages';
    public function __construct(PDO $db){
        parent::__construct($db);
    }

    public function sendMessage($user_id, $receiver_id, $message, $chat_id):bool{
        try{
            $query = "INSERT INTO $this->message (user_id, reciever_id, message, chat_id, created_at)
                        VALUES (:user_id, :reciever_id, :message, :chat_id, NOW())";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':chat_id', $chat_id);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            error_log("Chat message insertion error: ", $e->getMessage());
            return false;
        }
    }

    public function getChats($user_id): array{
        try{
            $receiver_id = $user_id;
            $query = "SELECT * FROM $this->table 
            WHERE user_id = :user_id OR receiver_id = :receiver_id
            GROUP BY LEAST(user_id, receiver_id), GREATEST(user_id, receiver_id)
            ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "An error occurred: " . $e->getMessage();
            return [];
        }
    }

    public function getMessages($user_id, $chat_id): array{
        try{
            $query = "SELECT * FROM $this->message 
            WHERE (user_id = :user_id AND receiver_id = :chat_id) 
            OR (user_id = :chat_id AND receiver_id = :user_id)
            ORDER BY created_at ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':chat_id', $chat_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching messages: ", $e->getMessage());
            return [];
        }
    }

    public function ensureChatExists($user_id, $receiver_id): ?int {
        try {
            // Check if a chat already exists between the two users
            $query = "SELECT id FROM $this->table WHERE (user_id = :user_id AND reciever_id = :receiver_id) OR (user_id = :receiver_id AND reciever_id = :user_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->execute();

            // Fetch the chat ID if it exists
            $chatId = $stmt->fetchColumn();

            if (!$chatId) { // If no chat exists, create a new one
                $insertQuery = "INSERT INTO $this->table (user_id, reciever_id, created_at) VALUES (:user_id, :receiver_id, NOW())";
                $insertStmt = $this->db->prepare($insertQuery);
                $insertStmt->bindParam(':user_id', $user_id);
                $insertStmt->bindParam(':receiver_id', $receiver_id);
                $insertStmt->execute();

                $chatId = $this->db->lastInsertId();
            }

            return (int)$chatId;
        } catch (PDOException $e) {
            error_log("Chat existence check error: " . $e->getMessage());
            return null;
        }
    }

}