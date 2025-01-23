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

    public function registerUser($first_name, $last_name,$username, $age, $email,$is_client, $password,): bool
    {

        $hash_password = password_hash($password, PASSWORD_ARGON2ID);

        $query = "INSERT INTO $this->table 
                (first_name, last_name, username,age,suspend ,email,is_client ,password ,created_at)
                VALUES (:first_name, :last_name,:username,:age, false , :email,:is_client, :password, NOW())
                 ";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':is_client', $is_client);
        $stmt->bindParam(':password', $hash_password);

        return $stmt->execute();
    }

    /*public function EmailVerify($email): bool{
        $query = "UPDATE $this->table SET email_verified_at = NOW() WHERE email = :email AND email_verified_at IS NULL";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':email', $email);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }*/

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



}