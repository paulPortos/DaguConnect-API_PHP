<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;
use PDOException;

class Chat extends BaseModel
{

    protected string $table = 'chat';

    public function __construct(PDO $db){
        parent::__construct($db);
    }

    public function sendMessage(int $user_id, int $receiver_id, String $message): bool {
        try {
            $query = "INSERT INTO $this->table 
                    (sender_id, receiver_id, message, created_at) 
                    VALUES (:user_id, :receiver_id, :message, NOW())";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->bindParam(':message', $message);
            $stmt->execute();
            return true;
        } catch (PDOException $e){
            error_log("Chat message insertion error: ", $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves the latest messages for each unique receiver associated with a user, with pagination.
     *
     * This function fetches the latest message exchanged between the specified user and each of their unique receivers,
     * ordered by creation time in descending order. It supports pagination to limit the number of results returned.
     *
     * @param int $user_id The ID of the user whose messages are being retrieved.
     * @param int $page The page number for pagination (default: 1).
     * @param int $per_page The number of messages to return per page (default: 10).
     *
     * @return array An array of messages, each represented as an associative array.
     *               Returns an empty array if an error occurs during retrieval.
     */

    public function getMessages(int $user_id, int $page = 1, int $per_page = 10): array {
        try {
            // Calculate the OFFSET
            $offset = ($page - 1) * $per_page;

            // Modified query to return the first/latest message of each unique receiver
            $query = "
        SELECT m.* 
        FROM $this->table m
        INNER JOIN (
            SELECT 
                GREATEST(user_id, receiver_id) AS unique_pair, 
                LEAST(user_id, receiver_id) AS unique_pair_reverse,
                MAX(created_at) AS latest_message_time
            FROM $this->table 
            WHERE user_id = :user_id 
            GROUP BY 
                GREATEST(user_id, receiver_id), 
                LEAST(user_id, receiver_id)
        ) AS latest_messages 
        ON GREATEST(m.user_id, m.receiver_id) = latest_messages.unique_pair
        AND LEAST(m.user_id, m.receiver_id) = latest_messages.unique_pair_reverse
        AND m.created_at = latest_messages.latest_message_time
        ORDER BY m.created_at DESC
        LIMIT :per_page OFFSET :offset";

            $stmt = $this->db->prepare($query);

            // Bind the parameters
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':per_page', $per_page, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();

            // Return the results as an associative array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Chat message retrieval error: " . $e->getMessage());
            return [];
        }
    }

}