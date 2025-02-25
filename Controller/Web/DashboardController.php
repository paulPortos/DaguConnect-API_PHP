<?php

namespace Controller\Web;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Admin;
use DaguConnect\Services\Env;

class DashboardController extends BaseController
{
    private Admin $admin_model;

    public function __construct(Admin $admin_model){
        $this->admin_model = $admin_model;
        new Env();
    }

    public function userStatistics(): void
    {
        $totalUserCount = $this->admin_model->getAllUserCount();
        $totalActiveUsers = $this->admin_model->getAllActiveUsers();
        $totalBookingPending = $this->admin_model->getPendingBookings();
        $totalBookingActive = $this->admin_model->getActiveBookings();
        $totalBookingCancelled = $this->admin_model->getCancelledBookings();
        $totalBookingCompleted = $this->admin_model->getCompletedBookings();
        $totalBooking = $this->admin_model->getAllBookings();
        $totalJobsAvailable = $this->admin_model->getAvailableJobs();
        $totalJobsOngoing = $this->admin_model->getOngoingJobs();
        $totalJobsCompleted = $this->admin_model->getCompletedJobs();
        $totalJobsCancelled = $this->admin_model->getCancelledJobs();
        $totalJobs = $this->admin_model->getAllJobs();
        $userCountsByDate = $this->admin_model->getUsersCountByDate(); // NEW FUNCTION
        $dataReport =$this->generateReport($totalActiveUsers, $totalUserCount, $totalJobsAvailable, $totalJobsCompleted);
        if ($totalUserCount <= 0 ) {
            $this->jsonResponse(["Message" => "No users detected"], 200);
        }

        $this->jsonResponse([
            "users" => [
                "total_user" => $totalUserCount,
                "active_user" => $totalActiveUsers,
                "user_counts_by_date" => $userCountsByDate // RETURNING USER CREATION COUNTS
                ],
            "bookings" => [
                "pending" => $totalBookingPending,
                "active" => $totalBookingActive,
                "cancelled" => $totalBookingCancelled,
                "completed" => $totalBookingCompleted,
                "total_Booking" => $totalBooking,
                ],
            "jobs" => [
                "available" => $totalJobsAvailable,
                "ongoing" => $totalJobsOngoing,
                "cancelled" => $totalJobsCancelled,
                "completed" => $totalJobsCompleted,
                "totalJobs" => $totalJobs,
                ],
            "data_report" => $dataReport
            ]);
    }

