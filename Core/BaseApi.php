<?php

namespace DaguConnect\Core;

use DaguConnect\Includes\config;
use Exception;
use PDO;
use DaguConnect\Middleware\Middleware;

class BaseApi
{
    use Middleware;
    public PDO $db;
    public config $config;
    private array $routes = [];
    public mixed $requestBody;

    public function __construct()
    {
        $this->config = new config();
        $this->db = $this->config->getDB();

        // Check the Content-Type and parse accordingly
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $this->requestBody = json_decode(file_get_contents('php://input'), true);
        } elseif (str_contains($contentType, 'multipart/form-data')) {
            // For multipart/form-data, use PHP's $_POST and $_FILES
            $this->requestBody = $_POST;
            // You can also access files using $_FILES if needed
        } else {
            $this->requestBody = null;
        }
    }

    public function route(string $method, string $uri, callable $action): void
    {
        $this->routes[$method][$uri] = $action;
    }

    public function handleRequest(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$requestMethod] ?? [] as $routeUri => $action) {
            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $routeUri);
            if (preg_match("~^{$pattern}$~", $requestUri, $matches)) {
                array_shift($matches);

                // Cast numeric parameters to integers
                $matches = array_map(function ($param) {
                    return is_numeric($param) ? (int)$param : $param;
                }, $matches);

                // Validate the token and get user ID
                $userId = $this->Auth($requestUri, $this->db);
                if ($userId === null) {
                    http_response_code(401);
                    echo json_encode(['message' => 'Unauthorized']);
                    return;
                }

                call_user_func_array($action, array_merge([$userId], $matches));
                return;
            }
        }
        $this->respondNotFound();
    }

    protected function respondNotFound(): void
    {
        http_response_code(404);
        echo json_encode(['message' => 'Route not found']);
    }
}
