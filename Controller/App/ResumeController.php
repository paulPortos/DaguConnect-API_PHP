<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Resume;
use DaguConnect\Services\FileUploader;
use Exception;


class ResumeController extends BaseController
{
    private Resume $resumeModel;

    use  FileUploader;
    protected $targetDir;

    public function __construct(Resume $resume_Model)
    {
        $this->targetDir = "/uploads/profile_pictures/";
        $this->initializeBaseUrl(); // Initialize baseUrl from FileUploader
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
    public function UpdateResume($user_id, $specialties, $profile_pic,$prefered_work_location, $academic_background, $work_fee): void
    {
            try {
                // Convert arrays to JSON
                $specialties_json = json_encode($specialties);
                $prefered_work_location_json = json_encode($prefered_work_location);
                $academic_background_json = json_encode($academic_background);

                $fullProfilePicUrl = $this->uploadProfilePic($profile_pic, $this->targetDir);




                $result = $this->resumeModel->UpdateResume($user_id, $specialties_json,  $fullProfilePicUrl, $prefered_work_location_json, $academic_background_json, $work_fee);
                if ($result) {
                    $this->jsonResponse(['message' => 'Resume Updated successfully.'], 201);
                } else {
                    $this->jsonResponse(['message' => 'Something went wrong.'], 500);
                }
            } catch (Exception $e) {
                $this->jsonResponse(['message' => $e->getMessage()], 500);
            }

    }





}