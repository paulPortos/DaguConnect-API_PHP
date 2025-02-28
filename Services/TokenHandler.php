<?php

namespace DaguConnect\Services;

use PDO;

trait TokenHandler
{
    protected $token_table = 'user_tokens';

    protected $forget_table = 'forgot_password';
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

    public function createTokenForgetPassword($email, $token, PDO $db) {
        // Delete existing tokens for this email
        $deleteQuery = "DELETE FROM $this->forget_table WHERE email = :email";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bindParam(':email', $email);
        $deleteStmt->execute();

        // Insert new token into password_reset table
        $query = "INSERT INTO $this->forget_table (email, token, created_at) VALUES (:email, :token, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);

        return $stmt->execute();
    }

    public function ResetPasswordByToken($token, $new_password, PDO $db) {
        // Fetch the email associated with the token
        $query = "SELECT email FROM $this->forget_table WHERE token = :token";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false; // Token not found
        }

        $email = $result['email']; // Retrieve the email

        // Hash the new password
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in the users table
        $updateQuery = "UPDATE users SET password = :password WHERE email = :email";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(':password', $hashedPassword);
        $updateStmt->bindParam(':email', $email);

        if ($updateStmt->execute()) {
            // Delete the used token from password_reset table
            $deleteQuery = "DELETE FROM  $this->forget_table  WHERE email = :email";
            $deleteStmt = $db->prepare($deleteQuery);
            $deleteStmt->bindParam(':email', $email);
            $deleteStmt->execute();

            return true;
        }

        return false;
    }
}