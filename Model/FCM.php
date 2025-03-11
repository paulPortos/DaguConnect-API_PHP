<?php
namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;

class FCM extends BaseModel {
    protected string $table = "fcm_tokens";

    public function __construct(PDO $db){
        parent::__construct($db);
    }

    public function getToken($user_id): array
    {
        try {
            $query = "SELECT token FROM $this->table WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("error: " . $e->getMessage());
            return [];
        }
    }
}