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
        $this->seedUser4();
        $this->seedUser5();
        $this->seedUser6();
        $this->seedUser7();
        $this->seedUser8();
        $this->seedUser9();
        $this->seedUser10();
        $this->seedUser11();
        $this->seedUser12();
        $this->seedUser13();
        $this->seedUser14();
        $this->seedUser15();
    }

    private function seedUser1 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['Xmen', 'Wolverine', 'XBoy', '1999-03-21', 0, 'testing@gmail.com', 1, $hashed_password]);
    }

    private function seedUser2 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['Ahron Paul', 'Villacote', 'vahron24', '1999-03-21' , 0, 'vahron24@gmail.com', 0, $hashed_password]);
    }

    private function seedUser3 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['Alice', 'Johnson', 'alicejohnson', '1999-03-21' , 0, 'test@gmail.com', 1, $hashed_password]);
    }
    private function seedUser4 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test4', 'test4', 'test4', '1999-03-21' , 0, 'test4@gmail.com', 0, $hashed_password]);
    }

    private function seedUser5 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test5', 'test5', 'test5', '1999-03-21' , 0, 'test5@gmail.com', 0, $hashed_password]);
    }

    private function seedUser6 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test6', 'test6', 'test6', '1999-03-21' , 0, 'test6@gmail.com', 0, $hashed_password]);
    }

    private function seedUser7 (): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test7', 'test7', 'test7', '1999-03-21' , 0, 'test7@gmail.com', 0, $hashed_password]);
    }
    private function seedUser8(): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['Test 8', 'Client', 'test8', '1999-03-21' , 0, 'test8Client@gmail.com', 1, $hashed_password]);
    }
    private function seedUser9(): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test9', 'test9', 'test9', '1999-03-21' , 0, 'test9@gmail.com', 0, $hashed_password]);
    }
    private function seedUser10(): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test10', 'test10', 'test10', '1999-03-21' , 0, 'test10@gmail.com', 0, $hashed_password]);
    }
    private function seedUser11(): void {
    $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
    $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
    $stmt->execute(['test11', 'test11', 'test11', '1999-03-21' , 0, 'test11@gmail.com', 0, $hashed_password]);
    }
    private function seedUser12(): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test12', 'test12', 'test12', '1999-03-21' , 0, 'test12@gmail.com', 0, $hashed_password]);
    }
    private function seedUser13(): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test13', 'test13', 'test13', '1999-03-21' , 0, 'test13@gmail.com', 0, $hashed_password]);
    }
    private function seedUser14(): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test14', 'test14', 'test14', '1999-03-21' , 0, 'test14@gmail.com', 0, $hashed_password]);
    }
    private function seedUser15(): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute(['test15', 'test15', 'test15', '1999-03-21' , 0, 'test15@gmail.com', 0, $hashed_password]);
    }




}