<?php

namespace DaguConnect\Seeders;

use PDO;

trait Admin_Seed
{

    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function seed_admin(): void {
        $this->seedAdmin1();
    }

    private function seedAdmin1(): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);

        $token = bin2hex(random_bytes(16));

        $stmt = $this->db->prepare("INSERT INTO admin (username, email, password, token) VALUES (?, ?, ?, ?)");
        $stmt->execute(['EzBoy', 'test@gmail.com', $hashed_password, $token]);
    }
}