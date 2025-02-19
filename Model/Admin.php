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

    public function registerUser($username, $email, $password):bool {
        $hash_password = password_hash($password, PASSWORD_ARGON2ID);

        $query = "INSERT INTO $this->table 
            (username, email, password, created_at)
            VALUES (:username, :email, :password, NOW())
            ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash_password);
        return $stmt->execute();
    }

    public function loginUser($username, $email, $password): bool
    {

        // Query to check if the user exists
        $query = "SELECT * FROM $this->table WHERE username = :username AND email = :email LIMIT 1";

        // Prepare the statement
        $stmt = $this->db->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);

        // Execute the query
        $stmt->execute();

        // Fetch the user record
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // If user is found, verify the password
        if ($user && password_verify($password, $user['password'])) {
            return true; // Login successful
        }

        return false; // Login failed
    }

    public function passwordValidation($email, $username, $password): bool
    {
        try {
            $query = "SELECT password FROM $this->table WHERE email = :email AND username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
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

    public function emailValidation($email): bool {
        try {
            $query = "SELECT * FROM $this->table WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
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

    public function createToken($email): string
    {
    // Verify admin exists
    $query = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Generate token
    $token = bin2hex(random_bytes(32));

    // Update admin table
    $updateQuery = "UPDATE $this->table SET token = :token WHERE email = :email";
    $updateStmt = $this->db->prepare($updateQuery);
    $updateStmt->bindParam(':token', $token);
    $updateStmt->bindParam(':email', $email);
    $updateStmt->execute();

    return $token;
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

    public function getJobAvailableCount(): int {
        $query = "SELECT COUNT(*) AS count FROM jobs WHERE id = 'available'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result as an associative array and return the count as an integer
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getJobOngoingCount(): int {
        $query = "SELECT COUNT(*) AS count FROM jobs WHERE id = 'ongoing'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result as an associative array and return the count as an integer
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getJobCompletedCount(): int {
        $query = "SELECT COUNT(*) AS count FROM jobs WHERE id = 'done'";
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
}