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

    public function RateTradesman($client_id,$tradesman_id,$rating,$message,$client_name,$profile_picture,$tradesman_fullname):bool{
        $query = "INSERT INTO $this->table (client_id,tradesman_id,tradesman_fullname,client_profile ,ratings,message,client_name,rated_at) 
                    VALUES(:client_id, :tradesman_id,:tradesman_fullname, :profile_pic, :rating, :message, :client_name, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->bindParam(':tradesman_fullname', $tradesman_fullname);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':client_name', $client_name);
        $stmt->bindParam(':profile_pic', $profile_picture);
        return $stmt->execute();
    }

    public function ExistingRating($client_id,$trademan_id){
        $query = "SELECT COUNT(*) FROM  $this->table  WHERE 
                client_id = :client_id AND tradesman_id = :trademan_id ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':trademan_id', $trademan_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function viewratings($tradesman_id){
        $query = "SELECT * FROM $this->table WHERE
                        tradesman_id = :tradesman_id ORDER BY rated_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}