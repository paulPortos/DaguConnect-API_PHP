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
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            1,
            1,
            2,
            "099535834236",
            "Ahron Paul Ahron Paul",
            $url,
            200,
            "Xmen Wolverine",
            "123 Main Street, New York",
            "Carpentry",
            "I need help with a software bug",
            "2022-01-01",
            "Pending",
            null   // cancel_reason
        ]);
    }

    public function seedClientBooking2($url):void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            3,
            2,
            4,
            "099535834236",
            "test4 test4",
            $url,
            200,
            "Alice Johnson",
            "123 Main Street, New York",
            "Welding",
            "I need help with a software bug",
            "2022-01-01",
            "Active",
            null   // cancel_reason
        ]);
    }
    public function seedClientBooking3($url):void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            8,
            3,
            5,
            "099535834236",
            "test5 test5",
            $url,
            200,
            "test8 test8",
            "123 Main Street, New York",
            "Plumbing",
            "I need help with a software bug",
            "2022-01-01",
            "Completed",
            null   // cancel_reason
        ]);
    }
    public function seedClientBooking4($url):void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            9,
            4,
            6,
            "099535834236",
            "test6 test6",
            $url,
            200,
            "test9 test9",
            "123 Main Street, New York",
            "Electrician",
            "I need help with a software bug",
            "2022-01-01",
            "Cancelled",
            null   // cancel_reason
        ]);
    }
    public function seedClientBooking5($url):void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            10,
            5,
            7,
            "099535834236",
            "test7 test7",
            $url,
            200,
            "test10 test10",
            "123 Main Street, New York",
            "Roofing",
            "I need help with a software bug",
            "2022-01-01",
            "Declined",
            null   // cancel_reason
        ]);
    }

}