<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Client;
use DaguConnect\Model\Job_Application;
use DaguConnect\Services\IfDataExists;
use http\Message;

class JobApplicationController extends BaseController
{
    use IfDataExists;
    private $job_type_enum;
    private $application_status;
    private $job_application_type;
    private Job_Application $job_application_model;
    protected config $db;
    public function __construct(Job_Application $job_application)
    {
        $this->db = new config();
        $this->job_application_model = $job_application;
        $this->application_status = [
            'Pending','Active','Declined','Complete','Cancelled'
        ];
        $this->job_type_enum = [
            'Carpentry',
            'Painting',
            'Welding',
            'Electrical_work',
            'Plumbing',
            'Masonry',
            'Roofing',
            'Ac_repair',
            'Mechanics',
            'Cleaning'
        ];

        $this->job_application_type = [
            'Carpenter',
            'Painter',
            'Welder',
            'Electrician',
            'Plumber',
            'Mason',
            'Roofer',
            'Ac_technician',
            'Mechanic',
            'Cleaner'
        ];
    }

    public function apply_job(int $user_id, int $job_id, string $qualifications_summary):void {
        $resume_id = $this->job_application_model->getResumeId($user_id);
        $jobDetails = $this->job_application_model->getJobType($job_id);
        $tradesmanProfilePicture = $this->job_application_model->getTradesmanProfilePictureById($user_id);
        $tradesmanFullname = $this->job_application_model->getTradesmanFullName($user_id);

        if ($this->job_application_model->checkIfAlreadyApplied($user_id, $job_id)) {
            $this->jsonResponse(['message' => 'You have already applied for this job.'], 400);
            return;
        }

        if (!$this->job_application_model->isApproved($user_id)) {
            $this->jsonResponse(['message' => 'Your resume must be approved to apply for jobs.'], 400);
            return;
        }

        $exist = $this->job_application_model->checkIfJobApplicationExists($user_id, $job_id);
        var_dump($exist);

        if($exist){
            $reApplyJob = $this->job_application_model->reApplyJob($user_id, $job_id);
            if ($reApplyJob) {
                $this->jsonResponse(['message' => 'Job reapplication successfully.'], 201);
            } else {
                $this->jsonResponse(['message' => 'Job reapplication failed.'], 500);
            }
            return;
        }

        if ($resume_id == 0) {
            $this->jsonResponse(['message' => 'No resume found for this user.'], 400);
            return;
        }

        // Ensure job type exists
        if (empty($jobDetails['job_type'])) {
            $this->jsonResponse(['message' => 'Invalid job type.'], 400);
            return;
        }

        if (empty($qualifications_summary)){
            $this->jsonResponse(['message' => 'Summary field must be provided'], 400);
            return;
        }

        if (trim(strlen($qualifications_summary)) <= 30) {
            $this->jsonResponse(['message' => 'Summary field must be at least 30 characters'], 400);
            return;
        }

        if (trim(strlen($qualifications_summary)) > 150) {
            $this->jsonResponse(['message' => 'Summary field should not more than 150 characters'], 400);
            return;
        }

        $index = array_search($jobDetails['job_type'], $this->job_application_type, true);
        if ($index === false) {
            $this->jsonResponse(['message' => 'Invalid job type.'], 400);
            return;
        }

        $job_type_application_post = $this->job_type_enum[$index];

        $applyJob = $this->job_application_model->applyJob(
            $user_id,
            $resume_id,
            $job_id,
            $jobDetails['user_id'],
            $jobDetails['client_fullname'],
            $tradesmanFullname,
            $tradesmanProfilePicture,
            $jobDetails['client_profile_picture'],
            $jobDetails['address'],
            $job_type_application_post,
            $jobDetails['deadline'],
            $qualifications_summary,
            "Pending"
        );

        if ($applyJob) {
            $this->jsonResponse(['message' => 'Application successful.'], 201);
        } else {
            $this->jsonResponse(['message' => 'Application failed.'], 500);
        }
    }

    public function getMyJobApplications($userId, int $page, int $limit): void
    {
        // Check if the user is a client (clients cannot access tradesman-specific features)
        if ($this->job_application_model->checkIsClient($userId)) {
            $this->jsonResponse(['message' => 'Access denied, accessing tradesman specific feature'], 403);
            return;
        }

        // Fetch paginated job applications
        $result = $this->job_application_model->getMyJobApplications($userId, $page, $limit);

        // Handle empty results
        if (empty($result['applications'])) {
            $this->jsonResponse(['message' => "You have not applied for any jobs yet."], 200);
            return;
        }

        // Return paginated job applications
        $this->jsonResponse([
            'applications' => $result['applications'],
            'current_page' => $result['current_page'],
            'total_pages' => $result['total_pages']
        ], 200);
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

    public function getMyJobsApplicants($client_id, int $page = 1, int $limit = 10): void
    {
        // Fetch paginated job applicants
        $result = $this->job_application_model->getMyJobsApplicants($client_id, $page, $limit);

        // Handle empty results
        if (empty($result['applicants'])) {
            $this->jsonResponse(['message' => "No applicants found for your jobs."], 200);
            return;
        }

        // Return paginated job applicants
        $this->jsonResponse([
            'applicants' => $result['applicants'],
            'current_page' => $result['current_page'],
            'total_pages' => $result['total_pages']
        ], 200);
    }

    public function changeJobApplicationStatus(int $user_id, int $job_applicationId, string $status, string $cancel_reason = ""):void {
        if (!$this->exists($job_applicationId, "id", "jobs")) {
            $this->jsonResponse(['message' => 'Invalid job application ID'], 400);
            return;
        }
        if($status === 'Cancelled') {
            $message = 'Job application cancelled successfully.';
        } else if ($status === 'Declined') {
            $message = 'Job application declined successfully.';
        } else if ($status === 'Active') {
            $message = 'Job application accepted successfully.';
        } else if ($status === 'Completed') {
            $message = 'Job application completed successfully.';
        } else {
            $this->jsonResponse(['message' => 'Invalid status.'], 400);
            return;
        }

        if ($this->job_application_model->isClient($user_id)) {
            if ($this->job_application_model->changeJobApplicationStatusClient($user_id, $job_applicationId, $status)) {
                $this->jsonResponse(['message' => '$message'], 201);
                if ($status === 'Cancelled') {
                    $this->job_application_model->addCancellationReason($job_applicationId, $cancel_reason, 'Client');
                }
            } else {
                $this->jsonResponse(['message' => "Internal Server Error s"], 500);
            }
        } else {
            if ($this->job_application_model->changeJobApplicationStatusTradesman($user_id, $job_applicationId, $status)) {

                if ($status === 'Cancelled') {
                    $this->job_application_model->addCancellationReason($job_applicationId, $cancel_reason, 'Tradesman');
                }
                $this->jsonResponse(['message' => $message], 201);
            } else {
                $this->jsonResponse(['message' => "Internal Server Error r"], 500);
            }
        }
    }

}