<?php

namespace DaguConnect\Model;


use DaguConnect\Core\BaseModel;

use DaguConnect\Services\Confirm_Password;
use PDO;

class User extends BaseModel
{
    use Confirm_Password;

    protected $table = 'users';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function readAll(): array {
        $query = "SELECT * FROM $this->table";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function loginUser($email, $password): bool {
        $query = "SELECT * 
        FROM $this->table
        WHERE email_verified_at IS NOT NULL
        AND email = :email
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user_exist = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_exist) {
            if (password_verify($password, $user_exist['password'])) {
                return true;
            }
        }
        return false;
    }

    public function registerUser($first_name, $last_name, $email, $password, $confirm_password, $age):bool {
        if (isset($first_name, $last_name, $email, $password, $confirm_password, $age)) {
            //Check if password and confirm password match
            $match = $this->checkPassword($password, $confirm_password);
            if ($match) {
                $hash_password = password_hash($password, PASSWORD_ARGON2ID);

                $query = "INSERT INTO $this->table 
                (name, email, password, created_at, updated_at)
                VALUES (:name, :email, :password, NOW(), NOW())
                 ";

                $stmt = $this->db->prepare($query);

                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hash_password);

                return $stmt->execute();
            } return false;
        } return false;
    }
}