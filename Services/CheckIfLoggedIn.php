<?php

namespace DaguConnect\Services;

use DaguConnect\Includes\config;
use PDO;

trait CheckIfLoggedIn
{
    protected config $db;

    public function loggedIn($email, $table): bool
    {
        $query = "SELECT * FROM $table WHERE email = :email AND token IS NOT NULL";

        $stmt = $this->db->getDB()->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result !== false) {
            return true;
        }
        return false;
    }
}