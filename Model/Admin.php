<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use DaguConnect\Services\Confirm_Password;
use PDO;

class Admin extends BaseModel
{
    use Confirm_Password;
    protected $table = 'admin';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function registerUser($email, $password):bool {
        $hash_password = password_hash($password, PASSWORD_ARGON2ID);

        $query = "INSERT INTO $this->table 
            (email, password, created_at, updated_at)
            VALUES (:email, :password, NOW(), NOW())
            ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash_password);
        return $stmt->execute();
    }
}