<?php

namespace DaguConnect\Model;
use DaguConnect\Core\BaseModel;

use PDO;
use PDOException;

class Tradesman extends BaseModel
{
    protected $table = 'client_booking';

    public function __construct(PDO $db){
        parent::__construct($db);
    }

    //get all the booking from the tradesman
    public function getClientsBooking($tradesman_id,int $pages, int $limit):array{
        $offset = ($pages - 1) * $limit;
        try{

            $count_stmt = $this->db->prepare("SELECT COUNT(*) as total FROM $this->table WHERE tradesman_id = :tradesman_id ");
            $count_stmt ->bindParam(':tradesman_id', $tradesman_id,PDO::PARAM_INT);
            $count_stmt ->execute();
            $totalApplicants = (int) $count_stmt->fetch(PDO::FETCH_ASSOC)['total']; // ✅ Cast to int


            $query = "SELECT * FROM $this->table WHERE tradesman_id = :tradesman_id LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':tradesman_id', $tradesman_id);
            $stmt->bindParam(':limit', $limit,PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset,PDO::PARAM_INT);
            $stmt->execute();

            $bookings = $stmt->fetchall(PDO::FETCH_ASSOC);

            $totalPages = max(1, ceil($totalApplicants / $limit));

            return [
                'bookings' => $bookings,
                'current_page' => $pages,
                'total_pages' => $totalPages
            ];
        }catch (PDOException $e){
            error_log("error: " . $e->getMessage());
            return [];

        }

    }

    //update the booking_status if it is declined or accepted
    public function UpdateBookStatus($booking_status,$booking_id,$tradesman_id,$cancel_reason):bool
    {
        try {
            $query = "UPDATE $this->table SET
                  booking_status = :booking_status, cancel_reason = :cancel_reason, booking_date_status = NOW()
                  WHERE id = :booking_id AND tradesman_id = :tradesman_id";

            $stmt = $this->db->prepare($query);
            $stmt ->bindParam(':booking_status', $booking_status);
            $stmt ->bindParam(':booking_id', $booking_id);
            $stmt ->bindParam(':tradesman_id', $tradesman_id);
            $stmt ->bindParam(':cancel_reason', $cancel_reason);

            return $stmt->execute();
        }catch (PDOException $e){
            // Log the error message and return false
            error_log('Error on updating: '. $e->getMessage());
            return false;
        }


    }

    //validate if the booking exist or if the booking belongs to the tradesman
    public function ValidateBookingUpdate( $tradesman_id,$booking_id): bool
    {
        try {
            $query = "SELECT COUNT(*) FROM $this->table WHERE id = :booking_id AND tradesman_id = :tradesman_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->bindParam(':tradesman_id', $tradesman_id, PDO::PARAM_INT);
            $stmt->execute();

            // Return true if the booking exists, false otherwise
            return $stmt->fetchColumn() > 0;
        }catch (PDOException $e){
            error_log('Error on updating: '. $e->getMessage());
            return false;
        }

    }



}