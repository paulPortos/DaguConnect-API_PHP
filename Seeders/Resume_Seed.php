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
        $this->seedResume1();
        $this->seedResume2();
        $this->seedResume3();
        $this->seedResume4();
        $this->seedResume5();
        echo"Seeding resume complete". PHP_EOL;
    }

    public function seedResume1(): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "vahron24@gmail.com",
            2,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            "http://192.168.1.217:8000/uploads/profile_pictures/Default.png",
            "hello this is a tester",
            json_encode(["Lingayen","Dagupan"]),
            200,
            "Ahron Paul Villacote",
            1
        ]);
    }

    public function seedResume2(): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test4@gmail.com",
            4,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            "http://192.168.1.217:8000/uploads/profile_pictures/Default.png",
            "hello this is a tester",
            json_encode(["Lingayen","Dagupan"]),
            200,
            "test4 test4",
            1
        ]);
    }
    public function seedResume3(): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test5@gmail.com",
            5,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            "http://192.168.1.217:8000/uploads/profile_pictures/Default.png",
            "hello this is a tester",
            json_encode(["Lingayen","Dagupan"]),
            200,
            "karlos son",
            1
        ]);
    }

    public function seedResume4(): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test6@gmail.com",
            6,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            "http://192.168.1.217:8000/uploads/profile_pictures/Default.png",
            "hello this is a tester",
            json_encode(["Lingayen","Dagupan"]),
            200,
            "karlos son",
            1
        ]);

    }
    public function seedResume5(): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialties, profile_pic, about_me, prefered_work_location, work_fee, tradesman_full_name, updated_at, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([
            "test7@gmail.com",
            7,
            json_encode(["Carpentry", "Painting", "Electrician"]),
            "http://192.168.1.217:8000/uploads/profile_pictures/Default.png",
            "hello this is a tester",
            json_encode(["Lingayen","Dagupan"]),
            200,
            "karlos son",
            1
        ]);
    }


}