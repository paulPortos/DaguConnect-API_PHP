<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Report;
use DaguConnect\Model\Resume;

class ReportController extends BaseController
{
    private Report $report;
    private Resume $resume;

    public function __construct(Report $report_model,Resume $resume_model){
        $this->report = $report_model;
        $this->resume = $resume_model;
    }

    public function reportTradesman($client_id,$tradesman_id,$report_reason,$report_details,){

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
        $report = $this->report->ReportTradesman($tradesman_id,$client_id,$report_reason,$report_details);
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