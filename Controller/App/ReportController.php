<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Report;
use DaguConnect\Model\Resume;
use DaguConnect\Services\FileUploader;
use DaguConnect\Model\User;

class ReportController extends BaseController
{
    private Report $report;
    private Resume $resume;
    protected $reportDir;
    use FileUploader;
    private User $user;

    public function __construct(Report $report_model,Resume $resume_model, User $user_model){
        $this->report = $report_model;
        $this->resume = $resume_model;
        $this->user = $user_model;
        $this->reportDir = "/uploads/reports/";
    }

    public function reportTradesman($client_id,$tradesman_id,$report_reason,$report_details,$report_attachment){


        if(empty($report_reason) || empty($report_details)|| empty($report_attachment)){
            $this->jsonResponse(['message' => 'Please fill all the fields.'],400);
            return;
        }

        $existing_tradesman = $this->resume->ExistingTrademsn($tradesman_id);

        if(!$existing_tradesman){
            $this->jsonResponse([
                'message' => "Tradesman $tradesman_id does not exist"
            ],400);
            return;
        }

        $exising_report = $this->report->ExistingReport($tradesman_id,$client_id);

        if($exising_report){
            $this->jsonResponse([
                'message' => "Your report to this tradesman is Pending"
            ],400);
            return;
        }
        // get resume ID by tradesman ID and task_type
        $resume_id = $this->resume->getResumeIdByTradesmanId($tradesman_id);

        // Upload the report attachment and get the full URL
        $fullReportUrl = $this->uploadFile($report_attachment, $this->reportDir);

        //gets the values from the resume by resume_id
        $tradesmanDetails = $this->resume->getTradesmanDetails($resume_id['id']);


        //gets the tradesman_fullaname
        $tradesman_fullname = $tradesmanDetails['tradesman_full_name'];

        //gets the tredesman_profile
        $tradesman_profile = $tradesmanDetails['profile_pic'];

        //get the tradesman_email
        $tradesman_email = $tradesmanDetails['email'];

        // Get client details from users table
        $clientDetails = $this->user->getClientDetails($client_id);

        //gets the clients_fullname
        $clients_fullname = $clientDetails['fullname'];



        $report = $this->report->ReportTradesman($tradesman_id,$client_id,$report_reason,$report_details,$tradesman_email,$tradesman_profile,$tradesman_fullname,$clients_fullname,$fullReportUrl);

        if(!$report){
            $this->jsonResponse([
                'message' => 'failed to report tradesman'
            ],400);
        }else{
            $this->jsonResponse([
                'message' => 'successfully reported tradesman'
            ],201);
        }
    }
}