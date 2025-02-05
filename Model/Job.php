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

    public function getJobs(int $page = 1, int $limit = 15): array
    {
        $offset = ($page - 1) * $limit; // Calculate the starting point
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE status = 'available' LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

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

    public function addJob($user_id, $client_fullname, $salary, $job_type, $job_description, $location, $status, $deadline): bool {
        try {
            $stmt = $this->db->prepare("INSERT INTO $this->table (user_id, client_fullname, salary, job_type, job_description, location, status, deadline, created_at) VALUES (:user_id, :client_fullname, :salary, :job_type, :job_description, :location, :status, :deadline, NOW())");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':client_fullname', $client_fullname);
            $stmt->bindParam(':salary', $salary);
            $stmt->bindParam(':job_type', $job_type);
            $stmt->bindParam(':job_description', $job_description);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error inserting job: " . $e->getMessage());
            return false;
        }
    }

    public function updateJob($id, $salary, $job_description, $location, $deadline): bool {
        try{
            $stmt = $this->db->prepare("UPDATE $this->table SET salary = :salary, job_description = :job_description, location = :location, deadline = :deadline WHERE id = :id");
            $stmt->bindParam(':salary', $salary);
            $stmt->bindParam(':job_description', $job_description);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
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
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting job: ". $e->getMessage());
            return false;
        }
    }
}