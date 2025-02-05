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
            $query = "INSERT INTO $this->message (user_id, receiver_id, message, chat_id)
                        VALUES (:user_id, :receiver_id, :message, :chat_id)";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':chat_id', $chat_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Chat message insertion error: " . $e->getMessage());
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
            error_log("Error fetching messages: ". $e->getMessage());
            return [];
        }
    }

    public function deleteMessage($id, $user_id): bool
    {
        try{
            $query = "DELETE FROM $this->message WHERE id = :message_id AND user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':message_id', $id);
            $stmt->bindParam(':user_id', $user_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting message: ". $e->getMessage());
            return false;
        }
    }



    public function changeLatestMessage($chat_id, $message): bool {
        try{
            $query = "UPDATE $this->table SET latest_message = :latest_message WHERE id = :chat_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':latest_message', $message);
            $stmt->bindParam(':chat_id', $chat_id);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error changing latest message: ". $e->getMessage());
            return false;
        }
    }

    public function ensureChatExists($user_id, $receiver_id, $message): ?int {
        try {
            // Check if a chat already exists between the two users
            $query = "SELECT id FROM $this->table 
            WHERE (user_id = :user_id1 AND receiver_id = :receiver_id1) 
            OR (user_id = :receiver_id2 AND receiver_id = :user_id2)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id1', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':receiver_id1', $receiver_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id2', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':receiver_id2', $receiver_id, PDO::PARAM_INT);
            $stmt->execute();

            $chatId = $stmt->fetchColumn();

            // If no chat exists, create a new one
            if (!$chatId) {
                $insertQuery = "INSERT INTO $this->table (user_id, receiver_id, latest_message) 
                                VALUES (:user_id, :receiver_id, :latest_message)";
                $insertStmt = $this->db->prepare($insertQuery);
                $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':latest_message', $message);
                $insertStmt->execute();

                $chatId = $this->db->lastInsertId();
            }
            return (int)$chatId;
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            return null;
        }
    }

}