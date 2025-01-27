<?php

namespace DaguConnect\Model;
use DaguConnect\Core\BaseModel;

use PDO;

class Tradesman extends BaseModel
{
    protected $table = 'client_booking';

    public function __construct(PDO $db){
        parent::__construct($db);
    }

    public function getClientsBooking($tradesman_id):array{

        $query = "SELECT * FROM $this->table WHERE tradesman_id = :tradesman_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->execute();

        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    public function UpdateBookStatus($booking_status,$work_status,$booking_id,$tradesman_id):bool
    {
        $query = "UPDATE $this->table SET
                  booking_status = :booking_status,work_status = :work_status
                  WHERE id = :booking_id AND tradesman_id = :tradesman_id";

        $stmt = $this->db->prepare($query);
        $stmt ->bindParam(':booking_status', $booking_status);
        $stmt ->bindParam(':work_status', $work_status);
        $stmt ->bindParam(':booking_id', $booking_id);
        $stmt ->bindParam(':tradesman_id', $tradesman_id);

        return $stmt->execute();

    }

    public function ValidateBookingUpdate($booking_id, $tradesman_id): bool
    {
        $query = "SELECT COUNT(*) FROM $this->table WHERE id = :booking_id AND tradesman_id = :tradesman_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->bindParam(':tradesman_id', $tradesman_id, PDO::PARAM_INT);
        $stmt->execute();

        // Return true if the booking exists, false otherwise
        return $stmt->fetchColumn() > 0;
    }

}