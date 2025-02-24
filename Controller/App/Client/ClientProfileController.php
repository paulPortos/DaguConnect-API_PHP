<?php

namespace Controller\App\Client;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Chat;
use DaguConnect\Model\Client_Profile;
use DaguConnect\Services\FileUploader;
use Exception;

class ClientProfileController extends BaseController
{
    use FileUploader;
    private Client_Profile $model;
    private config $db;
    protected $profileDir;
    private string $string;
    public function __construct(Client_Profile $client_profile) {
        $this->db = new config();
        $this->model = $client_profile;
        $this->profileDir = "/uploads/profile_pictures/";
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
        
        if (empty($address)) {
            $this->jsonResponse(['message' => "Address cannot be empty."], 400);
        }

        $profile = $this->model->updateProfileAddress($user_id, $address);
        if ($profile) {
            $this->jsonResponse(['message' => "Updated successfully!"], 200);
        } else {
            $this->jsonResponse(['message' => "Update failed."], 400);
        }
    }

    /**
     * @throws Exception
     */
    public function updateProfilePicture(int $user_id, $profile_picture): void {
        $profilePicUrl = $this->uploadFile($profile_picture, $this->profileDir);

        $profile = $this->model->updateProfilePicture($user_id, $profilePicUrl);
        if ($profile) {
            $this->jsonResponse(['message' => "Updated successfully!"], 200);
        } else {
            $this->jsonResponse(['message' => "Update failed."], 400);
        }
    }
}