<?php

namespace DaguConnect\Seeders;

use PDO;

trait User_Seed
{

    private PDO $db;

    public function __construct(PDO $db) {
        $this->$db = $db;

    }

    public function seed_user(): void {
        $this->seedUser1();
        $this->seedUser2();
        $this->seedUser3();
        echo "Seeding users table complete" . PHP_EOL;
    }

    private function seedUser1 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, age, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['Xmen', 'Wolverine', 'XBoy', 30, 0, 'testing@gmail.com', 1, $hashed_password]);
    }

    private function seedUser2 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, age, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['Ahron Paul', 'Villacote', 'vahron24', 25, 0, 'vahron24@gmail.com', 0, $hashed_password]);
    }

    private function seedUser3 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, age, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['Alice', 'Johnson', 'alicejohnson', 28, 0, 'test@gmail.com', 1, $hashed_password]);
    }
}