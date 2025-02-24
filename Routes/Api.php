<?php

namespace DaguConnect\Routes;

use Controller\AdminAuthController;
use Controller\App\ChatController;
use Controller\App\Client\ClientProfileController;
use Controller\App\JobApplicationController;
use Controller\App\JobController;
use Controller\App\RatingsController;
use Controller\App\ReportController;
use Controller\APP\ResumeController;
use Controller\App\ClientController;
use Controller\App\TradesmanController;
use Controller\AuthenticationController;
use Controller\Web\DashboardController;
use DaguConnect\Core\BaseApi;
use DaguConnect\Model\Admin;
use DaguConnect\Model\Chat;
use DaguConnect\Model\Client_Profile;
use DaguConnect\Model\Job;
use DaguConnect\Model\Job_Application;
use DaguConnect\Model\Rating;
use DaguConnect\Model\Report;
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
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: *");

        // Handle preflight OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit(0);
        }
        parent::__construct(); // Calling constructor

        $this->registeredRoutes();

        $this->handleRequest();
    }

    public function registeredRoutes(): void
    {

        // Register a route for the AuthenticationController
        $this->route('POST', '/admin/register', function () {

            $this->responseBodyChecker();

            ['first_name' => $first_name, 'last_name' => $last_name, 'username' => $username, 'email' => $email, 'password' => $password, 'confirm_password' => $confirm_password] = $this->requestBody;

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->register($first_name, $last_name, $username, $email, $password, $confirm_password);
        });

        $this->route('POST', '/admin/login', function () {
            $this->responseBodyChecker();

            ['username' => $username, 'password' => $password] = $this->requestBody;

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->login($username, $password);
        });

        $this->route('DELETE', '/admin/logout', function ($adminId) {
            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->logout($adminId);
        });

        $this->route('PUT', '/admin/change_password', function () {
            $this->responseBodyChecker();

            ['user_id' => $user_id, 'current_password' => $current_password, 'new_password' => $new_password] = $this->requestBody;

            $adminController = new AdminAuthController(new Admin($this->db));
            $adminController->changePassword($user_id, $current_password, $new_password);
        });

        $this->route('GET', '/admin/users/statistic', function () {
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->userStatistics();
        });

        $this->route('GET', '/admin/job/statistic', function () {
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->jobsStatistics();
        });

        $this->route('GET', '/admin/booking/statistics', function () {
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->bookingStatistics();
        });

        $this->route('GET', '/admin/user/management', function () {
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->userManagement();
        });

        $this->route('GET', '/admin/resume/management', function () {
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->resumeManagement();
        });
        $this->route('GET', '/admin/report/management', function () {
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->reportManagement();
        });


        $this->route('PUT', '/admin/validate/Resume/{tradesman_id}', function ($user_id, $tradesman_id) {
            $this->responseBodyChecker();

            $status_of_approval = $this->requestBody['status_of_approval'];

            $adminController = new DashboardController(new Admin($this->db));
            $adminController->validateResume($tradesman_id, $status_of_approval);
        });

        $this->route('PUT', '/admin/suspend/report/{reported_id}', function ($user_id,$reported_id) {
            $this->responseBodyChecker();

            $report_status = $this->requestBody['report_status'];

            $adminController = new DashboardController(new Admin($this->db));
            $adminController->suspendedReported($reported_id,$report_status);
        });



        $this->route('GET', '/admin/view/user/details/{tradesman_id}', function ($user_id,$tradesman_id) {
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->viewUserDetail($tradesman_id);
        });

        $this->route('GET', '/admin/view/report/details/{id}', function ($user_id,$id) {
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->viewReportDetail($id);
        });

        $this->route('GET', '/admin/rating/management',function (){
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->ratingManagement();
        });

        $this->route('GET', '/admin/view/rating/details/{id}', function ($id) {
            $adminController = new DashboardController(new Admin($this->db));
            $adminController->viewRatingDetail($id);
        });


        $this->route('POST', '/user/register', function () {
            $this->responseBodyChecker();

            [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'birthdate' => $birthdate,
                'email' => $email,
                'is_client' => $is_client,
                'password' => $password,
                'confirm_password' => $confirm_password
            ] = $this->requestBody;

            $AuthController = new AuthenticationController(new User($this->db), new Resume($this->db), new Client_Profile($this->db));
            $AuthController->register($first_name, $last_name, $username,$birthdate, $email,$is_client ,$password, $confirm_password);
        });

        $this->route('POST', '/user/login', function () {
            $this->responseBodyChecker();

            ['email' => $email, 'password' => $password] = $this->requestBody;

            $authController = new AuthenticationController(new User($this->db),new Resume($this->db),new Client_Profile($this->db));
            $authController->login($email, $password);
        });

        $this->route('DELETE', '/user/logout', function () {

            $authController = new AuthenticationController(new User($this->db), new Resume($this->db), new Client_Profile($this->db));
            $authController->logout();
        });

        $this->route('GET', '/verify-email', function () {
            $email = $_GET['email'] ?? null;

            $AuthController = new AuthenticationController(new User($this->db),new Resume($this->db),new Client_Profile($this->db));
            $AuthController->verifyEmail($email);
        });

        /*$this->route('POST','/user/tradesman/update/resume', function ($userId) {
            $this->responseBodyChecker();

            // Extract title and description from request body
            $specialties = $this->requestBody['specialties'] ;
            $profile_pic = $_FILES['profile_pic'];
            $about_me = $this->requestBody['about_me'] ;
            $prefered_work_location = $this->requestBody['prefered_work_location'] ;
            $work_fee = $this->requestBody['work_fee'];

            // Create ResumeController and store resume
            $ResumeController = new ResumeController(new Resume($this->db), new Client($this->db),new User($this->db),new Report($this->db));
            $ResumeController->UpdateResume($userId,$specialties,$profile_pic,$about_me,$prefered_work_location,$work_fee);
        });*/

        $this->route('POST','/user/tradesman/update/profile', function ($userId){
            $this->responseBodyChecker();
            $profile_pic = $_FILES['profile_pic'];

            $ResumeController = new ResumeController(new Resume($this->db), new Client($this->db),new User($this->db),new Report($this->db));
            $ResumeController->updateTradesmanProfile($userId, $profile_pic);
        });

        $this->route('POST','/user/tradesman/submit/resume', function ($userId){
            $this->responseBodyChecker();

            $specialty = $this->requestBody['specialty'] ;
            $about_me = $this->requestBody['about_me'];
            $work_fee = $this->requestBody['work_fee'];
            $prefered_location = $this->requestBody['prefered_location'];
            $document = $_FILES['document'];
            $valid_id_front = $_FILES['valid_id_front'];
            $valid_id_back = $_FILES['valid_id_back'];

            $ResumeController = new ResumeController(new Resume($this->db), new Client($this->db),new User($this->db),new Report($this->db));
            $ResumeController->submitResume($userId,$specialty,$about_me,$prefered_location,$work_fee,$document,$valid_id_front,$valid_id_back);
        });

        $this->route('PUT', '/user/tradesman/update/resume/details', function ($userId){
            $this->responseBodyChecker();

            $about_me = $this->requestBody['about_me'];
            $prefered_work_location = $this->requestBody['prefered_work_location'];
            $work_fee = $this->requestBody['work_fee'];
            $phone_number = $this->requestBody['phone_number'];

            $ResumeController = new ResumeController(new Resume($this->db), new Client($this->db),new User($this->db),new Report($this->db));
            $ResumeController->updateTradesmanDetails($userId,$about_me,$prefered_work_location,$work_fee,$phone_number);
        });


        $this->route('POST', '/user/client/rate/tradesman/{booking_id}', function ($userId,$tradesman_id) {
            $this->responseBodyChecker();
            $message = $this->requestBody['message'];
            $rating = $this->requestBody['rating'];

            $RatingController = new RatingsController(new Rating($this->db),new Client_Profile($this->db),new Client($this->db),new Resume($this->db));
            $RatingController->rateTradesman($userId,$tradesman_id,$rating,$message);
        });
        $this->route('GET', '/user/client/view/tradesman/rating/{tradesman_id}', function ($user_id,$tradesman_Id) {
            $RatingController = new RatingsController(new Rating($this->db),new Client_Profile($this->db),new Client($this->db),new Resume($this->db));
            $RatingController->viewratingsById($tradesman_Id);
        });

        $this->route('GET', '/user/tradesman/view/ratings', function ($tradesman_id) {
            $RatingController = new RatingsController(new Rating($this->db),new Client_Profile($this->db),new Client($this->db),new Resume($this->db));
            $RatingController->viewratings($tradesman_id);
        });

        $this->route('POST', '/user/client/report/tradesman/{tradesman_Id}', function($client_Id,$tradesman_Id){
            $this->responseBodyChecker();

            $report_reason = $this->requestBody['report_reason'];
            $report_details = $this->requestBody['report_details'];
            $report_attachment = $_FILES['report_attachment'];

            $ReportController = new ReportController(new Report($this->db),new Resume($this->db), new User($this->db),new Client_Profile($this->db));
            $ReportController->reportTradesman($client_Id,$tradesman_Id,$report_reason,$report_details,$report_attachment);
        });

        $this->route('POST', '/user/tradesman/report/client/{client_Id}', function($tradesman_Id,$client_Id){
            $this->responseBodyChecker();

            $report_reason = $this->requestBody['report_reason'];
            $report_details = $this->requestBody['report_details'];
            $report_attachment = $_FILES['report_attachment'];

            $ReportController = new ReportController(new Report($this->db),new Resume($this->db), new User($this->db),new Client_Profile($this->db));
            $ReportController->reportClient($tradesman_Id,$client_Id,$report_reason,$report_details,$report_attachment);
        });



        $this->route('POST', '/user/client/booktradesman/{tradesman_Id}', function ($userId,$tradesman_id) {
            $this->responseBodyChecker();

            $phone_number = $this->requestBody['phone_number'] ?? null;
            $address = $this->requestBody['address'] ?? null;
            $task_type = $this->requestBody['task_type'] ?? null;
                $task_description = $this->requestBody['task_description'] ?? null;
            $booking_date = $this->requestBody['booking_date'] ?? null;

            $ClientController = new ClientController(new Client($this->db),new Resume($this->db),new User($this->db));
            $ClientController->BookTradesman($userId,$tradesman_id,$phone_number,$address,$task_type,$task_description,$booking_date );
        });

        $this->route('GET', '/user/client/getbooking', function ($userId) {
            $ClientBookingController = new ClientController(new Client($this->db),new Resume($this->db),new User($this->db));
            $ClientBookingController->GetBookingClient($userId);
        });
        $this ->route('GET','/user/client/viewbooking/{resumeId}' , function ($userID,$resumeId) {
            $ViewBookingController = new ClientController(new Client($this->db),new Resume($this->db),new User($this->db));
            $ViewBookingController->viewClientBooking($resumeId);
        });

        $this->route('PUT', '/user/tradesman/bookings/status/{booking_id}', function ($userId,$booking_id) {
            $this->responseBodyChecker();

            $book_status = $this->requestBody['book_status'] ?? null;

            $TradesmanBookingStatus = new TradesmanController(new Tradesman($this->db));
            $TradesmanBookingStatus ->UpdateBookingFromClient($userId,$booking_id,$book_status);
        });

        $this->route('GET', '/user/tradesman/getbooking', function ($userId) {

            $TradesmanBookingController = new TradesmanController(new Tradesman($this->db));
            $TradesmanBookingController->GetBookingFromClient($userId);
        });

        $this->route('PUT', '/user/client/work/status/{booking_id}', function ($userId,$booking_id) {
            $this->responseBodyChecker();

            $booking_status = $this->requestBody['work_status'];

            $ClientWorkController = new ClientController(new Client($this->db),new Resume($this->db),new User($this->db));
            $ClientWorkController->UpdateWorkFromTradesman($userId, $booking_id,$booking_status);
        });

        $this->route('POST', '/user/client/create-job', function ($userId) {
            $this->responseBodyChecker();

            $applicant_limit_count = $this->requestBody['applicant_limit_count'] ?? null;
            $salary = $this->requestBody['salary'] ?? null;
            $job_type = $this->requestBody['job_type'] ?? null;
            $job_description = $this->requestBody['job_description'] ?? null;
            $location = $this->requestBody['location'] ?? null;
            $status = $this->requestBody['status'] ?? null;
            $deadline = $this->requestBody['deadline'] ?? null;

            $jobController = new JobController(new Job($this->db));
            $jobController->addJob($userId, $salary, $applicant_limit_count, $job_type, $job_description, $location, $status, $deadline);
        });

        $this->route('GET', '/user/jobs', function () {
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;

            $jobController = new JobController(new Job($this->db));
            $jobController->getAllJobs($page, $limit);
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

        $this->route('POST', '/user/client/job/apply/{jobId}', function ($userId, $jobId){
            ['qualification_summary' => $qualificationSummary] = $this->requestBody;
            $jobApplicationController = new JobApplicationController(new Job_Application($this->db));
            $jobApplicationController->apply_job($userId, $jobId, $qualificationSummary);
        });

        $this->route('GET', '/user/getresumes', function () {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

            $ResumeController = new ResumeController(new Resume($this->db),new Client($this->db),new User($this->db),new Report($this->db));
            $ResumeController->GetAllResumes($page, $limit);
        });

        $this->route('GET', '/user/getresume/{resumeId}', function ($userId,$resumeId) {
            $ResumeController = new ResumeController(new Resume($this->db),new Client($this->db),new User($this->db),new Report($this->db));
            $ResumeController->ViewResume($resumeId);
        });

        $this->route('POST', '/user/message/send', function ($userId){
            $this->responseBodyChecker();
            ['receiver_id' => $receiverId, 'message' => $message] = $this->requestBody;

            $messageController = new ChatController(new Chat($this->db));
            $messageController->messageUser($userId, $receiverId, $message);
        });

        $this->route('GET', '/user/chat/get', function ($userId){
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;
            $messageController = new ChatController(new Chat($this->db));
            $messageController->getChats($userId, $page, $limit);
        });

        $this->route('GET', '/client/jobs/view/my_jobs', function ($userId){
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;

            $jobController = new JobController(new Job($this->db));
            $jobController->viewUserJobs($userId, $page, $limit);
        });

        $this->route('GET', '/user/message/{chatId}', function ($user_id, $chat_id) {
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;
            $messageController = new ChatController(new Chat($this->db));
            $messageController->getMessages($user_id, $chat_id, $page, $limit);
        });


        $this->route('DELETE', '/client/jobs/delete/{jobId}', function ($jobId, $userId){
            $jobController = new JobController(new Job($this->db));
            $jobController->deleteJob($jobId, $userId);
        });

        $this->route('DELETE', '/user/message/delete{messageId}', function ($message_id, $userId){
            $messageController = new ChatController(new Chat($this->db));
            $messageController->deleteMessage($message_id, $userId);
        });

        $this->route('GET', '/user/jobs/recent', function () {
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;

            $jobController = new JobController(new Job($this->db));
            $jobController->getAllRecentJobs($page, $limit);
        });

        $this->route('POST', '/client/update/profile_picture', function ($userId) {
            if (empty($_FILES['profile_picture'])) {
                echo json_encode(["message" => "No file uploaded"]);
                return;
            }
            $profile_pic = $_FILES['profile_picture'];

            $clientProfileController = new ClientProfileController(new Client_Profile($this->db));
            $clientProfileController->updateProfilePicture($userId, $profile_pic);
        });

        $this->route('POST', '/client/update/profile_address', function ($userId){
            $this->responseBodyChecker();
            ['address' => $profile_address] = $this->requestBody;
            $clientProfileController = new ClientProfileController(new Client_Profile($this->db));
            $clientProfileController->updateProfileAddress($userId, $profile_address);
        });

        $this->route('GET', '/client/profile', function ($user_id) {
            $clientProfileController = new ClientProfileController(new Client_Profile($this->db));
            $clientProfileController->getProfile($user_id);
        });

        $this->route('POST', '/client/jobs/update/{jobId}', function ($userId,$jobId){
            $this->responseBodyChecker();
            ['salary' => $salary, 'job_description' => $job_description, 'address' => $address, 'deadline' => $deadline] = $this->requestBody;
            $jobController = new JobController(new Job($this->db));
            $jobController->updateJob($jobId, $userId, $salary, $job_description, $address, $deadline);
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