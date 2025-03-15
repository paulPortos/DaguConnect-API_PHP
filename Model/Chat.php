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

    public function getChats(int $user_id, int $page, int $limit): array {
        $offset = ($page - 1) * $limit;

        try {
            // Corrected count query
            $user_id2 = $user_id;
            $countStmt = $this->db->prepare("SELECT COUNT(DISTINCT CONCAT(LEAST(user1_id, user2_id), '_', GREATEST(user1_id, user2_id))) AS total 
                                         FROM $this->table 
                                         WHERE user1_id = :user1_id OR user2_id = :user2_id");
            $countStmt->bindParam(':user1_id', $user_id, PDO::PARAM_INT);
            $countStmt->bindParam(':user2_id', $user_id2, PDO::PARAM_INT);
            $countStmt->execute();
            $totalChats = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Fetch paginated chats
            $query = "SELECT * FROM $this->table 
                  WHERE user1_id = :user1_id OR user2_id = :user2_id
                  ORDER BY created_at DESC
                  LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user1_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':user2_id', $user_id2, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT); // Use bindValue
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate total pages
            $totalPages = max(1, ceil($totalChats / $limit));

            return [
                'chats' => $chats,
                'current_page' => $page,
                'total_pages' => $totalPages
            ];
        } catch (PDOException $e) {
            error_log("Error fetching chats: " . $e->getMessage());
            return [];
        }
    }

    public function getMessages(int $user_id, int $chat_id, int $page, int $limit): array {
        try {
            // Get total number of messages
            $countStmt = $this->db->prepare("SELECT COUNT(*) as total FROM $this->message WHERE chat_id = :chat_id");
            $countStmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
            $countStmt->execute();
            $totalMessages = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total']; // Cast to int

            // Calculate total pages
            $totalPages = max(1, ceil($totalMessages / $limit));

            // Ensure the page is valid (start from last page)
            $page = min($page, $totalPages);

            // Adjust offset to get the **latest messages first**
            $offset = max(0, ($totalPages - $page) * $limit);

            // Fetch messages in descending order (newest first)
            $query = "SELECT * FROM $this->message 
                  WHERE chat_id = :chat_id
                  ORDER BY created_at DESC
                  LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'messages' => array_reverse($messages), // Reverse to maintain ASC order in UI
                'current_page' => $page,
                'total_pages' => $totalPages
            ];
        } catch (PDOException $e) {
            error_log("Error fetching messages: " . $e->getMessage());
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

    public function markAsReadChat(int $chat_id, int $user_id): bool {
        try {
            $query = "UPDATE $this->table
                  SET last_read_by_user_id = :user_id
                  WHERE id = :id
                  AND last_read_by_user_id != :user_id_duplicate";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $chat_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id_duplicate', $user_id, PDO::PARAM_INT); // Bind the second occurrence


            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error marking chat as read: " . $e->getMessage());
            return false;
        }
    }

    public function markAsReadMessage($chat_id, $user_id): bool{
        try {
            $query = "UPDATE $this->table
                  SET is_read = 1 
                  WHERE id = :chat_id 
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

    public function getFullName($user_id)
    {
        $query = "SELECT CONCAT(first_name, ' ', last_name) AS fullname FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProfilePicture($user_id) {
        try {
            // Check if the user is a client
            $query = "SELECT is_client FROM users WHERE id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $isClient = $stmt->fetch(PDO::FETCH_ASSOC);

            // If no user is found, return null
            if ($isClient === false) {
                error_log("No user found with user_id: " . $user_id);
                return null;
            }

            if ($isClient['is_client'] == 0) {
                // Fetch profile picture from tradesman_resume for non-clients
                $query = "SELECT profile_pic FROM tradesman_resume WHERE user_id = :user_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Check if result exists before accessing it
                return $result !== false ? $result['profile_pic'] : null;
            } else {
                $query = "SELECT profile_picture FROM client_profile WHERE user_id = :user_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Check if result exists before accessing it
                return $result !== false ? $result['profile_picture'] : null;
            }
        } catch (PDOException $e) {
            error_log("Error getting profile picture: " . $e->getMessage());
            return null;
        }
    }

}