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
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $url = "http://{$host}/uploads/profile_pictures/Default.png";
        $this->seedUser1($url);
        $this->seedUser2($url);
        $this->seedUser3($url);
        $this->seedUser4($url);
        $this->seedUser5($url);
        $this->seedUser6($url);
        $this->seedUser7($url);
        $this->seedUser8($url);
        $this->seedUser9($url);
        $this->seedUser10($url);
        $this->seedUser11($url);
        $this->seedUser12($url);
        $this->seedUser13($url);
        $this->seedUser14($url);
        $this->seedUser15($url);
    }

    private function seedUser1 ($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['Xmen', 'Wolverine', 'XBoy', '1999-03-21', 0, 'testing@gmail.com', $url ,1, $hashed_password]);
    }

    private function seedUser2 ($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)");
        $stmt->execute(['Ahron Paul', 'Villacote', 'vahron24', '1999-03-21' , 0, 'vahron24@gmail.com', $url, 0, $hashed_password]);
    }

    private function seedUser3 ($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['Alice', 'Johnson', 'alicejohnson', '1999-03-21' , 0, 'test@gmail.com', $url, 1, $hashed_password]);
    }
    private function seedUser4 ($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test4', 'test4', 'test4', '1999-03-21' , 0, 'test4@gmail.com', $url, 0, $hashed_password]);
    }

    private function seedUser5 ($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test5', 'test5', 'test5', '1999-03-21' , 0, 'test5@gmail.com', $url, 0, $hashed_password]);
    }

    private function seedUser6 ($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test6', 'test6', 'test6', '1999-03-21' , 0, 'test6@gmail.com', $url, 0, $hashed_password]);
    }

    private function seedUser7 ($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test7', 'test7', 'test7', '1999-03-21' , 0, 'test7@gmail.com', $url, 0, $hashed_password]);
    }
    private function seedUser8($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test8', 'test8', 'test8', '1999-03-21' , 0, 'test8@gmail.com', $url, 1, $hashed_password]);
    }
    private function seedUser9($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test9', 'test9', 'test9', '1999-03-21' , 0, 'test9@gmail.com',$url, 0, $hashed_password]);
    }
    private function seedUser10($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test10', 'test10', 'test10', '1999-03-21' , 0, 'test10@gmail.com',$url, 0, $hashed_password]);
    }
    private function seedUser11($url): void {
    $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
    $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
    $stmt->execute(['test11', 'test11', 'test11', '1999-03-21' , 0, 'test11@gmail.com', $url, 0, $hashed_password]);
    }
    private function seedUser12($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test12', 'test12', 'test12', '1999-03-21' , 0, 'test12@gmail.com', $url, 0, $hashed_password]);
    }
    private function seedUser13($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test13', 'test13', 'test13', '1999-03-21' , 0, 'test13@gmail.com', $url, 0, $hashed_password]);
    }
    private function seedUser14($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test14', 'test14', 'test14', '1999-03-21' , 0, 'test14@gmail.com', $url, 0, $hashed_password]);
    }
    private function seedUser15($url): void {
        $hashed_password = password_hash('password123', PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, username, birthdate, suspend, email_verified_at, email,profile, is_client, password) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?,?)");
        $stmt->execute(['test15', 'test15', 'test15', '1999-03-21' , 0, 'test15@gmail.com',$url, 0, $hashed_password]);
    }




}