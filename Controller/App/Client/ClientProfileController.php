<?php

namespace Controller\App\Client;

use DaguConnect\Core\BaseController;

class ClientProfileController extends BaseController
{
    public function getProfile(int $user_id): void {
        $user = $this->model->getUser($user_id);
        if ($user) {
            $this->jsonResponse($user);
        } else {
            $this->jsonResponse(['message' => "User not found."], 404);
        }
    }
}