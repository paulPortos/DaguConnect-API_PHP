<?php

namespace DaguConnect\PhpMailer;

use PDO;


trait EmailVerification
{
    protected $table = 'users';
    public function EmailVerify($email, PDO $db): bool{
        $query = "UPDATE $this->table SET email_verified_at = NOW() WHERE email = :email AND email_verified_at IS NULL";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':email', $email);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }
}