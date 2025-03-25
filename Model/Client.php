<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;

use PDO;
use PDOException;

class Client extends BaseModel
{
    protected $table = 'client_booking';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    //client booking tradesman
    public function BookTradesman($user_id, $resume_id, $tradesman_id, $phone_number, $tradesman_fullname, $tradesman_profile, $work_fee, $client_fullname, $address, $task_type, $task_description, $booking_date,$client_profile): bool
    {
        $query = "INSERT INTO $this->table 
                (user_id, resume_id, tradesman_id, phone_number, tradesman_fullname, tradesman_profile, work_fee, client_fullname,client_profile, address, task_type, task_description, booking_date, booking_status,booking_date_status, created_at)
                VALUES (:user_id, :resume_id, :tradesman_id, :phone_number, :tradesman_fullname, :tradesman_profile, :work_fee, :client_fullname,:client_profile, :address, :task_type, :task_description, :booking_date, 'Pending', NOW() ,NOW())";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':resume_id', $resume_id);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':tradesman_fullname', $tradesman_fullname);
        $stmt->bindParam(':tradesman_profile', $tradesman_profile);
        $stmt->bindParam(':work_fee', $work_fee);
        $stmt->bindParam(':client_fullname', $client_fullname);
        $stmt->bindParam(':client_profile', $client_profile);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':task_type', $task_type);
        $stmt->bindParam(':task_description', $task_description);
        $stmt->bindParam(':booking_date', $booking_date);

        return $stmt->execute();
    }

    //get the all the client booking that is accepted and work_status is active
    public  function GetBooking($user_id,int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        try {
            // Get total count of job applicants for the client
            $countStmt = $this->db->prepare("SELECT COUNT(*) as total FROM $this->table WHERE user_id = :user_id");
            $countStmt->bindParam(':user_id', $user_id ,PDO::PARAM_INT);
            $countStmt->execute();
            $totalApplicants = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total']; // ✅ Cast to int

            $query = "SELECT * FROM $this->table WHERE user_id = :user_id LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $bookings = $stmt->fetchall(PDO::FETCH_ASSOC);

            $totalPages = max(1, ceil($totalApplicants / $limit));

            return [
                'bookings' => $bookings,
                'current_page' => $page,
                'total_pages' => $totalPages
            ];
        }catch (PDOException $e){
            error_log("error: " . $e->getMessage());
            return [];
        }

    }
    public function ViewBooking($resumeId){
        $query = "SELECT * FROM $this->table WHERE resume_id = :resume_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':resume_id', $resumeId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    //update the work_status of the client_booking
    public function UpdateWorkStatus($user_id, $booking_id,$booking_status,$cancel_reason): bool
    {
        try {
            $query = "UPDATE $this->table 
            SET booking_status = :booking_status,
                cancel_reason = :cancel_reason,
                booking_date_status  = NOW()
            WHERE user_id = :user_id 
            AND id = :booking_id";


            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':booking_id', $booking_id);
            $stmt->bindParam(':cancel_reason', $cancel_reason);
            $stmt->bindParam(':booking_status', $booking_status);
            $stmt->execute();

            return $stmt->rowCount() > 0;  // Check if any rows were updated
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    //validate if the booking exist or if the booking belongs to the client
    public function ValidateWorkUpdate( $user_id,$booking_id): bool
    {
        $query = "SELECT COUNT(*) FROM $this->table WHERE id = :booking_id AND user_id = :user_id ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function CheckExistingBooking($user_id, $tradesman_id): bool
    {
        $query = "SELECT COUNT(*) FROM $this->table 
              WHERE user_id = :user_id 
              AND tradesman_id = :tradesman_id 
              AND booking_status IN ('Pending', 'Active')";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':tradesman_id', $tradesman_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }


    public function updateTradesmanProfileInBookings($user_id, $profile_pic_url): void
    {
        try {
            $query = "UPDATE $this->table 
                  SET tradesman_profile = :profile_pic_url 
                  WHERE tradesman_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':profile_pic_url', $profile_pic_url);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating tradesman profile in bookings: " . $e->getMessage());
        }
    }

    public function CheckCompletedBookings($user_id, $booking_id): bool
    {
        $query = "SELECT COUNT(*) FROM $this->table 
              WHERE user_id = :user_id 
              AND id = :booking_id
              AND booking_status IN ('Completed')";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

}