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
            "count" => $totalUserCount,
            "active" => $totalActiveUsers,
            "Pending" => $totalBookingPending,
            "Active" => $totalBookingActive,
            "Cancelled" => $totalBookingCancelled,
            "Completed" => $totalBookingCompleted,
            "TotalBooking" => $totalBooking
        ]]);
    }

    public function bookingStatistics() {

    }
}