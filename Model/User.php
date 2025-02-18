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

    public function registerUser($first_name, $last_name, $username, $birthdate, $email,$profile_pic, $is_client, $password): bool
    {
        try {
            $hash_password = password_hash($password, PASSWORD_ARGON2ID);

            $query = "INSERT INTO $this->table 
                (first_name, last_name, username, birthdate, suspend, email,profile, is_client, password, created_at)
                VALUES (:first_name, :last_name,:username ,:birthdate, false, :email, :profile_pic, :is_client, :password, NOW())";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':birthdate', $birthdate);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':profile_pic', $profile_pic);
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


    public function getClientDetails($user_id)
    {
        $query = "SELECT CONCAT(first_name, ' ', last_name) AS fullname FROM $this->table WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateUserProfile($user_id, $profile_pic_url): void
    {
        try {
            $query = "UPDATE $this->table 
                  SET profile = :profile_pic_url 
                  WHERE id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':profile_pic_url', $profile_pic_url);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating tradesman profile in bookings: " . $e->getMessage());
        }
    }
}