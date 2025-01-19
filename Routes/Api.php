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
        $this->route('GET', '/getUser', function () {
            $authController = new AuthenticationController(new User($this->db));
            $authController->index();
        });

        $this->route('POST', '/register/admin', function () {

            $this->responseBodyChecker();

            $username = $this->requestBody['username'];
            $email = $this->requestBody['email'];
            $password = $this->requestBody['password'];
            $confirm_password = $this->requestBody['confirm_password'];

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->register($username, $email, $password, $confirm_password);
        });

        $this->route('POST', '/login/admin', function () {
            $this->responseBodyChecker();

            $username = $this->requestBody['username'];
            $email = $this->requestBody['email'];
            $password = $this->requestBody['password'];

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->login($username, $email, $password);
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