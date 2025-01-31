<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Resume;
use DaguConnect\Services\FileUploader;
use Exception;

class ResumeController extends BaseController
{
    private Resume $resumeModel;
    private string $baseUrl;
    use  FileUploader;
    protected $targetDir;

    public function __construct(Resume $resume_Model)
    {
        $this->targetDir = "../uploads/profile_pictures/";
        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST']; // Auto-detect domain
        $this->resumeModel = $resume_Model;
    }
    //get the resume
    public function GetAllResumes():void{
        $resume = $this->resumeModel->GetResume();
        //check if there are existing resume's
        if(empty($resume)){
            $this->jsonResponse(['message'=>'No Resumes Found']);
            return;
        }else{
            $this->jsonResponse(['resumes'=>$resume]);
        }

    }
    //post resume of the tradesman
    public function StoreResume($email, $user_id, $specialties, $profile_pic,$prefered_work_location, $academic_background, $work_fee,$tradesman_full_name): void
    {
        if (empty($email) || empty($specialties) || empty($prefered_work_location) || empty($tradesman_full_name) || empty($work_fee)) {
            $this->jsonResponse(['message' => 'Please fill all the fields.'], 400);
            return;
        }

            try {
                // Upload profile pic
                $relativePath = $this->uploadProfilePic($profile_pic, 'profile_pictures');
                $fullProfilePicUrl = $this->baseUrl . $relativePath;

                $result = $this->resumeModel->resume($email, $user_id, $specialties,  $fullProfilePicUrl, $prefered_work_location, $academic_background, $work_fee,$tradesman_full_name);
                if ($result) {
                    $this->jsonResponse(['message' => 'Resume created successfully.'], 201);
                } else {
                    $this->jsonResponse(['message' => 'Something went wrong.'], 500);
                }
            } catch (Exception $e) {
                $this->jsonResponse(['message' => $e->getMessage()], 500);
            }

    }



}