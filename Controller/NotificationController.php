<?php

namespace Controller;

use AllowDynamicProperties;
use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Notification;

#[AllowDynamicProperties] class NotificationController extends BaseController
{
    private Notification $notificationModel;
    public function __construct(Notification $notification_Model)
    {
        $this->db = new config();
        $this->notificationModel = $notification_Model;
    }

    public function getNotification($userId, $page, $limit): void
    {
        $notification = $this->notificationModel->getNotification($userId, $page, $limit);
        if (empty($notification)) {
            $this->jsonResponse(['message' => 'No notification found'], 404);
        }

        if ($notification) {
            $this->jsonResponse([
                'notifications' => $notification['notifications'],
                'current_page' => $notification['current_page'],
                'total_pages' => $notification['total_pages']
            ], 200);
        }
    }
}