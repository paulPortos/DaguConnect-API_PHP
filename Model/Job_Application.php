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
    public function applyJob(int $user_id, int $resume_id, int $job_id, int $client_id, string $client_full_name, string $tradesman_full_name, string $client_profile_picture, string $tradesman_profile_picture, string $job_address, string $job_type, string $job_deadline, string $qualifications_summary, string $status):bool {
        try {
            $query = "INSERT INTO $this->table 
        (user_id, resume_id, job_id, client_id, client_fullname, tradesman_fullname, tradesman_profile_picture, client_profile_picture, job_address, job_type, job_deadline, qualification_summary, status,job_date_status ,created_at) 
        VALUES (:user_id, :resume_id, :job_id, :client_id, :client_fullname, :tradesman_fullname, :tradesman_profile_picture, :client_profile_picture, :job_address, :job_type, :job_deadline, :qualification_summary, :status, NOW() ,NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':resume_id', $resume_id);
            $stmt->bindParam(':job_id', $job_id);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':client_fullname', $client_full_name);
            $stmt->bindParam(':tradesman_fullname', $tradesman_full_name);
            $stmt->bindParam(':tradesman_profile_picture', $tradesman_profile_picture);
            $stmt->bindParam(':client_profile_picture', $client_profile_picture);
            $stmt->bindParam(':job_address', $job_address);
            $stmt->bindParam(':job_type', $job_type);
            $stmt->bindParam(':job_deadline', $job_deadline);
            $stmt->bindParam(':qualification_summary', $qualifications_summary);
            $stmt->bindParam(':status', $status);
            return  $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error applying for job: ". $e->getMessage());
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
    public function getMyJobApplications(int $userId, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        try {
            // Get total count of job applications for the user
            $countStmt = $this->db->prepare("SELECT COUNT(*) as total FROM $this->table WHERE user_id = :user_id");
            $countStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $countStmt->execute();
            $totalApplications = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total']; // ✅ Cast to int

            // Get paginated job applications
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE user_id = :user_id LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate total pages
            $totalPages = max(1, ceil($totalApplications / $limit));

            return [
                'applications' => $applications,
                'current_page' => $page,
                'total_pages' => $totalPages
            ];
        } catch (PDOException $e) {
            error_log("Error getting job applications: " . $e->getMessage());
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
            error_log("Error viewing job application: ". $e->getMessage());
            return [];
        }
    }

    /**
     * Updates the status of a job application to either accept or decline it.
     *
     * This function modifies the status of a specific job application in the database.
     * It can be used to accept or decline a job application based on the provided status.
     *
     * @param int $job_application_id The unique identifier of the job application to update.
     * @param string $status The new status to set for the job application (e.g., 'accepted', 'declined').
     *
     * @return bool Returns true if the update was successful and affected at least one row,
     *              false if the update failed or no rows were affected.
     */
    public function changeJobApplicationStatusTradesman(int $user_id, int $job_application_id, string $status): bool
    {
        try{
            $stmt = $this->db->prepare("UPDATE $this->table SET status = :status WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':id', $job_application_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error accepting or declining job application: ". $e->getMessage());
            return false;
        }
    }

    public function changeJobApplicationStatusClient(int $client_id, int $job_application_id, string $status): bool
    {
        try{
            $stmt = $this->db->prepare("UPDATE $this->table SET status = :status,job_date_status = NOW() WHERE id = :id AND client_id = :client_id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':id', $job_application_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error accepting or declining job application: ". $e->getMessage());
            return false;
        }
    }

    public function addCancellationReason(int $job_application_id, string $cancel_reason, string $cancelled_by): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE $this->table SET cancelled_reason = :reason, cancelled_by = :cancelled_by WHERE id = :id");
            $stmt->bindParam(':reason', $cancel_reason);
            $stmt->bindParam(':cancelled_by', $cancelled_by);
            $stmt->bindParam(':id', $job_application_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error adding cancellation reason: ". $e->getMessage());
            return false;
        }
    }

    public function isClient($user_id): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT is_client FROM users WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error checking if user is a client: ". $e->getMessage());
            return false;
        }
    }

    public function getMyJobsApplicants(int $client_id, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        try {
            // Get total count of job applicants for the client
            $countStmt = $this->db->prepare("SELECT COUNT(*) as total FROM $this->table WHERE client_id = :client_id");
            $countStmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
            $countStmt->execute();
            $totalApplicants = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total']; // ✅ Cast to int

            // Get paginated job applicants
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE client_id = :client_id LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate total pages
            $totalPages = max(1, ceil($totalApplicants / $limit));

            return [
                'applicants' => $applicants,
                'current_page' => $page,
                'total_pages' => $totalPages
            ];
        } catch (PDOException $e) {
            error_log("Error getting job applicants: " . $e->getMessage());
            return [];
        }
    }

    public function checkIfAlreadyApplied(int $user_id, int $job_id): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM $this->table 
                WHERE user_id = :user_id AND job_id = :job_id AND status IN ('Pending', 'Active')");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking if user already applied: " . $e->getMessage());
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
            error_log("Error tracing table: ". $e->getMessage());
            return [];
        }
    }
    //To get resume id from the user_resume table using the $user_id that was get from job_application post.
    public function getResumeId($user_id): Int {
        try {
            $query = "SELECT id FROM tradesman_resume WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $id = $stmt->fetch(PDO::FETCH_COLUMN); // Fetch only the `id` column

            return $id !== false ? (int) $id : 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    public function getClientId($job_id){
        try {
            $query = "SELECT user_id FROM jobs where id = :job_id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error tracing table: " . $e->getMessage());
            return false;
        }
    }

    public function getNameAddressDeadlineByJobId($job_id): array
    {
        try {
            $stmt = $this->db->prepare("SELECT client_fullname, address, deadline FROM jobs WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $job_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting job name and type: ". $e->getMessage());
            return [];
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

    public function getTradesmanProfilePictureById($id):string {
        try {
            $stmt = $this->db->prepare("SELECT profile_pic FROM tradesman_resume WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting profile picture: " . $e->getMessage());
            return "";
        }
    }

    public function getTradesmanFullName($user_id) {
        try {
            $query = "SELECT tradesman_full_name FROM tradesman_resume WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting tradesman full name: " . $e->getMessage());
            return "";
        }
    }

    public function getJobType($job_id): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM jobs WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $job_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting job name and type: ". $e->getMessage());
            return [];
        }
    }

    public function isApproved($user_id): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT is_approve FROM tradesman_resume WHERE user_id = :user_id LIMIT 1");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error checking if user is verified: ". $e->getMessage());
            return false;
        }
    }

    public function updateTradesmanProfileInJobApplication($userId, $profile_picture): void
    {
        try {
            $query = "UPDATE $this->table SET profile_pic = :profile_pic WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':profile_pic', $profile_picture);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating tradesman profile in job application: " . $e->getMessage());
        }
    }

    public function checkIfJobApplicationExists($userId, $jobId): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM $this->table 
                WHERE user_id = :user_id 
                AND job_id = :job_id 
                AND cancelled_by IN ('Client', 'Tradesman')");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':job_id', $jobId);
            $stmt->execute();
            return (int) $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking if job application exists: " . $e->getMessage());
            return false;
        }
    }

    public function reApplyJob($userId, $job_id): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE $this->table SET status = 'Pending', cancelled_by = null, cancelled_reason = null WHERE user_id = :user_id AND job_id = :job_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':job_id', $job_id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating job application status: " . $e->getMessage());
            return false;
        }
    }
}