    function generateReport($activeUsers, $totalUsers, $totalJobsAvailable, $totalJobsCompleted) {
        $apiKey = $_ENV['AI_API_KEY'];
        $url = "https://api.together.ai/v1/completions";

        $data = [
            "model" => "meta-llama/Llama-3.3-70B-Instruct-Turbo-Free",
            "prompt" => "Generate a concise business report with insights based on the following statistics:  
                 - Active Users: $activeUsers  
                 - Total Users: $totalUsers  
                 - Total Jobs Available: $totalJobsAvailable  
                 - Total Jobs Completed: $totalJobsCompleted  

                 Format the response as follows:  
                 **Business Report:**  
                 **1. Summary** (Provide a short business overview)  
                 **2. Key Insights** (Explain what these numbers indicate)  
                 **3. Recommendations** (Suggest ways to improve engagement and job completion)  

                 Keep the response **concise, professional, and structured**.",
            "max_tokens" => 250
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        $decodedResponse = json_decode($response, true);
        return $decodedResponse["choices"][0]["text"] ?? "Error generating report.";
    }

    public function bookingStatistics(): void
    {

        $totalBookings = $this->admin_model->getAllBookings();
        $totalActiveBookings = $this->admin_model->getActiveBookings();
        $totalCompletedBookings = $this->admin_model->getCompletedBookings();
        $totalCancelledBookings = $this->admin_model->getCancelledBookings();
        $bookings = $this->admin_model->getBookingList();


        $filteredBookings = array_map(function($booking){

            return [
                'id' => $booking['id'],
                'description' => $booking['task_description'],
                'category' => $booking['task_type'],
               'status' => $booking['booking_status'],
            ];
        }, $bookings);

        $this -> jsonResponse(
            [
                "total_bookings" => $totalBookings,
                "active_bookings" => $totalActiveBookings,
                "completed_bookings" => $totalCompletedBookings,
                "cancelled_bookings" => $totalCancelledBookings,
                "bookings" => $filteredBookings
            ]
        );
    }

    public function jobsStatistics(): void
    {
        $totalJobsAvailable = $this->admin_model->getAvailableJobs();
        $totalJobsOngoing = $this->admin_model->getOngoingJobs();
        $totalJobsCompleted = $this->admin_model->getCompletedJobs();
        $totalJobsCancelled = $this->admin_model->getCancelledJobs();
        $totalJobs = $this->admin_model->getAllJobs();
        $jobs = $this->admin_model->getJobsList();

        $filteredJobs = array_map(function($jobs){

            return [
                'fullname' => $jobs['client_fullname'],
                'job_type' => $jobs['job_type'],
                'salary' => $jobs['salary'],
                'applicant_limit_count' => $jobs['applicant_limit_count'],
                'address' => $jobs['address'],
                'deadline' => $jobs['deadline'],
                'status' => $jobs['status'],
            ];
        }, $jobs);

        $this -> jsonResponse(
            [
                "total_bookings" => $totalJobs,
                "available" => $totalJobsAvailable,
                "ongoing" => $totalJobsOngoing,
                "completed" => $totalJobsCompleted,
                "cancelled" => $totalJobsCancelled,
                "jobs" => $filteredJobs
            ]
        );
    }

    public function userManagement(): void
    {
        $totalUserCount = $this->admin_model->getAllUserCount();
        $totalTradesmanCount = $this->admin_model->getTradesman();
        $totalClientCount = $this->admin_model->getClient();
        $totalSuspendedCount = $this->admin_model->getAllSuspendedUsers();
        $users = $this->admin_model->getUsersList();

        // Filter user data to include only specific keys
        $filteredUsers = array_map(function($user) {
            $role = ($user['is_client'] == 1) ? "Client" : "Tradesman";
            return [
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'verified' => $user['email_verified_at'],
                'birthdate' => $user['birthdate'],
                'is_client' => $role
            ];
        }, $users);

        $this->jsonResponse(
            [
                "total_user" => $totalUserCount,
                "total_tradesman" => $totalTradesmanCount,
                "total_client" => $totalClientCount,
                "total_suspended" =>$totalSuspendedCount,
                "user" => $filteredUsers
            ]
        );
    }


    public function validateResume($user_id,$status_of_approval): void
    {
        $is_approve = 0 ;
        $is_active = 0 ;
        if($status_of_approval == 'Approved'){
            $is_approve = 1;
            $is_active = 1;
        }

        $resumeValidataion = $this->admin_model->validateResume($user_id,$status_of_approval,$is_approve,$is_active);

        if($resumeValidataion){
            $this->jsonResponse(['message' => 'Resume validation updated successfully.'],200);
        }
        else {
            $this->jsonResponse(['message' => 'Resume Is Not Pending'], 400);
        }
    }

    public function viewUserDetail($user_id): void
    {

        $userData = $this->admin_model->viewUserDetail($user_id);
        if($userData){
            $this->jsonResponse($userData,200);
        } else {
            $this->jsonResponse(['message' => 'User Not Found'], 400);
        }
    }


    public function resumeManagement(): void
    {
        $totalResumeCount = $this->admin_model->getAllResumeCount();
        $pendingResumeCount = $this->admin_model->getPendingResume();
        $approvedResumeCount = $this->admin_model->getApprovedResume();
        $declinedResumeCount = $this->admin_model->getDeclined();
        $resumes = $this->admin_model->getResumeList();

        // Filter resume data to include only specific keys
        $filteredResumes = array_map(function($resume) {
            return [
                'resume_id' => $resume['user_id'],
                'name' => $resume['tradesman_full_name'],
                'email' => $resume['email'],
                'status' => $resume['status_of_approval']
            ];
        }, $resumes);

        $this->jsonResponse([
            "total_resume" => $totalResumeCount,
            "pending_resume" => $pendingResumeCount,
            "approved_resume" => $approvedResumeCount,
            "declined_resume" => $declinedResumeCount,
            "resume" => $filteredResumes
        ],200);
    }

    public function reportManagement(): void
    {

        $totalReports = $this->admin_model->getAllReportCount();
        $pendingReports = $this->admin_model->getPendingReport();
        $resolvedReports = $this->admin_model->getSuspendedReport();
        $dissmissedReports = $this->admin_model->getDissmissReport();
        $reportList = $this->admin_model->getReportList();
        $filteredResumes = array_map(function($reports) {
            return [
                'id' => $reports['id'],
                'reported_by' => $reports['reported_by'],
                'reported' => $reports['reported'],
                'report_type' => $reports['report_reason'],
                'reporter' => $reports['reporter'],
                'report_status' => $reports['report_status']
            ];
        }, $reportList);
        $this->jsonResponse([
            "total_reports" => $totalReports,
            "pending_reports" => $pendingReports,
            "suspended_reports" => $resolvedReports,
            "dismissed_reports" => $dissmissedReports,
            "report_list" => $filteredResumes
        ]);
    }

    public function viewReportDetail($id): void
    {
        $reportData = $this->admin_model->viewReportDetail($id);
        if($reportData){
            $this->jsonResponse($reportData,200);
        } else {
            $this->jsonResponse(['message' => 'User Not Found'], 400);
        }
    }
    
    public function suspendedReported ($reported_id,$report_status): void
    {

        $suspend = 0;
        if($report_status == 'Suspend'){
            $suspend = 1;
        }
        $updateReportedStatus = $this->admin_model->updateSuspendStatus($reported_id,$suspend);
        $updateReportStatus = $this->admin_model->updateReportStatus($reported_id,$report_status);

        if($updateReportStatus || $updateReportedStatus){
            $this->jsonResponse(['message' => 'Report status updated successfully.'],200);
        }
        else {
            $this->jsonResponse(['message' => 'Report status not updated'], 400);
        }


    }


}