<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Resume;
use Exception;

class ResumeController extends BaseController
{
    private Resume $resumeModel;



    public function __construct(Resume $resume_Model)
    {
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

        // Check if the file was uploaded
        if (isset($profile_pic) && $profile_pic['error'] === UPLOAD_ERR_OK) {
            try {
                $result = $this->resumeModel->resume($email, $user_id, $specialties, $profile_pic, $prefered_work_location, $academic_background, $work_fee,$tradesman_full_name);
                if ($result) {
                    $this->jsonResponse(['message' => 'Resume created successfully.'], 201);
                } else {
                    $this->jsonResponse(['message' => 'Something went wrong.'], 500);
                }
            } catch (Exception $e) {
                $this->jsonResponse(['message' => $e->getMessage()], 500);
            }
        } else {
            $this->jsonResponse(['message' => 'Please upload a valid profile picture.'], 400);
        }
    }



}