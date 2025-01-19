<?php

namespace DaguConnect\Model;


use DaguConnect\Core\BaseModel;

use DaguConnect\Services\Confirm_Password;
use DaguConnect\Services\TokenGenerator;
use PDO;

class User extends BaseModel
{
    use Confirm_Password;
    use TokenGenerator;

    protected $table = 'users';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function readAll(): array
    {
        $query = "SELECT * FROM $this->table";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function registerUser($first_name, $last_name, $age, $email, $password,): bool
    {

        $hash_password = password_hash($password, PASSWORD_ARGON2ID);

        $query = "INSERT INTO $this->table 
                (first_name, last_name, age ,email ,password ,created_at)
                VALUES (:first_name, :last_name, :age, :email, :password, NOW())
                 ";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash_password);

        return $stmt->execute();
    }

    public function loginUser($email,$password): bool
    {
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
    }
    public function getUserByEmail(string $email): ?array
    {
        $query = "SELECT * FROM $this->table WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}