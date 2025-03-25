<?php

namespace DaguConnect\Seeders;

use DaguConnect\Services\Env;
use PDO;

trait clientbooking_Seed
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
        new Env();
    }

    public function seedClientBooking():void{
        $host = $_ENV['IP_ADDRESS'];
        $url = "http://{$host}:8000/uploads/profile_pictures/Default.png";
        $this->seedClientBooking1($url);
        $this->seedClientBooking2($url);
        $this->seedClientBooking3($url);
        $this->seedClientBooking4($url);
        $this->seedClientBooking5($url);
    }

    public function seedClientBooking1($url):void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname,client_profile, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            1,
            1,
            2,
            "099535834236",
            "Xmen Wolverine",
            $url,
            200,
            "Ezekiel Vidal",
            $url,
            "123 Main Street, New York",
            "Carpentry",
            "I need help with a software bug",
            "2022-01-01",
            "Pending",
            null   // cancel_reason
        ]);
    }

    public function seedClientBooking2($url):void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname,client_profile, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            3,
            2,
            4,
            "099535834236",
            "Lucas Wright",
            $url,
            200,
            "Alice Johnson",
            $url,
            "123 Main Street, New York",
            "Painter",
            "Join metal parts using heat and pressure to create strong, durable structures or repairs.",
            "2022-01-01",
            "Active",
            null   // cancel_reason
        ]);
    }
    public function seedClientBooking3($url):void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname,client_profile, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            8,
            3,
            5,
            "099535834236",
            "Charlotte Evans",
            $url,
            200,
            "test8 test8",
            $url,
            "123 Main Street, New York",
            "Plumbing",
            "Install and repair pipes, fixtures, and drainage systems to ensure efficient water flow and waste removal.",
            "2022-01-01",
            "Completed",
            null   // cancel_reason
        ]);
    }
    public function seedClientBooking4($url):void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname,client_profile, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            9,
            4,
            6,
            "099535834236",
            "Jacob Lee",
            $url,
            200,
            "test9 test9",
            $url,
            "123 Main Street, New York",
            "Electrician",
            "Install, maintain, and repair electrical wiring, systems, and equipment to ensure safe and reliable power distribution.",
            "2022-01-01",
            "Cancelled",
            null   // cancel_reason
        ]);
    }
    public function seedClientBooking5($url):void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname,client_profile, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            10,
            5,
            7,
            "099535834236",
            "Isabella Rossi",
            $url,
            200,
            "test10 test10",
            $url,
            "123 Main Street, New York",
            "Roofing",
            "Install, repair, and maintain roofs using materials like shingles, tiles, or metal to protect structures from weather damage.",
            "2022-01-01",
            "Declined",
            null   // cancel_reason
        ]);
    }

}