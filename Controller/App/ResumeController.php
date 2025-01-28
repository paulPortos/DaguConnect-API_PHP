<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Resume;

class ResumeController extends BaseController
{
    private Resume $resumeModel;

    public function __construct(Resume $resume_Model)
    {
        $this->resumeModel = $resume_Model;
    }
    public function StoreResume($email,$user_id,$specialties,$prefered_work_location,$academic_background,$tradesman_full_name): void
    {
       if(empty($email) ||empty($specialties) || empty($prefered_work_location)|| empty($tradesman_full_name))
       {
          $this->jsonResponse(['message' => 'Please fill all the fields.'],400);
          return;
       }
       $result =  $this->resumeModel->resume($email,$user_id,$specialties,$prefered_work_location,$academic_background,$tradesman_full_name);
       if($result){
           $this->jsonResponse(['message' => 'Resume created successfully.'],201);
       }else{
           $this->jsonResponse(['message' => 'Something went wrong.'],500);
       }
    }

}