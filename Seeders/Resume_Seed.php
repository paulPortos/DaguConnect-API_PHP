<?php

namespace DaguConnect\Seeders;

use DaguConnect\Services\Env;
use PDO;

trait Resume_Seed
{
    private PDO $db;
    public function __construct(PDO $db){
        new Env();
        $this->db = $db;
    }

    public function seedResume(): void
    {
        $host = $_ENV['IP_ADDRESS'];
        $url = "http://{$host}:8000/uploads/profile_pictures/Default.png";
        $documents = "http://{$host}:8000/uploads/document/Default.pdf";
        $valid_id_front = "http://{$host}:8000/uploads/IDFRONT/Defaultfront.jpg";
        $valid_id_back = "http://{$host}:8000/uploads/IDBACK/Defaultback.jpg";
        $this->seedResume1($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume2($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume3($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume4($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume5($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume6($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume7($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume8($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume9($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume10($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume11($url,$documents,$valid_id_front,$valid_id_back);
        $this->seedResume12($url,$documents,$valid_id_front,$valid_id_back);
    }

    public function seedResume1($url,$documents,$valid_id_front,$valid_id_back): void
    {
        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "vahron24@gmail.com",
            2,
            "Electrician",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Dagupan",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Ahron Paul Villacote",
            "Approved",
            1,
            1
        ]);
    }

    public function seedResume2($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ? ,?)");
        $stmt->execute([
            "test4@gmail.com",
            4,
            "Painting",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "test4 test4",
            "Approved",
            1,
            1
        ]);
    }
    public function seedResume3($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test5@gmail.com",
            5,
            "Electrician",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "test5 test5",
            "Approved",
            1,
            1
        ]);
    }

    public function seedResume4($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test6@gmail.com",
            6,
           "Carpentry",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "karlos son",
            "Approved",
            1,
            1
        ]);

    }
    public function seedResume5($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test7@gmail.com",
            7,
           "Painting",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "test7 test7",
            "Approved",
            1,
            1
        ]);
    }

    public function seedResume6($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test9@gmail.com",
            9,
          "Electrician",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "test9 test9",
            "Approved",
            1,
            1
        ]);
    }
    public function seedResume7($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test10@gmail.com",
            10,
            "Painting",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "tester son",
            "Approved",
            1,
            1
        ]);
    }
    public function seedResume8($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test11@gmail.com",
            11,
            "Electrician",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "tester son",
            "Approved",
            1,
            1
        ]);
    }

    public function seedResume9($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test12@gmail.com",
            12,
           "Painting",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "test12 test12",
            "Approved",
            1,
            1
        ]);
    }

    public function seedResume10($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test13@gmail.com",
            13,
            "Carpentry",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "test13 test13",
            "Approved",
            1,
            1
        ]);
    }
    public function seedResume11($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test14@gmail.com",
            14,
            "Carpentry",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "test14 test14",
            "Approved",
            1,
            1
        ]);
    }
    public function seedResume12($url,$documents,$valid_id_front,$valid_id_back): void
    {

        $stmt = $this->db->prepare("INSERT INTO tradesman_resume(email, user_id, specialty, profile_pic,birthdate, about_me, prefered_work_location, work_fee,documents,valid_id_front,valid_id_back ,tradesman_full_name,status_of_approval ,updated_at, created_at, is_active,is_approve) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?,?)");
        $stmt->execute([
            "test15@gmail.com",
            15,
            "Carpentry",
            $url,
            '1999-03-21',
            "hello this is a tester",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "test15 test15",
            "Approved",
            1,
            1
        ]);
    }

}