<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Client_Profile;
use DaguConnect\Model\Report;
use DaguConnect\Model\Resume;
use DaguConnect\Services\FileUploader;
use DaguConnect\Model\User;

class ReportController extends BaseController
{
    private Report $report;
    private Resume $resume;

    private Client_Profile $client_Profile;
    protected $reportDir;
    use FileUploader;
    private User $user;

    public function __construct(Report $report_model,Resume $resume_model, User $user_model,Client_Profile $client_profile_model){
        $this->report = $report_model;
        $this->resume = $resume_model;
        $this->user = $user_model;
        $this->client_Profile = $client_profile_model;
        $this->reportDir = "/uploads/reports/";
    }

    public function reportTradesman($reported_by_id,$reported_id,$report_reason,$report_details,$report_attachment){


        if(empty($report_reason) || empty($report_details)|| empty($report_attachment)){
            $this->jsonResponse(['message' => 'Please fill all the fields.'],400);
            return;
        }

        $existing_tradesman = $this->resume->ExistingTrademan($reported_id);

        if(!$existing_tradesman){
            $this->jsonResponse([
                'message' => "Tradesman $reported_id does not exist"
            ],400);
            return;
        }

        $exising_report = $this->report->ExistingReport($reported_by_id, $reported_id);

        if($exising_report){
            $this->jsonResponse([
                'message' => "Your report to this tradesman is Pending"
            ],400);
            return;
        }
        // get resume ID by tradesman ID and task_type

        // Upload the report attachment and get the full URL
        $fullReportUrl = $this->uploadFile($report_attachment, $this->reportDir);

        //gets the values from the resume by resume_id
        $tradesmanDetails = $this->resume->getTradesmanDetails($reported_id);


        //gets the tradesman_fullaname
        $tradesman_fullname = $tradesmanDetails['tradesman_full_name'];

        //gets the tredesman_profile
        $reporters_profile = $tradesmanDetails['profile_pic'];

        //get the tradesman_email
        $reporters_email = $tradesmanDetails['email'];

        // Get client details from users table
        $clientDetails = $this->user->getClientDetails($reported_by_id);

        //gets the clients_fullname
        $clients_fullname = $clientDetails['fullname'];



        $report = $this->report->ReportTradesman($reported_by_id,$reported_id,$report_reason,$report_details,$reporters_email,$reporters_profile,$tradesman_fullname,$clients_fullname,$fullReportUrl);

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

    public function reportClient($reported_by_id,$reported_id,$report_reason,$report_details,$report_attachment){


        if(empty($report_reason) || empty($report_details)|| empty($report_attachment)){
            $this->jsonResponse(['message' => 'Please fill all the fields.'],400);
            return;
        }

        $existing_client = $this->client_Profile->ExistingClient($reported_id);


        if(!$existing_client){
            $this->jsonResponse([
                'message' => "clients $reported_id does not exist"
            ],400);
            return;
        }

        $exising_report = $this->report->ExistingReport($reported_by_id, $reported_id);

        if($exising_report){
            $this->jsonResponse([
                'message' => "Your report to this tradesman is Pending"
            ],400);
            return;
        }
        // get resume ID by tradesman ID and task_type

        // Upload the report attachment and get the full URL
        $fullReportUrl = $this->uploadFile($report_attachment, $this->reportDir);

        //gets the values from the resume by resume_id
        $tradesmanDetails = $this->resume->getTradesmanDetails($reported_by_id);


        //gets the tradesman_fullaname
        $tradesman_fullname = $tradesmanDetails['tradesman_full_name'];


        // Get client details from users table
        $clientDetails = $this->client_Profile->getclientsDetails($reported_id);

        //gets the clients_fullname
        $clients_fullname = $clientDetails['full_name'];

        //get clients profile
        $reporters_profile = $clientDetails['profile_picture'];

        //get clients email
        $reporters_email = $clientDetails['email'];



        $report = $this->report->ReportClient($reported_by_id,$reported_id,$report_reason,$report_details,$reporters_email,$reporters_profile,$tradesman_fullname,$clients_fullname,$fullReportUrl);

        if(!$report){
            $this->jsonResponse([
                'message' => 'failed to report client'
            ],400);
        }else{
            $this->jsonResponse([
                'message' => 'successfully reported client'
            ],201);
        }

    }
}