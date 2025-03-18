<?php

namespace Controller;

use AllowDynamicProperties;
use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Notification;

#[AllowDynamicProperties] class NotificationController extends BaseController
{
    private Notification $notificationModel;

    /**
     * Constructor for the NotificationController class.
     *
     * @param Notification $notification_Model The notification model instance.
     */
    public function __construct(Notification $notification_Model)
    {
        $this->db = new config();
        $this->notificationModel = $notification_Model;
    }

    /**
     * Retrieves notifications for a specific user.
     *
     * @param int $userId The ID of the user whose notifications are to be retrieved.
     * @param int $page The page number for pagination.
     * @param int $limit The number of notifications per page.
     * @return void
     */
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

    /**
     * Clears notifications for a specific user.
     *
     * @param int $userId The ID of the user whose notifications are to be cleared.
     * @return void
     */
    public function clearNotification($userId): void {
        $clear = $this->notificationModel->clearNotification($userId);
        if ($clear) {
            $this->jsonResponse(['message' => 'Notification cleared'], 200);
        } else {
            $this->jsonResponse(['message' => 'Failed to clear notification'], 500);
        }
    }
}