<?php

namespace DaguConnect\Services;

use PDO;

trait TokenHandler
{
    protected $token_table = 'user_tokens';


    public function createToken( $user_id, PDO $db): ?string
    {
        try {
            $token = bin2hex(random_bytes(32));

            $query = "INSERT INTO $this->token_table(user_id, token ,created_at) 
                        VALUES(:user_id, :token, NOW())";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            return $token;
        }catch (\Exception $e){
            error_log("Token generator error",$e->getMessage());
            return false;
        }
    }

    public function DeleteToken($token, PDO $db):bool{

        try{
            $query = "DELETE FROM $this->token_table WHERE token = :token";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }catch (\Exception $e){
            error_log("Token generator error",$e->getMessage());
            return false;
        }

    }


}