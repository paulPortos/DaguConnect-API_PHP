<?php

namespace DaguConnect\Services;

use DaguConnect\Includes\config;
use PDO;
use PDOException;

class TraceTableUsingToken
{
    private config $config;

    public function __construct() {
        $this->config = new config();
    }
    public function traceTable($token): array {
        try {
            $query = "SELECT user_id FROM user_tokens WHERE token = :token";
            $stmt = $this->config->db->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            $user_id = $stmt->fetch(PDO::FETCH_ASSOC)['user_id'] ?? null;

            if(!$user_id){
                return [];
            }
            $query = "SELECT id FROM user_resume WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->config->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        } catch (PDOException $e) {
            error_log("Error tracing table: ", $e->getMessage());
            return [];
        }
    }
}