<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Chat;
use DaguConnect\Services\IfDataExists;

class ChatController extends BaseController
{
    private Chat $model;
    use IfDataExists;
    public function messageUser(int $user_id, int $receiver_id, String $message): void
    {
        if (empty(trim($message))) {
            $this->jsonResponse(['message' => "Cannot send empty message"], 400);
            return;
        }
        $ifExists = $this->exists($user_id,  "id", "users");
        if (!$ifExists) {
            $this->jsonResponse(['message' => "User does not exist"], 400);
            return;
        }
        $this->model->sendMessage($user_id, $receiver_id, $message);
    }

    public function getMessages($user_id, int $receiver_id) {
        
    }
}