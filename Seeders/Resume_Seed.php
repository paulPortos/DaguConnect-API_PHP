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
            "Electrical_work",
            $url,
            '1999-03-21',
            "Experienced electrician specializing in lighting, circuits, and energy-efficient solutions.",
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
            "I’m a painter who transforms spaces with vibrant colors and precision.",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Lucas Wright",
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
            "Electrical_work",
            $url,
            '1999-03-21',
            "I troubleshoot electrical issues and design systems as a dedicated electrician.",
            "Dagupan",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Charlotte Evans",
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
            "Masonry",
            $url,
            '1999-03-21',
            "I’m a mason who expertly crafts durable walls, foundations, and chimneys using brick and stone.",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Jacob Lee",
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
            "Skilled painter dedicated to creating beautiful, lasting finishes for homes.",
            "Binmaley",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Isabella Rossi",
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
            "Electrical_work",
            $url,
            '1999-03-21',
            "Skilled electrician passionate about ensuring safe, reliable power for all clients.",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Ava Sullivan",
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
            "I specialize in detailed painting and restoration as a professional painter.",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Ethan Brooks",
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
            "Electrical_work",
            $url,
            '1999-03-21',
            "I’m an electrician who installs and repairs wiring for homes and businesses.",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Sophia Nguyen",
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
            "Experienced painter bringing creativity and expertise to every wall I touch.",
            "Agno",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Noah Martinez",
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
            "I’m a carpenter who builds sturdy frameworks and custom furniture pieces.",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Olivia Patel",
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
            "Roofing",
            $url,
            '1999-03-21',
            "I’m a roofer who installs durable roofs to shield your home.",
            "Agno",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Liam Carter",
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
            "Mechanic",
            $url,
            '1999-03-21',
            "I’m a mechanic who repairs engines and keeps vehicles running smoothly.",
            "Lingayen",
            200,
            $documents,
            $valid_id_front,
            $valid_id_back,
            "Emma Johnson",
            "Approved",
            1,
            1
        ]);
    }
}