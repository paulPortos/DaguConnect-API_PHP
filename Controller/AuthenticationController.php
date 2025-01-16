<?php

namespace Controller;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Services\Confirm_Password;
use DaguConnect\Model\User;
use DaguConnect\Services\IfDataExists;


class AuthenticationController extends BaseController
{
    private User $userModel;



    use Confirm_Password;
    use IfDataExists;

    public function __construct(User $user_Model)
    {
        $this->db = new config();
        $this->userModel = $user_Model;



    }

    public function index(): void {
        $user_data = $this->userModel->readAll();

        if (empty($user_data)) {
            $this->jsonResponse(['Message' => 'No fetched users'], 200);
        } else {
            $this->jsonResponse(['users' => $user_data]);
        }
    }

    public function storeUsers($first_name, $last_name,$age,$email, $password, $confirm_password): void {
            //Check if password and confirm password match
            $match = $this->checkPassword($password, $confirm_password);
            if (isset($first_name, $last_name,$age,$email, $password, $confirm_password)) {
              if ($match) {
                  if ($this->exists($email, 'email', 'users')) {
                      $this->jsonResponse(['Message' => "Account already exists."], 400);
                  }else{

                      $this->userModel->registerUser($first_name, $last_name,$age,$email, $password);
                      $this->jsonResponse(['Message' => "Account created successfully."], 201);
                  }



              }else{
                  $this->jsonResponse(['Message' => "Password Do not match."], 400);
              }
        }else{
                $this->jsonResponse(['Message' => 'Fields are required to be filled up.'], 400);
            }
    }
}