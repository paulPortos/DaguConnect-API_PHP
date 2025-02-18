<?php

namespace DaguConnect\Seeders;

use PDO;

trait Resume_Seed
{
    private PDO $db;
    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function seedResume(): void
    {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $url = "http://{$host}/uploads/profile_pictures/Default.png";
        $this->seedResume1($url);
        $this->seedResume2($url);
        $this->seedResume3($url);
        $this->seedResume4($url);
        $this->seedResume5($url);
        $this->seedResume6($url);
        $this->seedResume7($url);
        $this->seedResume8($url);
        $this->seedResume9($url);
        $this->seedResume10($url);
        $this->seedResume11($url);
        $this->seedResume12($url);
    }

    public function seedResume1($url): void
    {
        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "vahron24@gmail.com",
            2,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Dagupan",
            200,
            "Ahron Paul Villacote",
            1
        ]);
    }

    public function seedResume2($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test4@gmail.com",
            4,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "test4 test4",
            1
        ]);
    }
    public function seedResume3($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test5@gmail.com",
            5,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "karlos son",
            1
        ]);
    }

    public function seedResume4($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test6@gmail.com",
            6,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "karlos son",
            1
        ]);

    }
    public function seedResume5($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test7@gmail.com",
            7,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "test7 test7",
            1
        ]);
    }

    public function seedResume6($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test9@gmail.com",
            9,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "test9 test9",
            1
        ]);
    }
    public function seedResume7($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test10@gmail.com",
            10,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "tester son",
            1
        ]);
    }
    public function seedResume8($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test11@gmail.com",
            11,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            json_encode(["Lingayen","Dagupan"]),
            200,
            "tester son",
            1
        ]);
    }

    public function seedResume9($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test12@gmail.com",
            12,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "test12 test12",
            1
        ]);
    }

    public function seedResume10($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test13@gmail.com",
            13,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "test13 test13",
            1
        ]);
    }
    public function seedResume11($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test14@gmail.com",
            14,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "test14 test14",
            1
        ]);
    }
    public function seedResume12($url): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test15@gmail.com",
            15,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            $url,
            "hello this is a tester",
            "Lingayen",
            200,
            "test15 test15",
            1
        ]);
    }

}