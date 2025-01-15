<?php

namespace Controller;

use Core\BaseController;
use DaguConnect\Services\Confirm_Password;
use Model\User;

class AuthenticationController extends BaseController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
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