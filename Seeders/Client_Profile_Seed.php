<?php

namespace DaguConnect\Seeders;

use DaguConnect\Services\Env;
use PDO;

trait Client_Profile_Seed
{
    private PDO $db;

    public function __construct(PDO $db) {
        new Env();
        $this->db = $db;
    }

    public function seed_client_profile(): void
    {
        $host = $_ENV['IP_ADDRESS'];
        $url = "http://{$host}:8000/uploads/profile_pictures/Default.png";
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
        $stmt->execute([3, 'Alice Johnson', 'test@gmail.com', 'San Fabian, Pangasinan', $url]);
    }

    public function seedClientProfile3($url): void
    {
        $stmt = $this->db->prepare("INSERT INTO client_profile (user_id, full_name, email, address, profile_picture) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([8, 'test8 test8', 'test8@gmail.com', 'San Carlos City, Pangasinan', $url]);
    }
}