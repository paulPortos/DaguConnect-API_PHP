<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Report;
use DaguConnect\Model\Resume;
use DaguConnect\Model\Client;
use DaguConnect\Model\User;
use DaguConnect\Services\FileUploader;
use Exception;
use DaguConnect\Services\IfDataExists;

class ResumeController extends BaseController
{
    private Resume $resumeModel;

    private Report $reportModel;
    private User $userModel;
    private Client $clientBookingModel;
    use FileUploader;
    protected $targetDir;

    use IfDataExists;


    public function __construct(Resume $resume_Model, Client $client_booking, User $user_model,Report $report_model)
    {
        $this->targetDir = "/uploads/profile_pictures/";
        $this->db = new config();
        $this->initializeBaseUrl(); // Initialize baseUrl from FileUploader
        $this->resumeModel = $resume_Model;
        $this->clientBookingModel = $client_booking;
        $this->userModel = $user_model;
        $this->reportModel = $report_model;
    }

    //get the resume
    public function GetAllResumes(int $page = 1, int $limit = 10): void
    {
        $resume = $this->resumeModel->GetResume($page, $limit);
        //check if there are existing resume's
        if (empty($resume)) {
            $this->jsonResponse(['message' => 'No Resumes Found']);
            return;
        }

        $this->jsonResponse([
            'resume' => $resume['resumes'],
            'current_page' => $resume['current_page'],
            'total_pages' => $resume['total_pages']
        ], 200);
    }

    public function ViewResume($resume_id): void
    {
        $exist = $this->exists($resume_id, 'id', 'tradesman_resume');
        if (!$exist) {
            $this->jsonResponse(['message' => 'Resume not found'], 404);
            return;
        }
        $resume = $this->resumeModel->viewResume($resume_id);

        if ($resume) {
            $this->jsonResponse($resume);
        } else {
            $this->jsonResponse(['message' => 'Failed to view Resume'], 500);
        }
    }

    //post resume of the tradesman
    public function UpdateResume($user_id, $specialties, $profile_pic, $about_me, $prefered_work_location, $work_fee): void
    {
        try {
            // Convert arrays to JSON
            $specialties_json = json_encode($specialties);
            $prefered_work_location_json = json_encode($prefered_work_location);

            // Upload the profile picture and get the full URL
            $fullProfilePicUrl = $this->uploadProfilePic($profile_pic, $this->targetDir);

            // Update the resume in the tradesman_resume table
            $result = $this->resumeModel->UpdateResume($user_id, $specialties_json, $fullProfilePicUrl, $about_me, $prefered_work_location_json, $work_fee);

            if ($result) {
                //updates the profile in the users table
                $this->userModel->updateUserProfile($user_id, $fullProfilePicUrl);
                // Update the tradesman_profile in the client_booking table
                $this->clientBookingModel->updateTradesmanProfileInBookings($user_id, $fullProfilePicUrl);
                //update the Report_profile in the client_booking table
                $this->reportModel->updateTradesmanProfileInReport($user_id, $fullProfilePicUrl);

                $this->jsonResponse(['message' => 'Resume Updated successfully.'], 201);
            } else {
                $this->jsonResponse(['message' => 'Something went wrong.'], 500);
            }
        } catch (Exception $e) {
            $this->jsonResponse(['message' => $e->getMessage()], 500);
        }
    }
}