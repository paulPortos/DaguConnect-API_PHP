<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Job;
use DaguConnect\Services\IfDataExists;

class JobController extends BaseController
{
    use IfDataExists;
    private array $job_type_enum;
    private array $job_status;
    private Job $job_model;
    public function __construct(Job $job_model)
    {
        $this->job_type_enum = [
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

        $this->job_status = [
            'pending','active','declined','complete','cancelled'
        ];
        $this->db = new config();
        $this->job_model = $job_model;
    }
    use IfDataExists;

    public function addJob($user_id, $client_fullname, $salary, $job_type, $job_description, $address, $status, $deadline): void
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
        if (!in_array($job_type, $this->job_type_enum, true)) {
            $this->jsonResponse(['message' => "Invalid job type"], 400);
            return;
        }

        $addJob = $this->job_model->addJob($user_id, $client_fullname, $salary, $job_type, $job_description, $address, $status, $deadline);
        if ($addJob) {
            $this->jsonResponse(['message' => "Job added successfully."], 200);
        } else {
            $this->jsonResponse(['message' => "Failed to add job."], 500);
            return;
        }
    }

    public function getAllJobs(int $page = 1, int $limit = 10): void
    {
        $result = $this->job_model->getJobs($page, $limit);

        if (empty($result['jobs'])) {
            $this->jsonResponse(['message' => "No jobs available"], 200);
            return;
        }

        $this->jsonResponse([
            'jobs' => $result['jobs'],
            'current_page' => $result['current_page'],
            'total_pages' => $result['total_pages']
        ], 200);
    }


    public function viewJob($id): void
    {
        $exist = $this->exists($id, "id", "jobs");

        if(!$exist) {
            $this->jsonResponse(['message' => "Job not found"], 404);
            return;
        }

        $job = $this->job_model->viewJob($id);
        if ($job) {
            $this->jsonResponse(['job' => $job], 200);
        } else {
            $this->jsonResponse(['message' => "Failed to view job"], 500);
        }
    }

    public function viewUserJobs($user_id): void{
        if (!$this->exists($user_id, "id", "users")) {
            $this->jsonResponse(['message' => "User does not exist."], 404);
        }

        $user_job_post = $this->job_model->viewUserJob($user_id);

        if (!$user_job_post) {
            $this->jsonResponse(['message' => "No job posts found."], 200);
        } else {
            $this->jsonResponse(['job_posts' => $user_job_post], 200);
        }
    }

    public function updateJob($id, $salary, $job_description, $location, $deadline): void{
        if (empty($salary) || empty($job_description) || empty($location) || empty($deadline)) {
            $this->jsonResponse(['message' => "Fields should not be empty."], 400);
        }

        $update_job = $this->job_model->updateJob($id, $salary, $job_description, $location, $deadline);
        if ($update_job) {
            $this->jsonResponse(['message' => "Job updated successfully."], 200);
        } else {
            $this->jsonResponse(['message' => "Failed to update job."], 500);
        }
    }

    public function deleteJob($id, $user_id): void{
        $delete_job = $this->job_model->deleteJob($id, $user_id);
        if ($delete_job) {
            $this->jsonResponse(['message' => "Job deleted successfully."], 200);
        } else {
            $this->jsonResponse(['message' => "Failed to delete job."], 500);
        }
    }
}