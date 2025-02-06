<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Resume;
use DaguConnect\Services\FileUploader;
use Exception;
use DaguConnect\Services\IfDataExists;


class ResumeController extends BaseController
{
    private Resume $resumeModel;

    use  FileUploader;
    protected $targetDir;

    use IfDataExists;
    public function __construct(Resume $resume_Model)
    {
        $this->targetDir = "/uploads/profile_pictures/";
        $this->db = new config();
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
            $this->jsonResponse($resume);
        }

    }

    public function ViewResume($resume_id): void
    {
        $exist = $this->exists($resume_id, 'id', 'tradesman_resume');
        if(!$exist){
            $this->jsonResponse(['message'=>'Resume not found'], 404);
            return;
        }
        $resume = $this->resumeModel->viewResume($resume_id);

        if($resume){
            $this->jsonResponse($resume);
        } else{
            $this->jsonResponse(['message'=>'Failed to view Resume'], 500);
        }
    }
    //post resume of the tradesman
    public function UpdateResume($user_id, $specialties, $profile_pic,$about_me,$prefered_work_location, $work_fee): void
    {
            try {
                // Convert arrays to JSON
                $specialties_json = json_encode($specialties);
                $prefered_work_location_json = json_encode($prefered_work_location);

                $fullProfilePicUrl = $this->uploadProfilePic($profile_pic, $this->targetDir);




                $result = $this->resumeModel->UpdateResume($user_id, $specialties_json,  $fullProfilePicUrl,$about_me ,$prefered_work_location_json, $work_fee);
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