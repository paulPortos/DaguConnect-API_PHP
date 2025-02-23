<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;
use PDOException;

class Client_Profile extends BaseModel
{
    protected $table = "client_profile";
    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function profile($user_id){
        try {
            $query = "SELECT * FROM $this->table WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('user_id' , $user_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("error: " . $e->getMessage());
            return [];
        }
    }

    public function updateProfileAddress($user_id, $address): bool{
        try {
            $query = "UPDATE $this->table SET address = :address WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('user_id' , $user_id);
            $stmt->bindParam('address' , $address);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            error_log("error: " . $e->getMessage());
            return false;
        }
    }

    public function updateProfilePicture($user_id, $picture): bool{
        try {
            $query = "UPDATE $this->table SET picture = :picture WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('user_id' , $user_id);
            $stmt->bindParam('picture' , $picture);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("error: " . $e->getMessage());
            return false;
        }
    }

    public function initialProfile($full_name, $email, $user_id): bool
    {
        try {
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $default_profile = "http://{$host}/uploads/profile_pictures/Default.png";
            $address = "No address provided";
            $query = "INSERT $this->table (user_id, full_name, email, address, profile_picture) VALUES(:user_id, :full_name, :email, :address, :profile_picture)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('user_id' , $user_id);
            $stmt->bindParam(':full_name' , $full_name);
            $stmt->bindParam(':email' , $email);
            $stmt->bindParam(':address' , $address);
            $stmt->bindParam(':profile_picture' , $default_profile);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            error_log("error: " . $e->getMessage());
            return false;
        }
    }

    public function getClientDetails($client_id)
    {
        $query = "SELECT full_name, email, profile_picture FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $client_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ExistingClient($client_id){
        $query = "SELECT COUNT(*) FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $client_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;

    }

    public function getclientsDetails($client_id)
    {
        $query = "SELECT full_name, email, profile_picture FROM $this->table WHERE user_id = :client_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}