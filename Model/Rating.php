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

    public function Rate_Tradesman($tradesman_id,$client_id,$rating,$message,$client_name):bool{
        $query = "INSERT INTO $this->table (tradesman_id,client_id, profile ,rating,message,client_name,rated_at ) 
                    VALUES(:tradesman_id, :client_id, 'none', :rating, :message, :client_name, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':client_name', $client_name);
        return $stmt->execute();
    }


}