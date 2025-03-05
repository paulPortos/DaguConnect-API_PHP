<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use Exception;
use PDO;

class Notification extends BaseModel
{
    protected string $table = 'notification';
    public function __construct(PDO $db){
        parent::__construct($db);
    }

    public function getNotification(int$userId, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        try {
            $countStmt = $this->db->prepare("SELECT COUNT(*) FROM $this->table WHERE user_id = :user_id");
            $countStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $countStmt->execute();
            $totalNotification = $countStmt->fetchColumn();

            $query = "SELECT * FROM $this->table WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total_pages = max(1, ceil($totalNotification / $limit));
            return [
                'notifications' => $notifications,
                'current_page' => $page,
                'total_pages' => $total_pages
            ];
        } catch (Exception $e) {
            return [];
        }
    }
}