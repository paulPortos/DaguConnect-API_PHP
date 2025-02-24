<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Chat;
use DaguConnect\Services\Env;
use DaguConnect\Services\IfDataExists;
use DaguConnect\WebSocket\WebSocketServer;
use Exception;

class ChatController extends BaseController
{
    private Chat $model;
    use IfDataExists;

    public function __construct(Chat $chat_model) {
        $this->db = new config();
        $this->model = $chat_model;
        new Env();
    }

    public function messageUser(int $user_id, int $receiver_id, string $message): void {
        if (empty($user_id) || empty($receiver_id)) {
            $this->jsonResponse(['message' => "Invalid user ID or receiver ID"]);
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

        // Ensure chat exists
        $chat_id = $this->model->ensureChatExists($user_id, $receiver_id, $message);

        // Update latest message
        $this->model->changeLatestMessage($chat_id, $message, $user_id);

        // Send message
        $chat = $this->model->sendMessage($user_id, $receiver_id, $message, $chat_id);

        if ($chat) {
            // Prepare WebSocket message
            $messageData = [
                'user_id' => $user_id,
                'receiver_id' => $receiver_id,
                'chat_id' => $chat_id,
                'message' => $message,
                'timestamp' => date('Y-m-d H:i:s')
            ];

            // Send message via Workerman WebSocket
            WebSocketServer::broadcastMessage($messageData);

            $this->jsonResponse(['message' => "Message sent successfully."], 201);
        } else {
            $this->jsonResponse(['message' => "Failed to send message."], 500);
        }
    }



    public function getChats(int $user_id, int $page = 1, int $limit = 10): void {
        $result = $this->model->getChats($user_id, $page, $limit); // Fetch chats from model
        $chat_return = ['chats' => []];

        // Ensure 'chats' key exists
        if (!isset($result['chats']) || !is_array($result['chats'])) {
            $this->jsonResponse(['error' => 'No chats found'], 404);
            return;
        }

        foreach ($result['chats'] as $chat) {
            if (!isset($chat['user1_id'], $chat['user2_id'], $chat['latest_message'])) {
                continue; // Skip if essential keys are missing
            }

            $full_name = null;
            $profile_picture = null;

            // If the sender is user1_id then return the full name and profile of the receiver
            if ($chat['user1_id'] == $user_id) {
                $full_name = $this->model->getFullName($chat['user2_id'])['fullname'] ?? null;
                $profile_picture = $this->model->getProfilePicture($chat['user2_id']);
            } else {
                $full_name = $this->model->getFullName($chat['user1_id'])['fullname'] ?? null;
                $profile_picture = $this->model->getProfilePicture($chat['user1_id']);
            }

            $is_read = isset($chat['is_read']) ? ($chat['is_read'] == 1) : false; // Handle missing key

            $chat_return['chats'][] = [
                'id' => $chat['id'] ?? null,
                'user1_id' => $chat['user1_id'] ?? null,
                'user2_id' => $chat['user2_id'] ?? null,
                'full_name' => $full_name,
                'latest_message' => $chat['latest_message'] ?? null,
                'profile_picture' => $profile_picture,
                'last_sender_id' => $chat['last_sender_id'] ?? null,
                'is_read' => $is_read,
                'created_at' => $chat['created_at'] ?? null,
                'updated_at' => $chat['updated_at'] ?? null
            ];
        }

        // Append pagination info
        $chat_return['current_page'] = $result['current_page'] ?? 1;
        $chat_return['total_pages'] = $result['total_pages'] ?? 1;

        $this->jsonResponse($chat_return, 200);
    }


    public function deleteMessage(int $id, $user_id): void {
        $exist = $this->exists($id, 'id', 'chats');

        if (!$exist) {
            $this->jsonResponse(['message' => "Message not found"], 404);
        } else {
            $this->model->deleteMessage($id, $user_id);
            $this->jsonResponse(['message' => "Message deleted successfully."], 200);
        }
    }

    public function getMessages($user_id, $chat_id, $page, $limit): void{
        $messagesData = $this->model->getMessages($user_id, $chat_id, $page, $limit);
        $this->model->markAsReadChat($chat_id, $user_id);
        $this->model->markAsReadMessage($chat_id, $user_id);

        if ($messagesData) {
            $this->jsonResponse(
                [
                    'messages' => $messagesData['messages'],
                    'current_page' => $messagesData['current_page'],
                    'total_pages' => $messagesData['total_pages']
                ], 200);
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