<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Chat;
use DaguConnect\Services\IfDataExists;

class ChatController extends BaseController
{
    private Chat $model;
    use IfDataExists;

    public function __construct(Chat $chat_model) {
        $this->db = new config();
        $this->model = $chat_model;
    }

    public function messageUser(int $user_id, int $receiver_id, String $message): void {
        if (empty($user_id) || empty($receiver_id)) {
            $this->jsonResponse(['message' => "Invalid user ID, receiver ID or chat ID"]);
            return;
        }

        if (empty($message)) {
            $this->jsonResponse(['message' => "Cannot send empty message"], 400);
            return;
        }

        if ($this->hasFoulWords($message)) {
            $this->jsonResponse(['message' => "Your message cannot contain foul words."], 400);
            return;
        }

        $chat_id = $this->model->ensureChatExists($user_id, $receiver_id, $message);

        $this->model->changeLatestMessage($chat_id, $message);

        $chat = $this->model->sendMessage($user_id, $receiver_id, $message, $chat_id);
        if ($chat) {
            $this->jsonResponse(['message' => "Message sent successfully."], 201);
        } else {
            $this->jsonResponse(['message' => "Failed to send message."], 500);
        }
    }

    public function getChats(int $user_id): void {
        $chat = $this->model->getChats($user_id);
        $full_name = null;
        $profile_picture = null;
        if ($chat['user_id1'] == $user_id) {
            $full_name = $this->model->getFullName($chat['user_id2']);
            $profile_picture = $this->model->getProfilePicture($chat['user_id2']);
        } else if ($chat['user_id2']) {
            $full_name = $this->model->getFullName($chat['user_id1']);
            $profile_picture = $this->model->getProfilePicture($chat['user_id1']);
        }

        $chat_return = [
            'chats' => [
                'id' => $chat['id'],
                'user_id1' => $chat['user_id1'],
                'user_id2' => $chat['user_id2'],
                'full_name' => $full_name,
                'latest_message' => $chat['latest_message'],
                'profile_picture' => $profile_picture,
                'created_at' => $chat['created_at'],
                'updated_at' => $chat['updated_at'],
        ]];

        $this->jsonResponse($chat_return, 200);

    }

    public function deleteMessage(int $id, $user_id): void {
        $exist = $this->exists($id, 'id', 'chats');

        if (!$exist) {
            $this->jsonResponse(['message' => "Message not found"], 404);
            return;
        } else {
            $this->model->deleteMessage($id, $user_id);
            $this->jsonResponse(['message' => "Message deleted successfully."], 200);
        }
    }

    public function getMessages($user_id, $chat_id): void{
        $messages = $this->model->getMessages($user_id, $chat_id);

        if ($messages) {
            $this->jsonResponse(['messages' => $messages], 200);
        } else {
            $this->jsonResponse(['message' => 'No messages found'], 200);
        }
    }

    public function hasFoulWords($message): bool {
        $foulWords = [
            'gago',
            'fuck',
            'fuck you',
            'asshole',
            'nigga',
            'nigger',
            'dick',
            'pakyu',
            'tang ina mo',
            'tangina mo'
        ];

        $messageLower = strtolower($message);
        foreach ($foulWords as $word) {
            if (str_contains($messageLower, $word)) {
                return true;
            }
        }
        return false;
    }
}