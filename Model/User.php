<?php

namespace DaguConnect\Model;


use DaguConnect\Core\BaseModel;

use DaguConnect\Services\Confirm_Password;
use PDO;
use PDOException;

class User extends BaseModel
{
    use Confirm_Password;

    protected $table = 'users';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function registerUser($first_name, $last_name, $username, $age, $email, $is_client, $password): bool
    {
        try {
            $hash_password = password_hash($password, PASSWORD_ARGON2ID);

            $query = "INSERT INTO $this->table 
                (first_name, last_name, username, age, suspend, email, is_client, password, created_at)
                VALUES (:first_name, :last_name, :username, :age, false, :email, :is_client, :password, NOW())";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':is_client', $is_client);
            $stmt->bindParam(':password', $hash_password);
            // Execute the query
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log the error message and return false
            error_log('Error in registerUser: ' . $e->getMessage());
            return false;
        }
    }

    public function loginUser($email,$password): bool
    {
        try {
            $query = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user_Exist = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user_Exist && password_verify($password, $user_Exist['password'])){
                return true;
            }else{
                return false;
            }
        } catch (PDOException $e) {
            // Log the error message and return false
            error_log('Error in loginUser: '. $e->getMessage());
            return false;
        }
    }

    public function getUserByEmail( string $email): ?array
    {
        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // Convert TINYINT(1) to true/false
                $user['suspend'] = (bool)$user['suspend'];
                $user['is_client'] = (bool)$user['is_client'];
            }
            return $user ?: null;
        }catch (PDOException $e){
            error_log("error: " . $e->getMessage());
            return [];
        }

    }

    public function getLastInsertId(): int
    {
        return (int) $this->db->lastInsertId();
    }
}