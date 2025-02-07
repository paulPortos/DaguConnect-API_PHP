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
            $this->update_updatedAt($chat_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Chat message insertion error: " . $e->getMessage());
            return false;
        }
    }

    public function getChats($user_id): array{
        try{
            $user2_id = $user_id;
            $query = "SELECT * FROM $this->table 
            WHERE user1_id = :user1_id OR user2_id = :user2_id
            GROUP BY LEAST(user1_id, user2_id), GREATEST(user1_id, user2_id)
            ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user1_id', $user_id);
            $stmt->bindParam(':user2_id', $user2_id);
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

    public function markAsReadChat($chat_id, $user_id): bool {
        try {
            $query = "UPDATE $this->table 
                  SET last_read_by_user_id = :user_id
                  WHERE id = :chat_id
                  AND last_read_by_user_id != :user_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error marking chat as read: " . $e->getMessage());
            return false;
        }
    }

    public function markAsReadMessage($chat_id, $user_id): bool{
        try {
            $query = "UPDATE messages 
                  SET is_read = 1 
                  WHERE chat_id = :chat_id 
                  AND ((user1_id = :user_idx) OR (user2_id = :user_idy))";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_idx', $user_id);
            $stmt->bindParam(':user_idy', $user_id);
            $stmt->bindParam(':chat_id', $chat_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error marking message as read: ". $e->getMessage());
            return false;
        }
    }

    public function changeLatestMessage($chat_id, $message, $user_id): bool {
        try {
            $query = "UPDATE $this->table 
                  SET latest_message = :latest_message,
                      last_sender_id = :last_sender_id,
                      is_read = CASE 
                          WHEN user1_id = :last_sender_id THEN 0 
                          WHEN user2_id = :last_sender_id THEN 0 
                          ELSE is_read 
                      END
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

    public function getFullName($user_id)
    {
        $query = "SELECT CONCAT(first_name, ' ', last_name) AS fullname FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProfilePicture($user_id){
        try {
            $query = "SELECT is_client FROM users WHERE id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $isClient = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($isClient['is_client'] == 0) {
                $query = "SELECT profile_pic FROM tradesman_resume WHERE user_id = :user_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC)['profile_pic'];
            } else {
                $query = "SELECT profile_pic FROM users WHERE user_id = :user_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC)['profile_pic'];
            }
        } catch (PDOException $e){
            error_log("Error getting profile picture: ". $e->getMessage());
            return null;
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
            WHERE (user_id = :user1_id AND receiver_id = :receiver_id1) 
            OR (user_id = :receiver_id2 AND receiver_id = :user2_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user1_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':receiver_id1', $receiver_id, PDO::PARAM_INT);
            $stmt->bindParam(':user2_id', $user_id, PDO::PARAM_INT);
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