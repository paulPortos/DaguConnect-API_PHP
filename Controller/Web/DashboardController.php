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

        if ($totalUserCount <= 0 ) {
            $this->jsonResponse(["Message" => "No users detected"], 200);
        }
        $this->jsonResponse(["users" => [
            "totaluser" => $totalUserCount,
            "activeuser" => $totalActiveUsers,
            "Pending" => $totalBookingPending,
            "Active" => $totalBookingActive,
            "Cancelled" => $totalBookingCancelled,
            "Completed" => $totalBookingCompleted,
            "TotalBooking" => $totalBooking
        ]]);
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
}