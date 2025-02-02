<?php

namespace Controller;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Services\Confirm_Password;
use DaguConnect\Model\User;
use DaguConnect\Services\IfDataExists;
use DaguConnect\Services\Trim;
use DaguConnect\Services\TokenGenerator;
use DaguConnect\PhpMailer\Email_Sender;
use DaguConnect\PhpMailer\EmailVerification;
use DaguConnect\Services\ValidateFirstandLastName;
use DaguConnect\Services\ValidateEmailAddress;




class AuthenticationController extends BaseController
{
    private User $userModel;



    use Confirm_Password;
    use ValidateFirstandLastName;
    use IfDataExists;
    use Trim;
    use TokenGenerator;
    use EmailVerification;
    use ValidateEmailAddress;

    public function __construct(User $user_Model)
    {

        $this->db = new config();
        $this->userModel = $user_Model;
    }

    public function register($first_name, $last_name, $username, $age, $email, $is_client ,$password, $confirm_password): void
    {
        //Check if password and confirm password match
        $match = $this->checkPassword($password, $confirm_password);

        //trim the password and check if the characters are 6 above
        $CorrectPass = $this->TrimPassword($password);

        //trim firstname and last name and check if the character is 1 above
        $trimedFirstName = $this->TrimFirstName($first_name);
        $trimedLastName = $this->TrimLastName($last_name);

        //checks if the first name and last name has numerical value or not
        $firstNameandLastnameValidation = $this->validateFirstAndLastName($first_name, $last_name);

        //check if the user enters a valid email
        $emailValidation = $this->validateEmailAddress($email);


        //check if the fields a re all filled up
        if(empty($first_name) || empty($last_name) || empty($username) ||empty($age) || empty($email) || !isset($is_client)|| empty($password) || empty($confirm_password)){
            $this->jsonResponse(['message' => 'Fields are required to be filled up.'], 400);
            return;
        }

        //check if the firstname and has 2 character
        if(!$trimedFirstName){
            $this->jsonResponse(['message' => 'Invalid Firstname must contain two character'], 400);
            return;
        }
        //check if the lastname and has 2 character
        if(!$trimedLastName){
            $this->jsonResponse(['message' => 'Invalid Lastname must contain two character'], 400);
            return;
        }

        //check if the password and confirm password is the same
        if(!$match){
            $this->jsonResponse(['message' => 'Password do not match.'], 400);
            return;
        }
        //check the email if it's validated or not
        if(!$emailValidation){
            $this->jsonResponse(['message' => 'Email is not valid.'], 400);
            return;
        }
        //checks if the user
        //checks the first_name and lastname if it contains a invalid character
        if(!$firstNameandLastnameValidation){
            $this->jsonResponse(['message' => 'First name and Last name should not contain any numerical value.'], 400);
            return;
        }
        //check if the username already exist or not
        if($this->exists($username, 'username', 'users')){
            $this->jsonResponse(['message' => 'Username already exists.'], 400);
            return;
        }
        //check if the email already exist or not
        if($this->exists($email, 'email', 'users')){
            $this->jsonResponse(['message' => 'Account already exists.'], 400);
            return;
        }

        //check if the password is 6 character long or not
        if(!$CorrectPass){
            $this->jsonResponse(['message' => 'Password must be at least 6 characters long.'], 400);
            return;
        }
        //stored the data in the database
        if($this->userModel->registerUser($first_name, $last_name, $username, $age, $email,$is_client, $password,)){
                //send_email verification
                Email_Sender::sendVerificationEmail($email);
                $this->jsonResponse(['message' => "Account created successfully.Please verify your email"], 201);
        }
    }

    public function verifyEmail($email): void
    {
        if (isset($email)) {
            if ($this->EmailVerify($email, $this->db->getDB())) {
                $this->renderView('Verified.html', ['message' => 'Email successfully verified.']);
            } else {
                $this->renderView('Already_Verified.html', ['message' => 'Email Already verified.']);
            }
        } else {
            $this->jsonResponse(['message' => 'Email parameter is missing.'], 400);
        }
    }


    public function login($email, $password): void{

        //gets the id by email inputed
        $user = $this->userModel->getUserByEmail($email);


        //check if the user exist
        if(!$user){
            $this->jsonResponse(['message' => 'User not found'], 404);
            return;
        }

        //check if the email is verified or not
        if($user['email_verified_at'] === null){
            $this->jsonResponse(['message' => 'Email not verified'], 400);
            return;
        }

        //check if the login credentials are right
        if(!$this->userModel->loginUser($email,$password)){
            $this->jsonResponse(['message' => 'Email or password invalid' ], 400);
        }else{
            //generates the token if all the requirements are met
            $token = $this->CreateToken($user['id'], $this -> db->getDB());
            if($token){
                //exclude the pass and the confirm_password from the json response
                unset($user['password'], $user['confirm_password']);

                $response = [
                    'message' => 'Login successful',
                    'token' => $token ,
                    'user' => $user
                ];

                $this->jsonResponse($response, 200);
            }else{
                $this->jsonResponse(['message' => 'Token generation failed'], 500);
            }
        }





        /*if($this->userModel->loginUser($email,$password)){
            $user = $this->getUserByEmail($email,$this->db->getDB());
            if($user){
                $token = $this->generateToken($user['id'], $this -> db->getDB());
                if($token){
                    if($user['email_verified_at'] !== null){
                        $this->jsonResponse(['message' => 'Login successful', 'token' => $token], 200);
                    }else{
                        $this->jsonResponse(['message' => 'Email not verified'], 400);
                    }
                }else{
                    $this->jsonResponse(['message' => 'Token generation failed'], 500);
                }
            }else{
                $this->jsonResponse(['message' => 'User not found'], 404);
            }
        }else{
            $this->jsonResponse(['message' => 'Email or password invalid' ], 400);
        }*/
    }
}