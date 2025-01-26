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
}