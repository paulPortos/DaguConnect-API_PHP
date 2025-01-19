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

    public function userStatistics() {
        $totalUserCount = $this->admin_model->getAllUserCount();
        if ($totalUserCount <= 0 ) {
            $this->jsonResponse(["Message" => "No users detected"], 200);
        }
        $this->jsonResponse(["users" => [
            "count" => $totalUserCount
        ]]);
    }
}