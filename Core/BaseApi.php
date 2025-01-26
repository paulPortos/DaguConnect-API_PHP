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

    /**
     * Constructor for the BaseApi class.
     *
     * Initializes the class by setting up the request body, configuration, and database connection.
     *
     * This constructor performs the following actions:
     * 1. Decodes the JSON input from the request body.
     * 2. Creates a new instance of the config class.
     * 3. Establishes a database connection using the configuration.
     *
     * No parameters are required as it uses the raw input and internal configuration.
     */
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
     * Handle incoming HTTP requests and route them to the appropriate action.
     *
     * This method processes the incoming request by matching the request URI and method
     * against registered routes. It also performs authentication and passes any matched
     * parameters to the action.
     *
     * @throws Exception If there's an error during request handling.
     * @return void
     */
    public function handleRequest(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$requestMethod] ?? [] as $routeUri => $action) {
            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $routeUri); // Convert {param} to regex
            if (preg_match("~^{$pattern}$~", $requestUri, $matches)) {
                array_shift($matches); // Remove full match

                // Validate the token and get user ID
                $userId = $this->Auth($requestUri, $this->db);
                if ($userId === null) {
                    http_response_code(401);
                    echo json_encode(['message' => 'Unauthorized']);
                    return;
                }

                // Call the action with matched params
                call_user_func_array($action, array_merge([$userId], $matches));
                return;
            }
        }
        $this->respondNotFound();
    }

//    public function handleRequest(): void
//    {
//        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//        $requestMethod = $_SERVER['REQUEST_METHOD'];
//
//        if (isset($this->routes[$requestMethod][$requestUri])) {
//            // Validate the token and get user ID
//            $userId = $this->Auth($requestUri, $this->db);
//            if ($userId === null) {
//                http_response_code(401);
//                echo json_encode(['message' => 'Unauthorized']);
//                return;
//            }
//            // Call the action for the route
//            $action = $this->routes[$requestMethod][$requestUri];
//            call_user_func_array($action, [$userId]);
//        } else {
//            $this->respondNotFound();
//        }
//    }



    protected function respondNotFound(): void
    {
        http_response_code(404);
        echo json_encode(['message' => 'Route not found']);
    }
}