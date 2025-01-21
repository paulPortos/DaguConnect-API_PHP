<?php

namespace DaguConnect\Routes;

use Controller\AdminAuthController;
use Controller\AuthenticationController;
use DaguConnect\Core\BaseApi;
use DaguConnect\Model\Admin;
use DaguConnect\Model\User;


class Api extends BaseApi
{

    public function __construct()
    {
        parent::__construct(); // Calling constructor

        $this->registeredRoutes();

        $this->handleRequest();
    }

    public function registeredRoutes(): void {
        // Register a route for the AuthenticationController
        $this->route('POST', '/register/user', function () {
            $this->responseBodyChecker();

            $first_name = $this->requestBody['first_name'];
            $last_name = $this->requestBody['last_name'];
            $age = $this->requestBody['age'];
            $email = $this->requestBody['email'];
            $is_client = $this->requestBody['is_client'];
            $password = $this->requestBody['password'];
            $confirm_password = $this->requestBody['confirm_password'];

            $authController = new AuthenticationController(new User($this->db));
            $authController->register($first_name, $last_name, $age, $email,$is_client ,$password, $confirm_password);
        });

        $this->route('POST', '/admin/register', function () {
            $this->responseBodyChecker();

            $username = $this->requestBody['username'];
            $email = $this->requestBody['email'];
            $password = $this->requestBody['password'];
            $confirm_password = $this->requestBody['confirm_password'];

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->register($username, $email, $password, $confirm_password);
        });

        $this->route('POST', '/admin/login', function () {
            $this->responseBodyChecker();

            $username = $this->requestBody['username'];
            $email = $this->requestBody['email'];
            $password = $this->requestBody['password'];

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->login($username, $email, $password);
        });

        $this->route('PUT', '/admin/change_password', function () {
            $this->responseBodyChecker();

            $user_id = $this->requestBody['user_id'];
            $current_password = $this->requestBody['current_password'];
            $new_password = $this->requestBody['new_password'];

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->changePassword($user_id, $current_password, $new_password);
        });

        $this->route('POST', '/user/login', function () {
            $this->responseBodyChecker();

            $email = $this->requestBody['email'];
            $password = $this->requestBody['password'];

            $authController = new AuthenticationController(new User($this->db));
            $authController->login($email, $password);


        });

        $this->route('GET', '/verify-email', function () {
            $email = $_GET['email'] ?? null;

            $authController = new AuthenticationController(new User($this->db));
            $authController->verifyEmail($email);
        });


    }


    //Check if the response body for POST is empty
    private function responseBodyChecker(): void {
        if (!$this->requestBody || !is_array($this->requestBody)) {
            echo json_encode(['message' => 'Invalid or missing JSON payload']);
            http_response_code(400);
            exit();
        }
    }

}