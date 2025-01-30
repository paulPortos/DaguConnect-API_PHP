<?php

// In Token.php (Model)
namespace DaguConnect\Model;

use PDO;
use PDOException;

class Token
{
    protected $db;
    protected $table = 'user_tokens';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function validateToken(string $token): ?array
    {
        try {
            // Query to check if the token exists and is valid in the database
            $query = "SELECT * FROM $this->table WHERE token = :token";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }catch (PDOException $e){
            error_log("error failed to validate: " . $e->getMessage());
            return [];
        }

    }
}
