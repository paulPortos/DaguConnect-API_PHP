<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use DaguConnect\Services\Confirm_Password;
use InvalidArgumentException;
use PDO;

class Admin extends BaseModel
{
    use Confirm_Password;
    protected $table = 'admin';

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

    public function createToken($email): string
    {
        // Check if the user exists
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Generate a unique token
        $token = bin2hex(random_bytes(32)); // Creates a 64-character token

        // Store the token in the database
        $updateQuery = "UPDATE $this->table SET token = :token WHERE email = :email";
        $updateStmt = $this->db->prepare($updateQuery);
        $updateStmt->bindParam(':token', $token);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->execute();

        // Return the token
        return $token;
    }

    public function getAllActiveUsers() {

    }

    public function getAllUserCount():Int {
        $query = "SELECT COUNT(*) AS count FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result as an associative array and return the count as an integer
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }
}