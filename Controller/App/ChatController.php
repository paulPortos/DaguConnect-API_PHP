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

        if (empty($message) || trim($message) === "") {
            $this->jsonResponse(['message' => "Cannot send empty message"], 400);
            return;
        }

        if ($this->hasFoulWords($message)) {
            $this->jsonResponse(['message' => "Your message cannot contain foul words."], 400);
            return;
        }

        $chat_id = $this->model->ensureChatExists($user_id, $receiver_id, $message);

        $this->model->changeLatestMessage($chat_id, $message, $user_id);

        $chat = $this->model->sendMessage($user_id, $receiver_id, $message, $chat_id);
        if ($chat) {
            $this->jsonResponse(['message' => "Message sent successfully."], 201);
        } else {
            $this->jsonResponse(['message' => "Failed to send message."], 500);
        }
    }

    public function getChats(int $user_id): void {
        $chats = $this->model->getChats($user_id);
        $chat_return = ['chats' => []];

        foreach ($chats as $chat) {
            $full_name = null;
            $profile_picture = null;

            //If the sender is user1_id then it will return the full name and profile of the receiver
            if ($chat['user1_id'] == $user_id) {
                $full_name = $this->model->getFullName($chat['user2_id'])['fullname'] ?? null;
                $profile_picture = $this->model->getProfilePicture($chat['user2_id']);
            } else if ($chat['user2_id']) {
                $full_name = $this->model->getFullName($chat['user1_id'])['fullname'] ?? null;
                $profile_picture = $this->model->getProfilePicture($chat['user1_id']);
            }

            $is_read = $chat['is_read'] == 1;

            $chat_return['chats'][] = [
                'id' => $chat['id'],
                'user1_id' => $chat['user1_id'],
                'user2_id' => $chat['user2_id'],
                'full_name' => $full_name,  // Now correctly a string
                'latest_message' => $chat['latest_message'],
                'profile_picture' => $profile_picture,
                'last_sender_id' => $chat['last_sender_id'],
                'is_read' => $is_read,
                'created_at' => $chat['created_at'],
                'updated_at' => $chat['updated_at'],
            ];
        }

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
        $this->model->markAsReadChat($chat_id, $user_id);
        $this->model->markAsReadMessage($chat_id, $user_id);

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