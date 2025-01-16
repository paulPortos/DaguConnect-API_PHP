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
        $query = "SELECT * FROM users WHERE username = :username AND email = :email LIMIT 1";

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
}