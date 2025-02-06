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
    public function getClientsBooking($tradesman_id):array{

        $query = "SELECT * FROM $this->table WHERE tradesman_id = :tradesman_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->execute();

        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    //update the booking_status if it is rejected or accepted
    public function UpdateBookStatus($booking_status,$booking_id,$tradesman_id):bool
    {
        try {
            $query = "UPDATE $this->table SET
                  booking_status = :booking_status
                  WHERE id = :booking_id AND tradesman_id = :tradesman_id";

            $stmt = $this->db->prepare($query);
            $stmt ->bindParam(':booking_status', $booking_status);
            $stmt ->bindParam(':booking_id', $booking_id);
            $stmt ->bindParam(':tradesman_id', $tradesman_id);

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