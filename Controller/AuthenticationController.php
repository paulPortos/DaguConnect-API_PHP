<?php

namespace Controller;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\User;

class AuthenticationController extends BaseController
{
    private User $userModel;

    public function __construct(User $user_Model)
    {
        $this->userModel = $user_Model;
    }

    public function index(): void {
        $user_data = $this->userModel->readAll();

        if (empty($user_data)) {
            $this->jsonResponse(['Message' => 'No fetched users'], 200);
        } else {
            $this->jsonResponse(['users' => $user_data]);
        }
    }
}