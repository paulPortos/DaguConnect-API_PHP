<?php

namespace Controller\Web;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Admin;

class DashboardController extends BaseController
{
    private Admin $admin_model;

    public function __construct(Admin $admin_model){
        $this->admin_model = $admin_model;
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
        $userCountsByDate = $this->admin_model->getUsersCountByDate(); // NEW FUNCTION

        if ($totalUserCount <= 0 ) {
            $this->jsonResponse(["Message" => "No users detected"], 200);
        }
        $this->jsonResponse([
            "users" => [
                "totaluser" => $totalUserCount,
                "activeuser" => $totalActiveUsers,
                "Pending" => $totalBookingPending,
                "Active" => $totalBookingActive,
                "Cancelled" => $totalBookingCancelled,
                "Completed" => $totalBookingCompleted,
                "TotalBooking" => $totalBooking,
                "UserCountsByDate" => $userCountsByDate // RETURNING USER CREATION COUNTS
                ]
            ]);
    }

    public function bookingStatistics() {

        $totalBookings = $this->admin_model->getAllBookings();
        $totalActiveBookings = $this->admin_model->getActiveBookings();
        $totalCompletedBookings = $this->admin_model->getCompletedBookings();
        $totalCancelledBookings = $this->admin_model->getCancelledBookings();
        $bookings = $this->admin_model->getBookingList();


        $filteredBookings = array_map(function($booking){

            return [
                'id' => $booking['id'],
                'title' => $booking['task_description'],
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

    public function userManagement(){
        $totalUserCount = $this->admin_model->getAllUserCount();
        $totalTradesmanCount = $this->admin_model->getTradesman();
        $totalClientCount = $this->admin_model->getClient();
        $totalSuspendedCount = $this->admin_model->getAllSuspendedUsers();
        $users = $this->admin_model->getUsersList();

        // Filter user data to include only specific keys
        $filteredUsers = array_map(function($user) {
            $role = ($user['is_client'] == 1) ? "Client" : "Tradesman";
            return [
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
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


    public function validateResume($user_id,$status_of_approval){

        $is_approve = null ;
        if($status_of_approval == 'Approved'){
            $is_approve = 1;
        }
        else if($status_of_approval == 'Declined'){
            $is_approve = 0;
        }

        $resumeValidataion = $this->admin_model->validateResume($user_id,$status_of_approval,$is_approve);

        if($resumeValidataion){
            $this->jsonResponse(['message' => 'Resume validation updated successfully.'],200);
        }
        else {
            $this->jsonResponse(['message' => 'Resume Is Not Pending'], 400);
        }
    }

    public function viewUserDetail($user_id){

        $userData = $this->admin_model->viewviewUserDetail($user_id);
        if($userData){
            $this->jsonResponse($userData,200);
        } else {
            $this->jsonResponse(['message' => 'User Not Found'], 400);
        }
    }
}