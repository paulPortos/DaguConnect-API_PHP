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

    public function resume($email, $user_id, $specialties, $profile_pic, $prefered_work_location, $academic_background, $work_fee, $tradesman_full_name): bool
    {
        try {
            // Convert arrays/objects to JSON strings
            $specialties_json = json_encode($specialties);
            $prefered_work_location_json = json_encode($prefered_work_location);
            $academic_background_json = json_encode($academic_background);

            // Prepare and execute the insert query
            $query = "INSERT INTO $this->table 
                        (email, user_id, specialties, profile_pic,prefered_work_location, academic_background, work_fee, tradesman_full_name, updated_at, created_at) 
                    VALUES(:email, :user_id, :specialties, :profile_pic,:prefered_work_location, :academic_background, :work_fee,:tradesman_full_name, NOW(), NOW())";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':specialties', $specialties_json);
            $stmt->bindParam(':profile_pic', $profile_pic);
            $stmt->bindParam(':prefered_work_location', $prefered_work_location_json);
            $stmt->bindParam(':academic_background', $academic_background_json);
            $stmt->bindParam(':work_fee', $work_fee);
            $stmt->bindParam(':tradesman_full_name', $tradesman_full_name);


            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error on posting a resume: " . $e->getMessage());
            return false;
        }
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


}