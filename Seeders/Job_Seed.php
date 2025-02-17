<?php

namespace DaguConnect\Seeders;

use PDO;

trait Job_Seed
{
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function seed_jobs(): void {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $url = "http://{$host}/uploads/profile_pictures/Default.png";
        $this->seedJob1($url);
        $this->seedJob2($url);
        $this->seedJob3($url);
        $this->seedJob4($url);
        $this->seedJob5($url);
        $this->seedJob6($url);
        $this->seedJob7($url);
        $this->seedJob8($url);
        $this->seedJob9($url);
        $this->seedJob10($url);
        $this->seedJob11($url);
        $this->seedJob12($url);
        echo "Seeding jobs table complete" . PHP_EOL;
    }

    private function seedJob1($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1,
            'John Doe',
            $url,
            1500.00,
            'electrician',
            'Need an electrician to fix the wiring in my house.',
            '123 Main St, Anytown, USA',
            null,
            null,
            'available',
            '2023-12-31'
        ]);
    }

    private function seedJob2($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3,
            'Jane Smith',
            $url,
            2000.00,
            'plumber',
            'Looking for a plumber to fix a leaking pipe.',
            '456 Elm St, Othertown, USA',
            null,
            null,
            'available',
            '2025-11-30'
        ]);
    }

    private function seedJob3($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Alice Johnson',
            $url,
            1800.00,
            'carpenter',
            'Need a carpenter to build a custom bookshelf.',
            '789 Oak St, Sometown, USA',
            null,
            null,
            'available',
            '2025-10-14'
        ]);
    }

    private function seedJob4($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Alice Johnson',
            $url,
            1800.00,
            'electrician',
            'Need a electrician to fix my wire on my 1st and 2nd floor.',
            '789 Oak St, Sometown, USA',
            null,
            null,
            'available',
            '2025-10-15'
        ]);
    }

    private function seedJob5($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Bob Brown',
            $url,
            2200.00,
            'mason',
            'Looking for a mason to build a brick wall.',
            '321 Pine St, New City, USA',
            null,
            null,
            'available',
            '2024-01-15'
        ]);
    }

    private function seedJob6($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Kuya Doc',
            $url,
            2200.00,
            'painter',
            'Looking for a painter to paint my dry wall into pink.',
            '321 Pine St, New City, USA',
            null,
            null,
            'available',
            '2024-01-15'
        ]);
    }

    private function seedJob7($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Lolo Diga',
            $url,
            2200.00,
            'mason',
            'Looking for a mason to build a brick wall.',
            '321 Pine St, New City, USA',
            null,
            null,
            'available',
            '2024-01-15'
        ]);
    }

    private function seedJob8($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Ladies Choice Mayonnaise',
            $url,
            1800.00,
            'carpenter',
            'Need a mechanic to build a custom car with turbo engine.',
            '789 Oak St, Sometown, USA',
            null,
            null,
            'available',
            '2025-10-14'
        ]);
    }

    private function seedJob9($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Mr Clean',
            $url,
            1800.00,
            'cleaner',
            'Need a cleaner to clean my whole house using mr clean.',
            '789 Oak St, DagTown, Japan',
            null,
            null,
            'available',
            '2025-10-14'
        ]);
    }

    private function seedJob10($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Aqua Boy',
            $url,
            1800.00,
            'cleaner',
            'Need a good cleaner to clean my whole house underwater',
            '789 Oak St, DagTown, Japan',
            null,
            null,
            'available',
            '2025-10-14'
        ]);
    }

    private function seedJob11($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Rapper D',
            $url,
            1800.00,
            'carpenter',
            'Need a carpenter to fix my 1B mansion located at runeterra.',
            '789 Teemo St, Bandle City, Runeterra',
            null,
            null,
            'available',
            '2025-10-14'
        ]);
    }

    private function seedJob12($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Vladdy Boy',
            $url,
            1800.00,
            'roofer',
            'Need a roofer to make my wood roof into a silicon m2 chip with 16 gb ram.',
            '789 Oak St, DagTown, Japan',
            null,
            null,
            'available',
            '2025-10-14'
        ]);
    }
}