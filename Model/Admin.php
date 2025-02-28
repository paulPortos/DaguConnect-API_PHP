<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseController;
use DaguConnect\Core\BaseModel;
use DaguConnect\Services\Confirm_Password;
use PDO;
use PDOException;

class Admin extends BaseModel
{
    use Confirm_Password;
    protected string $table = 'admin';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function registerUser($first_name, $last_name, $username, $email, $password):bool {
        try {
            $hash_password = password_hash($password, PASSWORD_ARGON2ID);

            $query = "INSERT INTO $this->table 
            (first_name, last_name, username, email, password, created_at)
            VALUES (:first_name, :last_name, :username, :email, :password, NOW())
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hash_password);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error in registerUser: ' . $e->getMessage());
            return false;
        }
    }

    public function loginUser($username, $password): bool
    {
        try {
            $query = "SELECT * FROM $this->table WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // If user is found, verify the password
            if ($user && password_verify($password, $user['password'])) {
                return true; // Login successful
            } else {
                return false; // Login failed
            }
        } catch (PDOException $e) {
            error_log("Error logging in: " . $e->getMessage());
            return false;
        }
    }

    public function passwordValidation( $username, $password): bool
    {
        try {
            $query = "SELECT password FROM $this->table WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $stored_password = $stmt->fetchColumn();
            if ($stored_password && password_verify($password, $stored_password)){
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function changeProfilePicture($user_id, $profile_picture) {
        try {
            $query = "UPDATE $this->table SET profile_picture = :profile_picture WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':profile_picture', $profile_picture);
            $stmt->bindParam(':id', $user_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error changing profile picture: " . $e->getMessage());
            return false;
        }
    }

    public function usernameValidation($username): bool {
        try {
            $query = "SELECT * FROM $this->table WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function createToken($username): string
    {
        try {
            // Verify admin exists
            $query = "SELECT * FROM $this->table WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Fetch result
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // If admin does not exist, return null or handle error
            if (!$admin) {
                return ""; // Or you can throw an exception
            }

            // Generate token
            $token = bin2hex(random_bytes(32));

            // Update admin table with the new token
            $updateQuery = "UPDATE $this->table SET token = :token WHERE username = :username";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':token', $token);
            $updateStmt->bindParam(':username', $username);
            $updateStmt->execute();

            return $token;
        } catch (PDOException $e) {
            error_log("Error creating token: " . $e->getMessage());
            return "";
        }
    }

    public function getName($username) {
        try {
            $query = "SELECT first_name, last_name FROM $this->table WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting name: " . $e->getMessage());
            return [];
        }
    }

    public function getEmail($username) {
        try {
            $query = "SELECT email FROM $this->table WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting email: " . $e->getMessage());
            return "";
        }
    }

    public function getPendingBookings(){
        $query = "SELECT COUNT(*) AS totalPending FROM client_booking Where booking_status = 'Pending' ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total pending from bookings and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalPending'];
    }

    public function getActiveBookings(){
        $query = "SELECT COUNT(*) AS totalActive FROM client_booking where booking_status = 'Active' ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total active from bookings and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalActive'];
    }


    public function getCancelledBookings(){
        $query = "SELECT COUNT(*) AS totalCancelled FROM client_booking where booking_status = 'Cancelled' ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total cancelled from bookings and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalCancelled'];
    }

    public function getTradesman(){
        $query = "SELECT COUNT(*) AS totalTradesman FROM users WHERE is_client = 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total tradesman and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalTradesman'];
    }

    public function getClient(){
        $query = "SELECT COUNT(*) AS totalClients FROM users WHERE is_client = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total tradesman and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalClients'];
    }

    public function getCompletedBookings(){
        $query = "SELECT COUNT(*) AS totalCompleted FROM client_booking where booking_status = 'Completed' ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total completed from bookings and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalCompleted'];
    }
    public function getAllBookings(){
        $query = "SELECT COUNT(*) AS total FROM client_booking";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total bookings and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }


    public function getAllActiveUsers() {
        $query = "SELECT COUNT(DISTINCT user_id) AS total FROM user_tokens WHERE token IS NOT NULL AND token != ''";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the active users and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }


    public function getAllUserCount():Int {
        $query = "SELECT COUNT(*) AS count FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result as an associative array and return the count as an integer
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getUsersCountByDate(): array
    {
        $query = "SELECT DATE(created_at) AS created_date, COUNT(*) AS user_created 
              FROM users 
              GROUP BY DATE(created_at) 
              ORDER BY created_date";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSuspendedUsers(){
        $query = "SELECT COUNT(*) AS totalSuspended FROM users WHERE suspend = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total suspended users and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalSuspended'];
    }

    public function getBookingList(){
        $query = "SELECT * FROM client_booking";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersList(){
        $query = "SELECT * FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeAdminPassword($userId, $current_password, $new_password): bool {
        $hashedPassword = null;

        $query = "SELECT password FROM $this->table WHERE id = :id";
        $stmt = $this->db ->prepare($query);
        $stmt->bindParam(":id", $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $hashedPassword = $result['password'];
        }
        $stmt->closeCursor();

        if (password_verify($current_password, $hashedPassword)) {
            $newHashPassword = password_hash($new_password, PASSWORD_ARGON2I);

            $updateQuery = "UPDATE $this->table SET password = :new_password WHERE id = :id";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(":new_password", $newHashPassword);
            $updateStmt->bindParam(":id", $userId, PDO::PARAM_INT);
            return $updateStmt->execute();
        }
        return false;
    }

    public function getAllJobs(): int {
        $query = "SELECT COUNT(*) AS count FROM jobs";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result as an associative array and return the count as an integer
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getJobsList(): array {
        $query = "SELECT * FROM jobs";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableJobs(): int {
        $query = "SELECT COUNT(*) AS count FROM jobs WHERE status = 'Available'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result as an associative array and return the count as an integer
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getOngoingJobs(): int {
        $query = "SELECT COUNT(*) AS count FROM jobs WHERE status = 'On_going'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result as an associative array and return the count as an integer
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getCancelledJobs(): int {
        $query = "SELECT COUNT(*) AS count FROM jobs WHERE status = 'Cancelled'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result as an associative array and return the count as an integer
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getCompletedJobs(): int {
        $query = "SELECT COUNT(*) AS count FROM jobs WHERE status = 'Completed'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result as an associative array and return the count as an integer
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function logoutUser($userId): bool
    {
        try {
            $query = "UPDATE admin SET token = NULL WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error logging out admin: " . $e->getMessage());
            return false;
        }
    }

    public function validateAdminToken($token): ?array
    {
        $query = "SELECT id FROM admin WHERE token = :token LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        return $admin ?: null;
    }

    public function validateresume($user_id, $status_of_approval, $is_approve,$is_active) {
        $query = "UPDATE tradesman_resume 
              SET status_of_approval = :status_of_approval, is_approve = :status , is_active = :is_active
              WHERE user_id = :user_id AND status_of_approval = 'Pending'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status_of_approval', $status_of_approval);
        $stmt->bindParam(':status', $is_approve);
        $stmt->bindParam(':is_active', $is_active);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->rowCount() > 0; // Check if any row was updated
    }


    public function viewUserDetail($user_id){
        $query = "SELECT  * FROM tradesman_resume WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function getAllResumeCount(){
        $query = "SELECT COUNT(*) AS totalResume FROM tradesman_resume WHERE status_of_approval IS NOT NULL";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total resume from resume and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalResume'];

    }

    public function getPendingResume(){
        $query = "SELECT COUNT(*) AS totalPending FROM tradesman_resume WHERE status_of_approval = 'Pending'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total pending from resune and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalPending'];
    }

    public function getApprovedResume(){
        $query = "SELECT COUNT(*) AS totalApproved FROM tradesman_resume WHERE status_of_approval = 'Approved'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total approved from resume and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalApproved'];
    }
    public function getDeclined(){
        $query = "SELECT COUNT(*) AS totalDeclined FROM tradesman_resume WHERE status_of_approval = 'Declined'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total declined from resume and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalDeclined'];

    }

    public function getResumeList(){
        $query = "SELECT * FROM tradesman_resume WHERE status_of_approval IS NOT NULL";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getReportList(){
        $query = "SELECT * FROM reports";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllReportCount(){
        $query = "SELECT COUNT(*) AS totalReports FROM reports";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total reports from reports and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalReports'];
    }

    public function getPendingReport(){
        $query = "SELECT COUNT(*) AS totalPendingReports FROM reports WHERE report_status = 'Pending'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total pending from reports and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalPendingReports'];
    }

    public function getSuspendedReport(){
        $query = "SELECT COUNT(*) AS totalSuspendedReports FROM reports WHERE report_status = 'Suspend'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total resolved from reports and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalSuspendedReports'];
    }

    public function getDissmissReport(){
        $query = "SELECT COUNT(*) AS totalDismissedReports FROM reports WHERE report_status = 'Dismissed'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch all the total resolved from reports and return them as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC)['totalDismissedReports'];
    }

    public function viewReportDetail($id){
        $query = "SELECT  * FROM reports WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateReportStatus($reported_id,$report_status){
     $query = "UPDATE reports SET report_status = :report_status WHERE reported_id = :reported_id";
     $stmt = $this->db->prepare($query);
     $stmt->bindParam(':report_status', $report_status);
     $stmt->bindParam(':reported_id', $reported_id);
     $stmt->execute();
     return $stmt->rowCount() > 0; // Check if any row was updated
    }

    public function updateSuspendStatus($userId,$suspend){
        $query = "UPDATE users SET suspend = :suspend WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':suspend', $suspend);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->rowCount() > 0; // Check if any row was updated

    }

    public function ratinglist(){
        $query = "SELECT * FROM ratings";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function viewRatingDetails($id){
        $query = "SELECT  * FROM ratings WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}