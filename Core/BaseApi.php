<?php

namespace Core;

use DaguConnect\config;
use PDO;

class BaseApi
{
    public config $config;
    public PDO $db;
    private array $routes = [];

    public function __construct()
    {
        require_once 'vendor/autoload.php';
        require_once 'includes/config.php';

        $this->config = new config();
        $this->db = $this->config->getDB();
    }


    /**
     * Register a new route
     *
     * @param string $method HTTP method (e.g., GET, POST)
     * @param string $uri Route path (e.g., /posts)
     * @param callable $action Function or method to call
     */

    public function registerRoute(string $method, string $uri, callable $action): void
    {
        $this->routes[$method][$uri] = $action;
    }

    /**
     * Handle incoming requests and route them
     */
    public function handleRequest(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestMethod][$requestUri])) {
            $action = $this->routes[$requestMethod][$requestUri];
            call_user_func($action);
        } else {
            $this->respondNotFound();
        }
    }

    protected function respondNotFound(): void
    {
        http_response_code(404);
        echo json_encode(['message' => 'Route not found']);
    }

    protected function respondNotAllowed(): void
    {
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
    }
}