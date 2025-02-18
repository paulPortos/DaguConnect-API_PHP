<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;

class Rating extends BaseModel
{
    protected $table = 'ratings';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function RateTradesman($client_id,$booking_id,$rating,$message,$client_name):bool{
        $query = "INSERT INTO $this->table (client_id,booking_id,profile ,ratings,message,client_name,rated_at) 
                    VALUES(:client_id, :booking_id, 'none', :rating, :message, :client_name, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':booking_id', $booking_id);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':client_name', $client_name);
        return $stmt->execute();
    }

    public function ExistingRating($client_id,$booking_id){
        $query = "SELECT COUNT(*) FROM  $this->table  WHERE 
                client_id = :client_id AND booking_id = :booking_id ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':booking_id', $booking_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}