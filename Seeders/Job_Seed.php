<?php

namespace DaguConnect\Seeders;

use DaguConnect\Services\Env;
use PDO;

trait Job_Seed
{
    private PDO $db;

    public function __construct(PDO $db) {
        new Env();
        $this->db = $db;
    }

    public function seed_jobs(): void {
        $host = $_ENV['IP_ADDRESS'];
        $url = "http://{$host}:8000/uploads/profile_pictures/Default.png";
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
    }

    private function seedJob1($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1,
            'John Smith',
            1,
            $url,
            1500.00,
            5,
            'Electrician',
            'Need an electrician to fix the wiring in my house.',
            'Urbiztondo',
            null,
            null,
            'Available',
            '2023-12-31'
        ]);
    }

    private function seedJob2($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3,
            'Karlos Rivo', //2
            2,
            $url,
            2000.00,
            5,
            'Electrician',
            'Looking for a plumber to fix a leaking pipe.',
            'Mangatarem',
            null,
            null,
            'Available',
            '2025-11-30'
        ]);
    }

    private function seedJob3($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Alice Johnson',
            1,
            $url,
            1800.00,
            5,
            'Carpenter',
            'Need a carpenter to build a custom bookshelf.',
            'Bayambang',
            null,
            null,
            'Available',
            '2025-10-14'
        ]);
    }

    private function seedJob4($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Alice Johnson',
            1,
            $url,
            1800.00,
            5,
            'Electrician',
            'Need a electrician to fix my wire on my 1st and 2nd floor.',
            'San Jacinto',
            null,
            null,
            'Available',
            '2025-10-15'
        ]);
    }

    private function seedJob5($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Bob Brown',
            2,
            $url,
            2200.00,
            5,
            'Mason',
            'Looking for a mason to build a brick wall.',
            'San Fabian',
            null,
            null,
            'Available',
            '2024-01-15'
        ]);
    }

    private function seedJob6($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Kuya Doc',
            2,
            $url,
            2200.00,
            5,
            'Electrician',
            'Looking for a painter to paint my dry wall into pink.',
            'Lingayen',
            null,
            null,
            'Available',
            '2024-01-15'
        ]);
    }

    private function seedJob7($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Lolo Diga',
            2,
            $url,
            2200.00,
            5,
            'Mason',
            'Looking for a mason to build a brick wall.',
            'Lingayen',
            null,
            null,
            'Available',
            '2024-01-15'
        ]);
    }

    private function seedJob8($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Ladies Choice Mayonnaise',
            1,
            $url,
            1800.00,
            5,
            'Carpenter',
            'Need a mechanic to build a custom car with turbo engine.',
            'Agno',
            null,
            null,
            'Available',
            '2025-10-14'
        ]);
    }

    private function seedJob9($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Mr Clean',
            1,
            $url,
            1800.00,
            5,
            'Cleaner',
            'Need a cleaner to clean my whole house using mr clean.',
            'Aguilar',
            null,
            null,
            'Available',
            '2025-10-14'
        ]);
    }

    private function seedJob10($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Aqua Boy',
            2,
            $url,
            1800.00,
            5,
            'Cleaner',
            'Need a good cleaner to clean my whole house underwater',
            '789 Oak St, DagTown, Japan',
            null,
            null,
            'Available',
            '2025-10-14'
        ]);
    }

    private function seedJob11($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Rapper D',
            2,
            $url,
            1800.00,
            5,
            'Carpenter',
            'Need a carpenter to fix my 1B mansion located at runeterra.',
            'Mangatarem',
            null,
            null,
            'Available',
            '2025-10-14'
        ]);
    }

    private function seedJob12($url): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Vladdy Boy',
            2,
            $url,
            1800.00,
            5,
            'Electrician',
            'Need a roofer to make my wood roof into a silicon m2 chip with 16 gb ram.',
            'Lingayen',
            null,
            null,
            'Available',
            '2025-10-14'
        ]);
    }
}