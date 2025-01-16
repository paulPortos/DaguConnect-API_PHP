<?php

namespace Controller;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Admin;
use DaguConnect\Services\Confirm_Password;
use DaguConnect\Services\IfDataExists;
class AdminAuthController extends BaseController
{
    use Confirm_Password;
    use IfDataExists;

    private Admin $adminModel;

    public function __construct(Admin $admin_model)
    {
        $this->db = new config();
        $this->adminModel = $admin_model;
    }

    public function register($username, $email, $password, $confirm_password): void
    {
        $match = $this->checkPassword($password, $confirm_password);

        if (isset($username, $email, $password, $confirm_password)) {
            if ($match) {
                if (!$this->exists($email, 'email', 'admin') && !$this->exists($username, 'username', 'admin')) {
                    $user_data = $this->adminModel->registerUser($username, $email, $password);
                    if ($user_data == true) {
                        $this->jsonResponse(['Message' => 'Registered successfully'], 201);
                    } else {
                        $this->jsonResponse(['Message' => 'Registration failed.'], 400);
                    }
                } else {
                    $this->jsonResponse(['Message' => "Account already exist."], 400);
                }
            } else {
                $this->jsonResponse(['Message' => "Password do not match"], 400);
            }
        } else {
            $this->jsonResponse(['Message' => 'Fields are required to be filled up.'], 400);
        }
    }
    public function login($username, $email, $password){
        if (isset($username, $email, $password)) {
            
        } else {
            $this->jsonResponse(['Message' => 'Fields are required to be filled up.'], 400);
        }
    }
}