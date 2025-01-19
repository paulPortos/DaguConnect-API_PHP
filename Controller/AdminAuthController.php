<?php

namespace Controller;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Admin;
use DaguConnect\Services\Confirm_Password;
use DaguConnect\Services\IfDataExists;
use DaguConnect\Services\CheckIfLoggedIn;

class AdminAuthController extends BaseController
{
    use Confirm_Password;
    use IfDataExists;
    use CheckIfLoggedIn;


    private Admin $adminModel;

    public function __construct(Admin $admin_model)
    {
        $this->db = new config();
        $this->adminModel = $admin_model;
    }

    public function register($username, $email, $password, $confirm_password): void
    {
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $this->jsonResponse(['Message' => 'Fields are required to be filled up.'], 400);
            return;
        }

        if (!$this->checkPassword($password, $confirm_password)) {
            BaseController::jsonResponse(['Message' => 'Passwords do not match.'], 400);
            return;
        }

        if ($this->exists($email, 'email', 'admin') || $this->exists($username, 'username', 'admin')) {
            $this->jsonResponse(['Message' => 'Account already exists.'], 400);
            return;
        }

        if ($this->adminModel->registerUser($username, $email, $password)) {
            $this->jsonResponse(['Message' => 'Registered successfully'], 201);
        } else {
            $this->jsonResponse(['Message' => 'Registration failed.'], 400);
        }
    }

    public function login($username, $email, $password): void
    {
        if (isset($username, $email, $password)) {

            if ($this->loggedIn($email, 'admin')){
                $this->jsonResponse(['Message' => 'Already logged in on another device.'], 400);
            } else {
                if ($this->adminModel->loginUser($username, $email, $password)) {
                    $token = $this->adminModel->createToken($email);
                    $this->jsonResponse(['Message' => 'Login successully!', 'Token' => $token], 200);
                } else {
                    $this->jsonResponse(['Message' => 'User does not exist.'], 400);
                }
            }


        } else {
            $this->jsonResponse(['Message' => 'Fields are required to be filled up.'], 400);
        }
    }
}