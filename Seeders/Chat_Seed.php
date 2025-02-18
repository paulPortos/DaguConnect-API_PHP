<?php

namespace DaguConnect\Seeders;

use PDO;

trait Chat_Seed {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function seed_chat_message(): void {
        $this->seedChat1();
        $this->seedChat2();
    }

    private function seedChat1(): void {
        // Create chat between user 1 and user 2
        $stmt = $this->db->prepare("INSERT INTO chats 
        (user1_id, user2_id, last_sender_id, last_read_by_user_id, latest_message, is_read) 
        VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([1, 2, 1, null, 'Good, lets meet up', 0]);

        // Get the last inserted chat ID
        $chatId = $this->db->lastInsertId();

        // Insert messages for this chat
        $this->insertMessage($chatId, 1, 2, 'Hello User 2, how are you?');
        $this->insertMessage($chatId, 2, 1, 'I am good, thanks! How about you?');
        $this->insertMessage($chatId, 1, 2, 'I am also good, are you available to do the work this Sunday?');
        $this->insertMessage($chatId, 2, 1, 'Yes, I’m perfectly available, we could meet up.');
        $this->insertMessage($chatId, 1, 2, 'Good, let’s meet up.');
    }

    private function seedChat2(): void {
        // Create chat between user 2 and user 3
        $stmt = $this->db->prepare("INSERT INTO chats 
        (user1_id, user2_id, last_sender_id, last_read_by_user_id, latest_message, is_read) 
        VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([2, 3, 2, null, 'Wait for me, I am on the way.', 0]);

        // Get the last inserted chat ID
        $chatId = $this->db->lastInsertId();

        // Insert messages for this chat
        $this->insertMessage($chatId, 2, 3, 'Hey User 3, are you available?');
        $this->insertMessage($chatId, 3, 2, 'Yes, I am here!');
        $this->insertMessage($chatId, 2, 3, 'Wait for me, I am on the way.');
    }

    private function insertMessage(int $chatId, int $userId, int $receiverId, string $message): void {
        $stmt = $this->db->prepare("INSERT INTO messages (user_id, receiver_id, chat_id, message, is_read) VALUES (?, ?, ?, ?, ?)");

        // Mark messages as unread initially
        $stmt->execute([$userId, $receiverId, $chatId, $message, 0]);
    }

}