<?php

namespace Controller;

use AllowDynamicProperties;
use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Admin;
use DaguConnect\Services\Confirm_Password;
use DaguConnect\Services\FileUploader;
use DaguConnect\Services\IfDataExists;
use DaguConnect\Services\CheckIfLoggedIn;
use DaguConnect\Services\ValidateEmailAddress;
use DaguConnect\Services\ValidateFirstandLastName;

#[AllowDynamicProperties] class AdminAuthController extends BaseController
{
    use Confirm_Password;
    use IfDataExists;
    use CheckIfLoggedIn;
    use ValidateFirstandLastName;
    use ValidateEmailAddress;
    use FileUploader;
    private Admin $adminModel;

    public function __construct(Admin $admin_model)
    {
        $this->db = new config();
        $this->adminModel = $admin_model;
        $this->profileDir = "/uploads/profile_pictures/";
    }

    public function register($first_name, $last_name, $username, $email, $password, $confirm_password): void
    {
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $this->jsonResponse(['message' => 'Fields are required to be filled up.'], 400);
            return;
        }

        if (!$this->validateFirstAndLastName($first_name, $last_name)) {
            $this->jsonResponse(['message' => 'Name must not contain special characters.'], 400);
            return;
        }

        if (strlen($username) <= 5) {
            $this->jsonResponse(['message' => 'Username must be at least 5 characters long.'], 400);
            return;
        }

        if (str_contains($password, ' ')) {
            $this->jsonResponse(['message' => 'Password should not contain spaces.'], 400);
            return;
        }

        if (strlen($password) <= 8) {
            $this->jsonResponse(['message' => 'Password must be at least 8 characters long.'], 400);
            return;
        } 

        if (!self::confirmPassword($password, $confirm_password)) {
            $this->jsonresponse(['message' => 'Passwords do not match.'], 400);
            return;
        }

        if ($this->exists($email, 'email', 'admin') || $this->exists($username, 'username', 'admin')) {
            $this->jsonResponse(['message' => 'Account already exists.'], 400);
            return;
        }

        if ($this->adminModel->registerUser($first_name, $last_name, $username, $email, $password)) {
            $this->jsonResponse(['message' => 'Account successfully created.'], 201);
        } else {
            $this->jsonResponse(['message' => 'Registration failed.'], 400);
        }
    }

    public function login($username, $password): void
    {

        if (empty($username)){
            $this->jsonResponse(['message' => 'Username field is required.'], 400);
            return;
        }

        if (empty($password)){
            $this->jsonResponse(['message' => 'Password field is required.'], 400);
            return;
        }

        if (strlen($username) < 5) {
            $this->jsonResponse(['message' => 'Username must be at least 5 characters long.'], 400);
            return;
        }
    
        if (strlen($password) <= 8) {
            $this->jsonResponse(['message' => 'Password must be at least 8 characters long.'], 400);
            return;
        }

        if (!$this->adminModel->usernameValidation($username)) {
            $this->jsonResponse(['message' => 'Username does not exists.'], 400);
            return;
        }

        if (!$this->adminModel->passwordValidation($username, $password)){
            $this->jsonResponse(['message' => 'Incorrect password!'], 400);
            return;
        }

        if ($this->isLoggedInUsername($username, 'admin')) {
            $this->jsonResponse(['message' => 'Already logged in on another device!'], 400);
            return;
        }

        if (!$this->adminModel->loginUser($username, $password)) {
            $this->jsonResponse(['message' => 'Internal Server Error.'], 500);
            return;
        }
    
        $token = $this->adminModel->createToken($username);
        $email = $this->adminModel->getEmail($username);
        $name = $this->adminModel->getName($username);
        $this->jsonResponse([
            'message' => 'Login successfully!',
            'admin' => [
                [
                    'first_name' => $name['first_name'] ?? '',
                    'last_name' => $name['last_name'] ?? '',
                    'username' => $username,
                    'email' => $email,
                    'token' => $token,
                ]
            ]
        ], 200);
    }

    public function changePassword($userId, $current_password, $new_password): void {
        $success = $this->adminModel->changeAdminPassword($userId, $current_password, $new_password);
        if ($success) {
            $this->jsonResponse(['message' => 'Password changed successfully.'], 200);
            return;
        }
        $this->jsonResponse(['message' => 'Incorrect password.'], 200);
    }

    public function logout($token): void {
        if ($this->adminModel->logoutUser($token)) {
            $this->jsonResponse(['message' => 'Logged out successfully.'], 200);
        } else {
            $this->jsonResponse(['message' => 'Logout failed.'], 400);
        }
    }

    public function changeProfilePicture($userId, $profile_picture): void{
        $profilePicUrl = $this->uploadFile($profile_picture, $this->profileDir);

        $profile = $this->adminModel->updateProfilePicture($userId, $profilePicUrl);
        if ($profile) {
            $this->jsonResponse(['message' => 'Profile picture updated successfully.'], 200);
        } else {
            $this->jsonResponse(['message' => 'Profile picture update failed.'], 400);
        }
    }

    public function changeUsername($userId, $username): void {

        $name = $this->adminModel->updateUsername($userId, $username);
        if ($name) {
            $this->jsonResponse(['message' => 'Username updated successfully.'], 200);
        } else {
            $this->jsonResponse(['message' => 'Name update failed.'], 400);
        }
    }

    public function forgotPassword($email): void {
        if (empty($email)) {
            $this->jsonResponse(['message' => 'Email field is required.'], 400);
            return;
        }

        if ($this->validateEmailAddress($email)) {
            $this->jsonResponse(['message' => 'Invalid email.'], 400);
            return;
        }

        if (!$this->exists($email, 'email', 'admin')) {
            $this->jsonResponse(['message' => 'Email does not exists.'], 400);
            return;
        }

        $otp = $this->generateOTP();

        $token = $this->adminModel->forgotPassword($email);
        if ($token) {
            $this->jsonResponse(['message' => 'Token sent to your email.'], 200);
        } else {
            $this->jsonResponse(['message' => 'Internal Server Error.'], 500);
        }
    }

    private function generateOTP(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}