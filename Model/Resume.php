<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;
use PDOException;

class Resume extends BaseModel
{
    protected $table = 'tradesman_resume';


    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function resume($email,$user_id,$specialties,$prefered_work_location,$academic_background,$tradesman_full_name):bool
    {
        try {
            // Convert arrays/objects to JSON strings
            $specialties_json = json_encode($specialties);
            $prefered_work_location_json = json_encode($prefered_work_location);
            $academic_background_json = json_encode($academic_background);

            $query = "INSERT INTO $this->table 
                    (email,user_id,specialties, prefered_work_location,academic_background,tradesman_full_name,updated_at,created_at) 
                    VALUES(:email,:user_id, :specialties, :prefered_work_location,:academic_background,:tradesman_full_name,NOW(),NOW())";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':specialties', $specialties_json);
            $stmt->bindParam(':prefered_work_location', $prefered_work_location_json);
            $stmt->bindParam(':academic_background', $academic_background_json);
            $stmt->bindParam(':tradesman_full_name', $tradesman_full_name);


            return  $stmt->execute();
        }catch (PDOException $e){
            error_log("Error on posting a resume:", $e->getMessage());
            return false;
        }


    }


}