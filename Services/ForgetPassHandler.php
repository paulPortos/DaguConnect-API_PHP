<?php

namespace DaguConnect\Services;

use PDO;

trait ForgetPassHandler
{
    protected $forget_table = 'forgot_password';
    public function createOtpForgetPassword($email, $token, PDO $db) {
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

    public function ResetPasswordByToken($otp, $new_password, PDO $db) {
        // Fetch the email associated with the token
        $query = "SELECT email FROM $this->forget_table WHERE token = :otp";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':otp', $otp);
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



    public function ResetPasswordByTokenAdmin($token, $new_password, PDO $db) {
        // Fetch the email and current password associated with the token
        $query = "SELECT email, password FROM admin WHERE email = (SELECT email FROM $this->forget_table WHERE token = :token)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false; // Token not found
        }

        $email = $result['email'];
        $currentPasswordHash = $result['password'];

        // Check if the new password is the same as the old one
        if (password_verify($new_password, $currentPasswordHash)) {
            echo json_encode(['message'=>'The new password is the same as the old one']);
            return false; // Prevent using the same password
        }

        // Hash the new password
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in the admin table
        $updateQuery = "UPDATE admin SET password = :password WHERE email = :email";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(':password', $hashedPassword);
        $updateStmt->bindParam(':email', $email);

        if ($updateStmt->execute()) {
            // Delete the used token from password_reset table
            $deleteQuery = "DELETE FROM $this->forget_table WHERE email = :email";
            $deleteStmt = $db->prepare($deleteQuery);
            $deleteStmt->bindParam(':email', $email);
            $deleteStmt->execute();

            return true;
        }

        return false;
    }
}