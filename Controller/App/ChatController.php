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

    public function messageUser(int $user_id, int $receiver_id, String $message, int $chat_id): void {
        if (empty($user_id) || empty($receiver_id) || empty($chat_id)) {
            $this->jsonResponse(['message' => "Invalid user ID, receiver ID or chat ID"]);
        }

        if (empty($message)) {
            $this->jsonResponse(['message' => "Cannot send empty message"], 400);
            return;
        }

        if ($this->hasFoulWords($message)) {
            $this->jsonResponse(['message' => "Your message cannot contain foul words."], 400);
            return;
        }
        $chat_id_check = $this->model->ensureChatExists($user_id, $receiver_id);
        // Create a user id if it doesn't exist'
        if ($chat_id_check) {
            $chat_id = $chat_id_check;
        }

        if ($this->model->sendMessage($user_id, $receiver_id, $message, $chat_id)) {
            $this->jsonResponse(['message' => "Message sent successfully."], 201);
        } else {
            $this->jsonResponse(['message' => "Failed to send message."], 500);
        }
    }

    public function getChats(int $user_id): void {
        $chat = $this->model->getChats($user_id);
        if($chat){
            $this->jsonResponse(['chats' => $chat], 200);
        } else {
            $this->jsonResponse(['message' => 'No chats found'], 200);
        }
    }



    public function hasFoulWords($message): bool {
        // Define an array of foul words
        $foulWords = ['fuck', 'fuck you', 'asshole', 'nigga', 'nigger', 'dick', 'pakyu', 'tang ina mo', 'tangina mo'];

        // Convert the message to lowercase for case-insensitive comparison
        $messageLower = strtolower($message);

        // Check for foul words in the message
        foreach ($foulWords as $word) {
            if (str_contains($messageLower, $word)) {
                return true; // Foul word found
            }
        }

        return false; // No foul words found
    }
}