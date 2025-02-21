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

    public function StoreResume($email, $user_id,$default_pic,$tradesman_full_name): bool
    {

        $query = "INSERT INTO $this->table 
                (email, user_id,specialties,profile_pic,prefered_work_location,tradesman_full_name,updated_at,created_at,is_active) 
                VALUES(:email, :user_id,'null',:deafault_pic,'null',:tradesman_full_name,NOW(), NOW(),false)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':deafault_pic', $default_pic);
        $stmt->bindParam(':tradesman_full_name', $tradesman_full_name);
        return $stmt->execute();
    }

    /*public function UpdateResume($user_id, $specialties, $profile_pic, $about_me, $prefered_work_location, $work_fee): bool
    {
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
    }*/

    public function UpdateTradesmanProfile($user_Id,$profile_pic):bool{
        $query = "UPDATE $this->table SET profile_pic = :profile_pic WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':profile_pic', $profile_pic);
        $stmt->bindParam(':user_id', $user_Id);
        return $stmt->execute();
    }

    public function SubmitResume($user_id, $specialty, $about_me, $certificate, $Valid_Id_Front, $Valid_Id_Back): bool
    {
        $query = "UPDATE $this->table 
              SET specialty = :specialty, 
                  about_me = :about_me, 
                  certificate = :certificate,
                  valid_id_front = :Valid_Id_Front, 
                  valid_id_Back = :Valid_Id_Back, 
                  status_of_approval = 'Pending'  
              WHERE user_id = :user_id 
              AND (status_of_approval IS NULL OR status_of_approval = 'Declined')";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':specialty', $specialty);
        $stmt->bindParam(':about_me', $about_me);
        $stmt->bindParam(':certificate', $certificate);
        $stmt->bindParam(':Valid_Id_Front', $Valid_Id_Front);
        $stmt->bindParam(':Valid_Id_Back', $Valid_Id_Back);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        // Check if any row was updated
        return $stmt->rowCount() > 0;
    }

    public function getTradesmanDetails($resume_id)
    {
        $query = "SELECT tradesman_full_name, work_fee, profile_pic, email FROM $this->table WHERE id = :resume_id";
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

    public function ExistingTrademsn($tradesman_id){
        $query = "SELECT COUNT(*) FROM $this->table WHERE user_id = :tradesman_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getResumeStatus($user_id)
    {
        $query = "SELECT status_of_approval FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['status_of_approval'] : null;
    }
}