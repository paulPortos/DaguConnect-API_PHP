<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Client;
use DaguConnect\Model\Job_Application;
use http\Message;

class JobApplicationController extends BaseController
{
    private $job_type_enum;
    private $application_status;
    private Job_Application $job_application_model;
    private config $db;
    public function __construct(Job_Application $job_application)
    {
        $this->db = new config();
        $this->job_application_model = $job_application;
        $this->application_status = [
            'pending','active','declined','complete','cancelled'
        ];
        $this->job_type_enum = [
            'carpentry','painting','welding','electrical_work','plumbing','masonry','roofing','ac_repair','mechanics','drywalling','glazing'
        ];
    }

    public function apply_job(int $user_id, int $job_id, string $job_name, string $job_type, string $qualifications_summary, string $status):void {
        $resume_id = $this->job_application_model->getResumeId($user_id);

        if ($resume_id == 0) {
            $this->jsonResponse(['message' => 'No resume found for this user.'], 400);
            return;
        }

        if (empty($job_name) || empty($job_type) || empty($status)) {
            $this->jsonResponse(['message' => 'Missing data'], 400);
            return;
        }

        if (empty($qualifications_summary)){
            $this->jsonResponse(['message' => 'Summary field must be provided'], 400);
            return;
        }

        if(!in_array($status, $this->application_status, true)) {
            $this->jsonResponse(['message' => "Invalid status"], 400);
            return;
        }

        if (trim(strlen($qualifications_summary)) < 150) {
            $this->jsonResponse(['message' => 'Summary field must be at least 150 characters'], 400);
            return;
        }

        if (!in_array($job_type, $this->job_type_enum, true)) {
            $this->jsonResponse(['message' => 'Invalid job type.'], 400);
            return;
        }

        if (!in_array($status, $this->application_status, true)) {
            $this->jsonResponse(['message' => 'Invalid status.'], 400);
            return;
        }

        $applyJob = $this->job_application_model->applyJob($user_id, $resume_id, $job_id, $job_name, $job_type, $qualifications_summary, $status);

        if ($applyJob) {
            $this->jsonResponse(['message' => 'Application successful.'], 201);

        } else {
            $this->jsonResponse(['message' => 'Application failed.'], 500);
        }
    }

    public function getMyJobApplications($user_id):void {
        $isClient = $this->job_application_model->checkIsClient($user_id);

        if ($isClient) {
            $this->jsonResponse(['message' => 'Access denied, accessing tradesman specific feature'], 403);
        }

        $myApplications = $this->job_application_model->getJobApplications($user_id);
        if (!empty($myApplications)){
            $this->jsonResponse(['my_applications' => $myApplications], 200);
        } else {
            $this->jsonResponse(['message' => 'You have not applied for any jobs yet.'], 200);
        }
    }

    public function viewMyJobApplication($jobApplicationId): void
    {
        $jobApplication = $this->job_application_model->viewJobApplication($jobApplicationId);
        if (!empty($jobApplication)){
            $this->jsonResponse(['job_application' => $jobApplication], 200);
        } else {
            $this->jsonResponse(['message' => 'Error getting job application'], 500);
        }

    }
}