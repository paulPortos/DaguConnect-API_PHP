<?php

namespace DaguConnect\Routes;

use Controller\AdminAuthController;
use Controller\App\JobApplicationController;
use Controller\App\JobController;
use Controller\APP\ResumeController;
use Controller\App\ClientController;
use Controller\App\TradesmanController;
use Controller\AuthenticationController;
use DaguConnect\Core\BaseApi;
use DaguConnect\Model\Admin;
use DaguConnect\Model\Job;
use DaguConnect\Model\Job_Application;
use DaguConnect\Model\Resume;
use DaguConnect\Model\Client;
use DaguConnect\Model\Tradesman;
use DaguConnect\Model\User;
use Exception;


class Api extends BaseApi
{

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(); // Calling constructor

        $this->registeredRoutes();

        $this->handleRequest();
    }

    public function registeredRoutes(): void {
        // Register a route for the AuthenticationController
        $this->route('POST', '/admin/register', function () {
            
            $this->responseBodyChecker();

            ['username' => $username, 'email' => $email, 'password' => $password, 'confirm_password' => $confirm_password] = $this->requestBody;

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->register($username, $email, $password, $confirm_password);
        });

        $this->route('POST', '/admin/login', function () {
            $this->responseBodyChecker();

            ['username' => $username, 'email' => $email, 'password' => $password] = $this->requestBody;

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->login($username, $email, $password);
        });

        $this->route('PUT', '/admin/change_password', function () {
            $this->responseBodyChecker();

            ['user_id' => $user_id, 'current_password' => $current_password, 'new_password' => $new_password] = $this->requestBody;


            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->changePassword($user_id, $current_password, $new_password);
        });

        $this->route('POST', '/user/register', function () {
            $this->responseBodyChecker();

            [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'age' => $age,
                'email' => $email,
                'is_client' => $is_client,
                'password' => $password,
                'confirm_password' => $confirm_password
            ] = $this->requestBody;

            $AuthController = new AuthenticationController(new User($this->db));
            $AuthController->register($first_name, $last_name, $username,$age, $email,$is_client ,$password, $confirm_password);
        });

        $this->route('POST', '/user/login', function () {
            $this->responseBodyChecker();

            ['email' => $email, 'password' => $password] = $this->requestBody;

            $authController = new AuthenticationController(new User($this->db));
            $authController->login($email, $password);
        });

        $this->route('GET', '/verify-email', function () {
            $email = $_GET['email'] ?? null;

            $AuthController = new AuthenticationController(new User($this->db));
            $AuthController->verifyEmail($email);
        });

        $this->route('POST','/user/tradesman/resume', function ($userId) {
            $this->responseBodyChecker();

            // Extract title and description from request body
            $email = $this->requestBody['email'] ?? null;
            $specialties = $this->requestBody['specialties'] ?? null;
            $prefered_work_location = $this->requestBody['prefered_work_location'] ?? null;
            $academic_background = $this->requestBody['academic_background'] ?? null;
            $work_fee = $this->requestBody['work_fee'] ?? null;
            $tradesman_full_name = $this->requestBody['tradesman_full_name'] ?? null;
            $profile_pic = $_FILES['profile_pic'] ?? null;


            // Create ResumeController and store resume
            $ResumeController = new ResumeController(new Resume($this->db));
            $ResumeController->StoreResume($email,$userId,$specialties,$profile_pic,$prefered_work_location,$academic_background,$work_fee,$tradesman_full_name);
        });

        $this->route('POST', '/user/client/booktradesman', function ($userId) {
            $this->responseBodyChecker();

            $tradesman_id= $this->requestBody['tradesman_id'] ?? null;
            $phone_number = $this->requestBody['phone_number'] ?? null;
            $address = $this->requestBody['address'] ?? null;
            $task_type = $this->requestBody['task_type'] ?? null;
            $task_description = $this->requestBody['task_description'] ?? null;
            $booking_date = $this->requestBody['booking_date'] ?? null;

            $ClientController = new ClientController(new Client($this->db));
            $ClientController->BookTradesman($userId,$tradesman_id,$phone_number,$address,$task_type,$task_description,$booking_date );
        });

        $this->route('GET', '/user/client/getbooking', function ($userId) {
            $ClientBookingController = new ClientController(new Client($this->db));
            $ClientBookingController->GetBookingClient($userId);
        });

        $this->route('PUT', '/user/tradesman/bookings/status/{booking_id}', function ($userId,$booking_id) {
            $this->responseBodyChecker();


            $book_status = $this->requestBody['book_status'] ?? null;

            $TradesmanBookingStatus = new TradesmanController(new Tradesman($this->db));
            $TradesmanBookingStatus ->UpdateBookingFromClient($book_status,$booking_id,$userId);
        });

        $this->route('GET', '/user/tradesman/getbooking', function ($userId) {

            $TradesmanBookingController = new TradesmanController(new Tradesman($this->db));
            $TradesmanBookingController->GetBookingFromClient($userId);
        });


        $this->route('PUT', '/user/client/work/status/{booking_id}', function ($userId,$booking_id) {
            $this->responseBodyChecker();

            $work_status = $this->requestBody['work_status'] ?? null;

            $ClientWorkController = new ClientController(new Client($this->db));
            $ClientWorkController->UpdateWorkFromTradesman($userId, $booking_id,$work_status);
        });


        $this->route('POST', '/user/client/create-job', function ($userId) {
            $this->responseBodyChecker();

            $client_fullname = $this->requestBody['client_fullname'] ?? null;
            $salary = $this->requestBody['salary'] ?? null;
            $job_type = $this->requestBody['job_type'] ?? null;
            $job_description = $this->requestBody['job_description'] ?? null;
            $status = $this->requestBody['status'] ?? null;
            $deadline = $this->requestBody['deadline'] ?? null;

            $jobController = new JobController(new Job($this->db));
            $jobController->addJob($userId, $client_fullname, $salary, $job_type, $job_description, $status, $deadline);
        });

        $this->route('GET', '/user/client/jobs', function () {
            $jobController = new JobController(new Job($this->db));
            $jobController->getAllJobs();
        });

        $this->route('GET', '/user/job/view/{id}', function ($userId,$id) {

            $jobController = new JobController(new Job($this->db));
            $jobController->viewJob($id);
        });

        $this->route('GET', '/user/tradesman/job-applications', function ($userId){
            $jobApplicationController = new JobApplicationController(new Job_Application($this->db));
            $jobApplicationController->getMyJobApplications($userId);
        });

        $this->route('GET', '/user/tradesman/job-applications/{jobId}', function ($userId, $jobId){
            $jobApplicationController = new JobApplicationController(new Job_Application($this->db));
            $jobApplicationController->viewMyJobApplication($jobId);
        });

        $this->route('POST', '/user/client/job/apply', function ($userId){
            ['job_id' => $jobId, 'job_name' => $jobName, 'job_type' => $jobType, 'qualification_summary' => $qualificationSummary, 'status' => $status] = $this->requestBody;
            $jobApplicationController = new JobApplicationController(new Job_Application($this->db));
            $jobApplicationController->apply_job($userId, $jobId, $jobName, $jobType, $qualificationSummary, $status);
        });

        $this->route('GET', '/user/getresumes', function () {
            $ResumeController = new ResumeController(new Resume($this->db));
            $ResumeController->GetAllResumes();
        });
    }

    //Check if the response body for POST is empty
    private function responseBodyChecker(): void {
        if (!$this->requestBody || !is_array($this->requestBody)) {
            echo json_encode(['message' => 'Invalid or missing JSON payload']);
            http_response_code(400);
            exit();
        }
    }

}