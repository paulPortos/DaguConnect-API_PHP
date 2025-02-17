<?php

namespace Controller\App\Client;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Chat;
use DaguConnect\Model\Client_Profile;

class ClientProfileController extends BaseController
{
    private Client_Profile $model;
    private config $db;
    private string $string;
    public function __construct(Client_Profile $client_profile) {
        $this->db = new config();
        $this->model = $client_profile;
    }

    public function getProfile(int $user_id): void {
        $user = $this->model->profile($user_id);
        if ($user) {
            $this->jsonResponse($user);
        } else {
            $this->jsonResponse(['message' => "User not found."], 404);
        }
    }

    public function updateProfileAddress(int $user_id, String $address): void {
        $profile = $this->model->updateProfileAddress($user_id, $address);
        if ($profile) {
            $this->jsonResponse(['message' => "Updated successfully!"], 200);
        } else {
            $this->jsonResponse(['message' => "Update failed."], 400);
        }
    }

    public function updateProfilePicture(int $user_id, String $profile_picture): void {
        $profile = $this->model->updateProfilePicture($user_id, $profile_picture);
        if ($profile) {
            $this->jsonResponse(['message' => "Updated successfully!"], 200);
        } else {
            $this->jsonResponse(['message' => "Update failed."], 400);
        }
    }
}