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

        $stmt = $this->db->prepare("INSERT INTO admin (first_name, last_name, username, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Ezekiel', 'Vidal', 'EzeBoy', 'test@gmail.com', $hashed_password]);
    }
}