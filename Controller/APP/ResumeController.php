<?php

namespace Controller\APP;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Resume;

class ResumeController extends BaseController
{
    private Resume $resumeModel;

    public function __construct(Resume $resume_Model)
    {
        $this->resumeModel = $resume_Model;
    }
    public function StoreResume($user_id,$title, $description)
    {
       if(empty($title) || empty($description))
       {
          $this->jsonResponse(['message' => 'Please fill all the fields.'],400);
          return;
       }
       $result =  $this->resumeModel->resume($user_id,$title, $description);
       if($result){
           $this->jsonResponse(['message' => 'Resume created successfully.'],200);
       }else{
           $this->jsonResponse(['message' => 'Something went wrong.'],500);
       }
    }

}