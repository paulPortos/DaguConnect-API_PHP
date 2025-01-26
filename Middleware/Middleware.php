<?php

namespace DaguConnect\Middleware;
use DaguConnect\Model\Token;

trait Middleware
{
    public function Auth($requestUri, $db): ?int
    {
        //endpoints that is protected by the middleware that needs a token
        $protectedRoutes = [
            '/user/resume',
            '/user/booktradesman'
        ];

        if (in_array($requestUri, $protectedRoutes)) {
            $headers = getallheaders();
            $token = null;

            // Extract token from Authorization header
            if (isset($headers['Authorization'])) {
                $token = substr($headers['Authorization'], 7); // Remove "Bearer " from the token string
            }
            if ($token === null) {
                return null; // Token is missing
            }

            $tokenModel = new Token($db);
            $tokenData = $tokenModel->validateToken($token);

            return $tokenData['user_id'] ?? null;
        }
        return true;
    }
}
