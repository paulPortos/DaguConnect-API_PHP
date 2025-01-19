<?php

namespace Controller;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Services\Confirm_Password;
use DaguConnect\Model\User;
use DaguConnect\Services\IfDataExists;
use DaguConnect\Services\Trim_Password;
use DaguConnect\Services\TokenGenerator;
use DaguConnect\Services\GetIdByEmail;


class AuthenticationController extends BaseController
{
    private User $userModel;


    use Confirm_Password;
    use IfDataExists;
    use Trim_Password;
    use TokenGenerator;
    use GetIdByEmail;


    public function __construct(User $user_Model)
    {
        $this->db = new config();
        $this->userModel = $user_Model;


    }

    public function index(): void
    {
        $user_data = $this->userModel->readAll();

        if (empty($user_data)) {
            $this->jsonResponse(['Message' => 'No fetched users'], 200);
        } else {
            $this->jsonResponse(['users' => $user_data]);
        }
    }

    public function storeUsers($first_name, $last_name, $age, $email, $password, $confirm_password): void
    {
        //Check if password and confirm password match
        $match = $this->checkPassword($password, $confirm_password);
        //trim the password and check if the characters are 6 above
        $CorrectPass = $this->trimPassword($password);

        if (isset($first_name, $last_name, $age, $email, $password, $confirm_password)) {

            if ($CorrectPass) {
                if ($match) {
                    if ($this->exists($email, 'email', 'users')) {
                        $this->jsonResponse(['Message' => "Account already exists."], 400);
                    } else {
                        $this->userModel->registerUser($first_name, $last_name, $age, $email, $password);
                        $this->jsonResponse(['Message' => "Account created successfully."], 201);
                    }
                } else {
                    $this->jsonResponse(['Message' => "Password Do not match."], 400);
                }
            } else {
                $this->jsonResponse(['Message' => 'Password must be at least 6 characters long.'], 400);
            }
        }
    }

    public function login($email, $password): void{
        if($this->userModel->loginUser($email,$password)){ //
            $user = $this->getUserByEmail($email,$this->db->getDB());
            if($user){
                $token = $this->generateToken($user['id'], $this -> db->getDB());
                if($token){
                    $this->jsonResponse(['message' => 'Login successful', 'token' => $token], 200);
                }else{
                    $this->jsonResponse(['message' => 'Token generation failed'], 500);
                }
            }else{
                $this->jsonResponse(['message' => 'User not found'], 404);
            }
        }else{
            $this->jsonResponse(['message' => 'Email or password invalid' ], 400);
        }
    }
}