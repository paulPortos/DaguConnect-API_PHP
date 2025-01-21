<?php

namespace DaguConnect\Core;

use DaguConnect\Includes\config;
use PDO;
use DaguConnect\Model\Token;

class BaseApi
{
    public config $config;
    public PDO $db;

    private array $routes = [];
    public mixed $requestBody;

    public function __construct()
    {
        require_once __DIR__ . '/../vendor/autoload.php';
        require_once __DIR__ . '/../Includes/config.php';
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
        $headers = apache_request_headers();

        // Check for Bearer token in Authorization header
        $token = null;
        if(isset($headers['Authorization'])) {
            $token = substr($headers['Authorization'], 7); // Remove "Bearer " from the token string
        }


        if (isset($this->routes[$requestMethod][$requestUri])) {

            // If token is required, validate it
            if ($this->isTokenRequired($requestUri)) {
                if (!$this->middleware($token)) {
                    http_response_code(401);
                    echo json_encode(['message' => 'Unauthorized']);
                    exit();
                }
            }

            $action = $this->routes[$requestMethod][$requestUri];
            call_user_func($action);
        } else {
            $this->respondNotFound();
        }
    }

    //endpoints that is protected by the middleware that needs a token
    private function isTokenRequired(string $uri): bool
    {
        // Define routes that require authentication
        $protectedRoutes = [
            '/getUser',
        ];

        return in_array($uri, $protectedRoutes);
    }

    //gets the token from the token table
    public function middleware(?string $token): ?int
    {
        if ($token === null) {
            return false; // Token is missing
        }

        $tokenModel = new Token($this->db);
        $tokenData = $tokenModel->validateToken($token);

        return $tokenData['user_id'] ?? null;
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