<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;
use PDOException;

class Job extends BaseModel
{
    protected string $table = 'jobs';
    public function __construct($db){
        parent::__construct($db);
    }

    public function getJobs(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        try {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as total FROM $this->table WHERE status = 'available'");
            $countStmt->execute();
            $totalJobs = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total']; // ✅ Cast to int to avoid errors

            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE status = 'available' LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalPages = max(1, ceil($totalJobs / $limit));
            return [
                'jobs' => $jobs,
                'current_page' => $page,
                'total_pages' => $totalPages
            ];
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return [];
        }
    }

    public function getAllRecentJobs(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        try {
            $countStmt = $this->db->prepare("SELECT COUNT(*) as total FROM $this->table WHERE status = 'available'");
            $countStmt->execute();
            $totalJobs = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total']; // ✅ Cast to int to avoid errors

            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE status = 'available' ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalPages = max(1, ceil($totalJobs / $limit));
            return [
                'jobs' => $jobs,
                'current_page' => $page,
                'total_pages' => $totalPages
            ];
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return [];
        }
    }

    // Explain this code in detail

    public function viewJob($id){
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting job: " . $e->getMessage());
            return "Job does not exist";
        }
    }

    public function addJob($user_id, $client_fullname, $client_profile_id, $client_profile_picture, $salary, $applicant_limit_count, $job_type, $job_description, $address, $status, $deadline): bool {
        try {
            $stmt = $this->db->prepare("INSERT INTO $this->table (user_id, client_fullname, client_profile_id, client_profile_picture, salary, applicant_limit_count, job_type, job_description, address, status, deadline, created_at) VALUES (:user_id, :client_fullname, :client_profile_id, :client_profile_picture, :salary, :applicant_limit_count, :job_type, :job_description, :address, :status, :deadline, NOW())");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':client_fullname', $client_fullname);
            $stmt->bindParam(':client_profile_id', $client_profile_id);
            $stmt->bindParam(':client_profile_picture', $client_profile_picture);
            $stmt->bindParam(':salary', $salary);
            $stmt->bindParam(':applicant_limit_count', $applicant_limit_count);
            $stmt->bindParam(':job_type', $job_type);
            $stmt->bindParam(':job_description', $job_description);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error inserting job: " . $e->getMessage());
            return false;
        }
    }

    public function updateJob($id, $user_id, $salary, $job_description, $address, $deadline): bool {
        try{
            $stmt = $this->db->prepare("UPDATE $this->table SET salary = :salary, job_description = :job_description, address = :address, deadline = :deadline WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':salary', $salary);
            $stmt->bindParam(':job_description', $job_description);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e){
            error_log("Error updating job: ". $e->getMessage());
            return false;
        }
    }

    public function viewUserJob($user_id): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user's jobs: " . $e->getMessage());
            return [];
        }
    }

    public function deleteJob($id, $user_id): bool{
        try {
            $stmt = $this->db->prepare("DELETE FROM $this->table WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error deleting job: ". $e->getMessage());
            var_dump($e->getMessage());
            return false;
        }
    }

    public function getProfileIdByUserId($user_id): int
    {
        try {
            $stmt = $this->db->prepare("SELECT id FROM client_profile WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting profile: " . $e->getMessage());
            return 0;
        }
    }

    public function getProfilePictureById($id):string {
        try {
            $stmt = $this->db->prepare("SELECT profile_picture FROM client_profile WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting profile picture: " . $e->getMessage());
            return "";
        }
    }

    public function getFullnameById($id):string {
        try {
            $stmt = $this->db->prepare("SELECT full_name FROM client_profile WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting profile picture: " . $e->getMessage());
            return "";
        }
    }
}