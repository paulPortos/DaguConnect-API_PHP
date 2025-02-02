<?php

namespace DaguConnect\Middleware;
use DaguConnect\Model\Token;

trait Middleware
{
    public function Auth($requestUri, $db): ?int
    {
        //endpoints that is protected by the middleware that needs a token
        $protectedRoutes = [
            '/user/tradesman/resume',
            '/user/client/booktradesman',
            '/user/tradesman/getbooking',
            '/user/client/create-job',
            '/user/tradesman/bookings/status/{booking_id}',
            '/user/job/view/{id}',
            '/user/tradesman/job-applications',
            '/user/tradesman/job-applications/{jobId}',
            '/user/client/job/apply',
            '/user/tradesman/bookings/status/{booking_id}',
            '/user/client/work/status/{booking_id}',
            '/user/client/getbooking',
            '/user/getresumes'
        ];

        foreach ($protectedRoutes as $protectedRoute) {
            // Convert {param} placeholders to regex
            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $protectedRoute);
            if (preg_match("~^{$pattern}$~", $requestUri)) {
                $headers = getallheaders();
                $token = null;

                // Extract token from Authorization header
                if (isset($headers['Authorization'])) {
                    $token = substr($headers['Authorization'], 7); // Remove "Bearer " from the token string
                }
                if ($token === null) {
                    return null; // Token is missing
                }

                // Validate the token
                $tokenModel = new Token($db);
                $tokenData = $tokenModel->validateToken($token);

                return $tokenData['user_id'] ?? null;
            }
        }

        return true;
    }
}
