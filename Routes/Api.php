<?php

namespace DaguConnect\Routes;

use Controller\AdminAuthController;
use Controller\App\JobController;
use Controller\APP\ResumeController;
use Controller\App\ClientController;
use Controller\App\TradesmanController;
use Controller\AuthenticationController;
use DaguConnect\Core\BaseApi;
use DaguConnect\Model\Admin;
use DaguConnect\Model\Job;
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

            $user_id = $this->requestBody['user_id'];
            $current_password = $this->requestBody['current_password'];
            $new_password = $this->requestBody['new_password'];

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->changePassword($user_id, $current_password, $new_password);
        });

        $this->route('POST', '/user/register', function () {
            $this->responseBodyChecker();

            $first_name = $this->requestBody['first_name'];
            $last_name = $this->requestBody['last_name'];
            $username = $this->requestBody['username'];
            $age = $this->requestBody['age'];
            $email = $this->requestBody['email'];
            $is_client = $this->requestBody['is_client'];
            $password = $this->requestBody['password'];
            $confirm_password = $this->requestBody['confirm_password'];

            $AuthController = new AuthenticationController(new User($this->db));
            $AuthController->register($first_name, $last_name, $username,$age, $email,$is_client ,$password, $confirm_password);
        });

        $this->route('POST', '/user/login', function () {
            $this->responseBodyChecker();

            $email = $this->requestBody['email'];
            $password = $this->requestBody['password'];

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
            $title = $this->requestBody['title'] ?? null;
            $description = $this->requestBody['description'] ?? null;

            // Check for missing data
            if (!$title || !$description) {
                echo json_encode(['message' => 'Title and description are required']);
                http_response_code(400);
                return;
            }

            // Create ResumeController and store resume
            $ResumeController = new ResumeController(new Resume($this->db));
            $ResumeController->StoreResume($userId, $title, $description);
        });

        $this->route('POST', '/user/client/booktradesman', function ($userId) {
            $this->responseBodyChecker();

            $tradesman_id= $this->requestBody['tradesman_id'] ?? null;
            $phone_number = $this->requestBody['phone_number'] ?? null;
            $address = $this->requestBody['address'] ?? null;
            $task_type = $this->requestBody['task_type'] ?? null;
            $task = $this->requestBody['task'] ?? null;

            $ClientController = new ClientController(new Client($this->db));
            $ClientController->BookTradesman($userId,$tradesman_id,$phone_number,$address,$task_type,$task);
        });

        $this->route('GET', '/user/client/getbooking', function ($userId) {
            $ClientBookingController = new ClientController(new Client($this->db));
            $ClientBookingController->GetBookingClient($userId);
        });

        $this->route('PUT', '/user/client/work/status/{booking_id}', function ($userId,$booking_id) {
            $this->responseBodyChecker();

            $work_status = $this->requestBody['work_status'] ?? null;

            $ClientWorkController = new ClientController(new Client($this->db));
            $ClientWorkController->UpdateWorkFromTradesman($userId, $booking_id,$work_status);
        });

        $this->route('GET', '/user/tradesman/getbooking', function ($userId) {

            $TradesmanBookingController = new TradesmanController(new Tradesman($this->db));
            $TradesmanBookingController->GetBookingFromClient($userId);
        });

        $this->route('PUT', '/user/tradesman/bookings/status/{booking_id}', function ($userId,$booking_id) {
            $this->responseBodyChecker();


            $book_status = $this->requestBody['book_status'] ?? null;

            $TradesmanBookingStatus = new TradesmanController(new Tradesman($this->db));
            $TradesmanBookingStatus ->UpdateBookingFromClient($book_status,$booking_id,$userId);
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

        $this->route('GET', '/user/client/job/view/{id}', function ($userId,$id) {

            $jobController = new JobController(new Job($this->db));
            $jobController->viewJob($id);
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