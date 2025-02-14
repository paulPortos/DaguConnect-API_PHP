<?php

namespace DaguConnect\Seeders;

use PDO;

trait clientbooking_Seed
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function seedClientBooking():void{
        $this->seedClientBooking1();
        $this->seedClientBooking2();
        $this->seedClientBooking3();
        $this->seedClientBooking4();
        $this->seedClientBooking5();
        echo "Seeding client bookings completed". PHP_EOL;
    }

    public function seedClientBooking1():void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            1,
            1,
            2,
           "099535834236",
            "Ahron Paul Ahron Paul",
            "http://". $_SERVER['HTTP_HOST'] ."/uploads/profile_pictures/Default.png",
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

    public function seedClientBooking2():void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            3,
            2,
            4,
            "099535834236",
            "test4 test4",
            "http://". $_SERVER['HTTP_HOST'] ."/uploads/profile_pictures/Default.png",
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
    public function seedClientBooking3():void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            8,
            3,
            5,
            "099535834236",
            "test5 test5",
            "http://". $_SERVER['HTTP_HOST'] ."/uploads/profile_pictures/Default.png",
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
    public function seedClientBooking4():void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            9,
            4,
            6,
            "099535834236",
            "test6 test6",
            "http://". $_SERVER['HTTP_HOST'] ."/uploads/profile_pictures/Default.png",
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
    public function seedClientBooking5():void{
        $stmt = $this->db->prepare("INSERT INTO client_booking(user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname, address, task_type, task_description, booking_date, booking_status, cancel_reason, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            10,
            5,
            7,
            "099535834236",
            "test7 test7",
            "http://". $_SERVER['HTTP_HOST'] ."/uploads/profile_pictures/Default.png",
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