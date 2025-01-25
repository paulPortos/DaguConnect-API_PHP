<?php

namespace DaguConnect\Services;

use PDO;

trait GetIdByEmail
{
    public function getUserByEmail( string $email,PDO $db): ?array
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            // Convert TINYINT(1) to true/false
            $user['suspend'] = (bool)$user['suspend'];
            $user['is_client'] = (bool)$user['is_client'];
        }
        return $user ?: null;
    }


}