<?php

namespace Controller;

use DaguConnect\Core\BaseController;
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
        $this->adminModel = $admin_model;
    }

    public function register($email, $password, $confirm_password): void {
        $match = $this->checkPassword($password, $confirm_password);

        if (isset($email, $password, $confirm_password)) {
            if ($match) {
                if (!$this->exists($email, 'email', 'admin')) {
                    $user_data = $this->adminModel->registerUser($email, $password);
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
}