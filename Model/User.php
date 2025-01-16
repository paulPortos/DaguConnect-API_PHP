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

    public function registerUser($first_name, $last_name,$age,$email, $password, ):bool {

        $hash_password = password_hash($password, PASSWORD_ARGON2ID);

        $query = "INSERT INTO $this->table 
                (first_name, last_name, age ,email ,is_client,password ,created_at)
                VALUES (:first_name, :last_name, :age, :email,false, :password, NOW())
                 ";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash_password);

        return $stmt->execute();
    }
}