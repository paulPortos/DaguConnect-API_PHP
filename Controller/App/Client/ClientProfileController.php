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

    public function updateProfileData(int $user_id, string $address, string $phone_number): void {
        
        if (empty($address)) {
            $this->jsonResponse(['message' => "Address cannot be empty."], 400);
        }

        $profile = $this->model->updateProfileAddress($user_id, $address, $phone_number);
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
        $existingProfile = $this->model->getClientProfilePicture($user_id);
        $profilePicUrl = $this->uploadFile($profile_picture, $this->profileDir);
        // Get the existing profile picture URL from the database


        $profile = $this->model->updateProfilePicture($user_id, $profilePicUrl);
        if ($profile) {

            // Delete the old profile picture if it exists, is different from the new one,
            // and is not the default.png
            if ($existingProfile &&
                $existingProfile !== $profilePicUrl &&
                !str_contains($existingProfile, 'Default.png') && // Check if it's not default.png
                file_exists($_SERVER['DOCUMENT_ROOT'] . parse_url($existingProfile, PHP_URL_PATH))) {
                if (!unlink($_SERVER['DOCUMENT_ROOT'] . parse_url($existingProfile, PHP_URL_PATH))) {
                    error_log("Failed to delete old profile picture: " . $existingProfile);
                }
            }

            $userProfile = $this->model->updateUserProfilePicture($user_id, $profilePicUrl);
            $jobProfile = $this->model->updateJobClientProfilePicture($user_id, $profilePicUrl);
            $jobApplicationProfile = $this->model->updateJobApplicationClientProfilePicture($user_id, $profilePicUrl);

            if (!$userProfile ) {
                $this->jsonResponse(['message' => "Update client profile failed!"], 400);
                return;
            }
            if (!$jobProfile){
                $this->jsonResponse(['message' => "Update job profile failed!"], 400);
                return;
            }
            if (!$jobApplicationProfile) {
                $this->jsonResponse(['message' => "Update job application profile failed!"], 400);
                return;
            }

            $this->jsonResponse(['message' => "Profile Updated successfully!"], 200);
        } else {
            $this->jsonResponse(['message' => "Update failed."], 400);
        }
    }
}