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

    public function getJobs(): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting jobs: ", $e->getMessage());
            return [];
        }
    }

    public function getJob($id){
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting job: ", $e->getMessage());
            return "Job does not exist";
        }
    }

    public function addJob($user_id, $client_fullname, $salary, $job_type, $job_description, $status, $deadline): bool {
        try {
            $stmt = $this->db->prepare("INSERT INTO $this->table (user_id, client_fullname, salary, job_type, job_description, status, deadline, created_at) VALUES (:user_id, :client_fullname, :salary, :job_type, :job_description, :status, :deadline, NOW())");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':client_fullname', $client_fullname);
            $stmt->bindParam(':salary', $salary);
            $stmt->bindParam(':job_type', $job_type);
            $stmt->bindParam(':job_description', $job_description);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error adding job: ", $e->getMessage());
            return false;
        }
    }

    public function deleteJob() {
        // TODO: Implement job deletion logic
    }
}