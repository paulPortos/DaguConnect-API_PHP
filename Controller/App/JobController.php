<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Job;
use DaguConnect\Services\IfDataExists;

class JobController extends BaseController
{
    private array $job_type;
    private Job $job_model;
    public function __construct(Job $job_model)
    {
        $this->job_type = ['carpentry',
        'painting',
        'welding',
        'electrical_work',
        'plumbing',
        'masonry',
        'roofing',
        'ac_repair',
        'mechanics',
        'drywalling',
        'glazing'];
        $this->db = new config();
        $this->job_model = $job_model;
    }
    use IfDataExists;

    public function addJob($user_id, $client_fullname, $salary, $job_type, $job_description, $status, $deadline): void
    {
        $exist = $this->exists($user_id, "id", "users");
        //Check if user exists
        if (!$exist) {
            $this->jsonResponse(['message' => "User not found"], 404);
            return;
        }

        //Check if all required fields are provided and not empty.
        if (empty($client_fullname) || empty($salary) || empty($job_type) || empty($job_description) || empty($status) || empty($deadline)) {
            $this->jsonResponse(['message' => "All fields are required and must not be empty."], 400);
            return;
        }

        //Check if the job type is valid
        if (!in_array($job_type, $this->job_type, true)) {
            $this->jsonResponse(['message' => "Invalid job type"], 400);
            return;
        }

        if ($this->job_model->addJob($user_id, $client_fullname, $salary, $job_type, $job_description, $status, $deadline)) {
            $this->jsonResponse(['message' => "Job added successfully."], 200);
        } else {
            $this->jsonResponse(['message' => "Failed to add job."], 500);
            return;
        }
    }

    public function getAllJobs(): void
    {
        $jobs = $this->job_model->getJobs();
        if (empty($jobs)) {
            $this->jsonResponse(['message' => "No jobs available"], 200);
            return;
        } else {
            $this->jsonResponse(['jobs' => $jobs], 200);
            return;
        }
    }
}