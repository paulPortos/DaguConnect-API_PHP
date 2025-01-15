<?php

namespace Routes;

use Controller\AuthenticationController;
use Core\BaseApi;
use Model\User;

class Api extends BaseApi
{
    public function __construct()
    {
        parent::__construct(); // Calling its constructor

        $this->registeredRoutes();

        $this->handleRequest();
    }

    public function registeredRoutes(): void {
        // Register a route for the AuthenticationController
        $this->route('GET', '/getUser', function () {
            $authController = new AuthenticationController(new User($this->db));
            $authController->index();
        });
    }


}