<?php

namespace DaguConnect\Seeders;

use PDO;

trait Client_Profile_Seed
{
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function seed_client_profile(): void
    {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $url = "http://{$host}/uploads/profile_pictures/Default.png";
        $this->seedClientProfile1($url);
        $this->seedClientProfile2($url);
        $this->seedClientProfile3($url);
    }

    public function seedClientProfile1($url): void
    {
        $stmt = $this->db->prepare("INSERT INTO client_profile (user_id, full_name, email, address, profile_picture) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([1, 'Xmen Wolverine', 'testing@gmail.com', 'Dagupan City, Pangasinan', $url]);
    }

    public function seedClientProfile2($url): void
    {
        $stmt = $this->db->prepare("INSERT INTO client_profile (user_id, full_name, email, address, profile_picture) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([2, 'Alice Johnson', 'test@gmail.com', 'San Fabian, Pangasinan', $url]);
    }

    public function seedClientProfile3($url): void
    {
        $stmt = $this->db->prepare("INSERT INTO client_profile (user_id, full_name, email, address, profile_picture) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([8, 'Test 8 Client', 'test8Client@gmail.com', 'San Carlos City, Pangasinan', $url]);
    }
}