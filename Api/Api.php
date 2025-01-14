<?php

use Controller\AuthenticationController;
use Core\BaseApi;
use Model\User;

class Api extends BaseApi
{
    public function __construct()
    {
        parent::__construct(); // Calling its constructor

        // Set up routes
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Instantiate the controller
        $postController = new AuthenticationController(new User($this->db));

        // Route the request
        if ($requestUri === '/posts' && $requestMethod === 'GET') {
            $postController->index();
        } else {
            header("HTTP/1.0 404 Not Found");
            echo json_encode(['message' => 'Route not found']);
        }
    }
}