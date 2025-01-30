<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;
use PDOException;

class Job_Application extends BaseModel
{
    protected $table = 'job_applications';
    public function __construct($db){
        parent::__construct($db);
    }

    /**
     * Applies for a job by inserting a new job application into the database.
     *
     * This function creates a new job application record with the provided details.
     *
     * @param int $user_id                The ID of the user applying for the job.
     * @param int $resume_id              The ID of the resume associated with the application.
     * @param int $job_id                 The ID of the job being applied for.
     * @param string $job_name               The name of the job.
     * @param string $job_type               The type of the job (e.g., full-time, part-time).
     * @param string $qualifications_summary A summary of the applicant's qualifications.
     * @param string $status                 The initial status of the job application.
     *
     * @return bool Returns true if the job application was successfully inserted, false otherwise.
     */
    //Apply for a job
    public function applyJob(int $user_id, int $resume_id, int $job_id, string $job_name, string $job_type, string $qualifications_summary, string $status):bool {
        try {
            $query = "INSERT INTO $this->table 
        (user_id, resume_id, job_id, job_name, job_type, qualifications_summary, status, created_at) 
        VALUES (:user_id, :resume_id, :job_id, :job_name, :job_type, :qualifications_summary, :status, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':resume_id', $resume_id);
            $stmt->bindParam(':job_id', $job_id);
            $stmt->bindParam(':job_name', $job_name);
            $stmt->bindParam(':job_type', $job_type);
            $stmt->bindParam(':qualifications_summary', $qualifications_summary);
            $stmt->bindParam(':status', $status);
            return  $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error applying for job: ", $e->getMessage());
            return false;
        }
    }
    /**
     * Retrieves a list of job applications from the database.
     *
     * This function fetches job applications with pagination support.
     *
     * @param int $limit The maximum number of records to return (default: 10).
     * @param int $offset The number of records to skip before starting to return the results (default: 0).
     *
     * @return array An array of job applications. Each element is an associative array representing a job application.
     *               Returns an empty array if an error occurs or no results are found.
     */
    public function getJobApplications(int $limit = 10, int $offset = 0): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting job applications: ", $e->getMessage());
            return [];
        }
    }

    /**
     * Retrieves a specific job application record from the database by its ID.
     *
     * @param int $job_application_id The ID of the job application to be retrieved.
     * @return array An associative array with job application details if found,
     *                      or a string message if the application does not exist or an error occurs.
     */
    public function viewJobApplication(int $job_application_id): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $job_application_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error viewing job application: ", $e->getMessage());
            return "Job application does not exist";
        }
    }

    //Accept or Decline job application
    public function acceptOrDeclineJobApplication(int $job_application_id, string $status): bool
    {
        try{
            $stmt = $this->db->prepare("UPDATE $this->table SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $job_application_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error accepting or declining job application: ", $e->getMessage());
            return false;
        }
    }


    public function viewTradesmanResume($user_id) {
        try {
            $query = "SELECT * FROM user_resume WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error tracing table: ", $e->getMessage());
            return [];
        }
    }
    //To get resume id from the user_resume table using the $user_id that was get from job_application post.
    public function getResumeId($user_id): Int {
        try {
            $query = "SELECT id FROM user_resume WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $id = $stmt->fetch(PDO::FETCH_COLUMN); // Fetch only the `id` column
            return $id !== false ? (int) $id : 0;
        } catch (PDOException $e) {
            error_log("Error tracing table: ", $e->getMessage());
            return 0;
        }
    }

    //Check if user is client or tradesman
    public function checkIsClient($user_id):bool {
        try {
            $query = "SELECT is_client FROM users where user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return (bool) $stmt->fetch(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error tracing table: " . $e->getMessage());
            return false;
        }
    }
}