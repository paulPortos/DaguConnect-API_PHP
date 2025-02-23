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
        $host = $_ENV['IP_ADDRESS'];
        $url = "http://{$host}:8000/uploads/profile_pictures/EzeBoi.png";
        $this->seedAdmin1($url);
    }

    private function seedAdmin1($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);

        $stmt = $this->db->prepare("INSERT INTO admin (first_name, last_name, profile_picture, username, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Ezekiel', 'Vidal', $url, 'EzeBoy', 'test@gmail.com', $hashed_password]);
    }
}