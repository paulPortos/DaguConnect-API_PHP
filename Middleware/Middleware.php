<?php

namespace DaguConnect\Middleware;

use DaguConnect\Model\Token;
use DaguConnect\Model\Admin;

trait Middleware
{
    public function Auth($requestUri, $db): ?int
    {
        // Endpoints that are protected by the middleware that needs a token
        $protectedRoutes = [
            '/user/tradesman/update/profile',
            '/user/tradesman/submit/resume',
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
            '/user/logout',
            '/user/message/send',
            '/user/chat/get',
            '/client/jobs/delete/{jobId}',
            '/admin/logout',
            '/user/client/booktradesman/{tradesman_Id}',
            '/user/client/rate/tradesman/{booking_id}',
            '/user/client/report/tradesman/{tradesman_Id}',
            '/user/tradesman/report/client/{client_Id}',
            '/client/update/profile_address',
            '/client/update/profile_picture',
            '/user/tradesman/update/resume/details',
            '/client/update/profile_picture',
            '/user/message/{chatId}/{receiver_id}',
            '/client/jobs/view/my_jobs',
            '/user/chat/get',
            '/client/profile',
            '/client/update/profile_picture',
            '/client/job/update/{jobId}',
            '/user/client/job/apply/{jobId}',
            '/user/jobs'
            '/client/job/update/{jobId}',
            '/user/tradesman/view/ratings'
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

                // Check if the route is an admin route
                if (strpos($protectedRoute, '/admin/') === 0) {
                    // Validate the token against the admin table
                    $adminModel = new Admin($db);
                    $adminData = $adminModel->validateAdminToken($token);

                    return $adminData['id'] ?? null;
                } else {
                    // Validate the token against the user_tokens table
                    $tokenModel = new Token($db);
                    $tokenData = $tokenModel->validateToken($token);

                    return $tokenData['user_id'] ?? null;
                }
            }
        }

        return true;
    }
}
