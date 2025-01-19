<?php

namespace DaguConnect\Core;

class BaseController
{
    protected static function jsonResponse($data, int $status =200): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }
}