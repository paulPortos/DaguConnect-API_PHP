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





    public function GetResume(int $page, int $limit):array{
        $offset = ($page - 1) * $limit; // Calculate the starting point
        try {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as total FROM $this->table WHERE is_active = 1");
            $countStmt->execute();
            $totalResume = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total']; // ✅ Cast to int to avoid errors

            $query = "SELECT * FROM $this->table WHERE is_active = 1 LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt ->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt ->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $resumes = $stmt->fetchAll(PDO::FETCH_ASSOC);


            // Decode JSON fields for each resume
            foreach ($resumes as &$resume) {
                $resume['specialties'] = json_decode($resume['specialties'], true);
                $resume['prefered_work_location'] = json_decode($resume['prefered_work_location'], true);
            }

            $totalPages = max(1, ceil($totalResume / $limit));
            return [
                'resumes' => $resumes,
                'current_page' => $page,
                'total_pages' => $totalPages
            ];
        } catch (PDOException $e) {
            error_log("Error getting resumes: " . $e->getMessage());
            return [];
        }
    }


    public function viewResume($resume_id){

        $query = "SELECT * FROM $this->table WHERE id = :resume_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':resume_id', $resume_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function StoreResume($email, $user_id,$default_pic,$tradesman_full_name){

        $query = "INSERT INTO $this->table 
                (email, user_id,profile_pic,tradesman_full_name,updated_at,created_at,is_active) 
                VALUES(:email, :user_id,:deafault_pic,:tradesman_full_name,NOW(), NOW(),false)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':deafault_pic', $default_pic);
        $stmt->bindParam(':tradesman_full_name', $tradesman_full_name);
        return $stmt->execute();
    }

    public function UpdateResume($user_id,$specialties, $profile_pic,$about_me, $prefered_work_location,$work_fee){
        $query = "UPDATE $this->table SET
                   specialties = :specialties,
                   profile_pic = :profile_pic,
                   about_me = :about_me,  
                   prefered_work_location = :prefered_work_location,
                   work_fee = :work_fee,
                   updated_at = NOW(),
                   is_active = true
                   WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':specialties', $specialties);
        $stmt->bindParam(':profile_pic', $profile_pic);
        $stmt->bindParam(':about_me', $about_me);
        $stmt->bindParam(':prefered_work_location', $prefered_work_location);
        $stmt->bindParam(':work_fee', $work_fee);
        return $stmt->execute();
    }

    public function getTradesmanDetails($resume_id)
    {
        $query = "SELECT tradesman_full_name, work_fee, profile_pic FROM $this->table WHERE id = :resume_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':resume_id', $resume_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getResumeIdByTradesmanId($tradesman_id): ?array
    {
        $query = "SELECT *FROM $this->table WHERE user_id = :tradesman_id ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

}