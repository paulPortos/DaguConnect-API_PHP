<?php

namespace DaguConnect\Core;

use DaguConnect\Includes\config;
use PDO;
use DaguConnect\Middleware\Middleware;

class BaseApi
{
    use Middleware;
    public config $config;
    public PDO $db;

    private array $routes = [];
    public mixed $requestBody;

    public function __construct()
    {

        $this->requestBody = json_decode(file_get_contents('php://input'), true);
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

    public function route(string $method, string $uri, callable $action): void
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
            // Validate the token and get user ID
            $userId = $this->Auth($requestUri, $this->db);
            if ($userId === null) {
                http_response_code(401);
                echo json_encode(['message' => 'Unauthorized']);
                return;
            }
            // Call the action for the route
            $action = $this->routes[$requestMethod][$requestUri];
            call_user_func_array($action, [$userId]);
        } else {
            $this->respondNotFound();
        }
    }



    protected function respondNotFound(): void
    {
        http_response_code(404);
        echo json_encode(['message' => 'Route not found']);
    }
}