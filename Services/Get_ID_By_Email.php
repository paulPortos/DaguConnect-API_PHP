<?php

namespace DaguConnect\Services;

use PDO;

trait Get_ID_By_Email
{
    public function getUserByEmail( string $email,PDO $db): ?array
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }


}