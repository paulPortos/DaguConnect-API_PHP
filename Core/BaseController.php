<?php

namespace DaguConnect\Core;

class BaseController
{
    protected function jsonResponse($data, int $status =200): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }

    protected function renderView(string $viewFile, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../Views/' . $viewFile;
    }
}