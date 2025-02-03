<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;

use Exception;
use PDO;
use PDOException;

class Resume extends BaseModel
{
    protected $table = 'tradesman_resume';



    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }





    public function GetResume():array{
        try {
            $query = "SELECT * FROM $this->table";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $resumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode JSON fields for each resume
            foreach ($resumes as &$resume) {
                $resume['specialties'] = json_decode($resume['specialties'], true);
                $resume['prefered_work_location'] = json_decode($resume['prefered_work_location'], true);
                $resume['academic_background'] = json_decode($resume['academic_background'], true);
            }

            return $resumes;
        }catch (PDOException $e){
            error_log("Error getting resume's: ", $e->getMessage());
            return [];
        }

    }

    public function StoreResume($email, $user_id,$default_pic,$tradesman_full_name){

        $query = "INSERT INTO $this->table 
                (email, user_id,profile_pic,tradesman_full_name,updated_at,created_at) 
                VALUES(:email, :user_id,:deafault_pic,:tradesman_full_name,NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':deafault_pic', $default_pic);
        $stmt->bindParam(':tradesman_full_name', $tradesman_full_name);
        return $stmt->execute();
    }

    public function UpdateResume($user_id,$specialties, $profile_pic, $prefered_work_location, $academic_background,$work_fee){
        $query = "UPDATE $this->table SET
                   specialties = :specialties,
                   profile_pic = :profile_pic,
                   prefered_work_location = :prefered_work_location,
                   academic_background = :academic_background,
                   work_fee = :work_fee,
                   updated_at = NOW() WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':specialties', $specialties);
        $stmt->bindParam(':profile_pic', $profile_pic);
        $stmt->bindParam(':prefered_work_location', $prefered_work_location);
        $stmt->bindParam(':academic_background', $academic_background);
        $stmt->bindParam(':work_fee', $work_fee);
        return $stmt->execute();
    }


}