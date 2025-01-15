<?php

namespace DaguConnect\Routes;

use Controller\AuthenticationController;
use DaguConnect\Core\BaseApi;
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
    }


}