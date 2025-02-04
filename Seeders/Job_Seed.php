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
        $this->seedJob1();
        $this->seedJob2();
        $this->seedJob3();
        $this->seedJob4();
        $this->seedJob5();
        $this->seedJob6();
        $this->seedJob7();
        $this->seedJob8();
        $this->seedJob9();
        $this->seedJob10();
        $this->seedJob11();
        $this->seedJob12();
        echo "Seeding jobs table complete" . PHP_EOL;
    }

    private function seedJob1(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1,
            'John Doe',
            null,
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

    private function seedJob2(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3,
            'Jane Smith',
            null,
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

    private function seedJob3(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Alice Johnson',
            null,
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

    private function seedJob4(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Alice Johnson',
            null,
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

    private function seedJob5(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Bob Brown',
            null,
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

    private function seedJob6(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Kuya Doc',
            null,
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

    private function seedJob7(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Lolo Diga',
            null,
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

    private function seedJob8(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Ladies Choice Mayonnaise',
            null,
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

    private function seedJob9(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            1, // user_id
            'Mr Clean',
            null,
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

    private function seedJob10(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Aqua Boy',
            null,
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

    private function seedJob11(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Rapper D',
            null,
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

    private function seedJob12(): void {
        $stmt = $this->db->prepare("INSERT INTO jobs (user_id, client_fullname, client_profile_picture, salary, job_type, job_description, location, latitude, longitude, status, deadline) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            3, // user_id
            'Vladdy Boy',
            null,
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