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
        echo "Seeding chats table and messages table complete" . PHP_EOL;
    }

    private function seedChat1(): void {
        // Create chat between user 1 and user 2
        $stmt = $this->db->prepare("INSERT INTO chats (user_id, receiver_id, receiver_name, latest_message) VALUES (?, ?, ?, ?)");
        $stmt->execute([1, 2, 'Ahron Paul Villacote', 'Good, lets meet up']);

        // Get the last inserted chat ID
        $chatId = $this->db->lastInsertId();

        // Insert messages for this chat
        $this->insertMessage($chatId, 1, 2, 'Hello User 2, how are you?');
        $this->insertMessage($chatId, 2, 1, 'I am good, thanks! How about you?');
        $this->insertMessage($chatId, 1, 2, 'I am also good, are you available to do the work this sunday?');
        $this->insertMessage($chatId, 2, 1, 'Yes im perfectly available, we could met up.');
        $this->insertMessage($chatId, 1, 2, 'Good, lets meet up.');
    }

    private function seedChat2(): void {
        // Create chat between user 2 and user 3
        $stmt = $this->db->prepare("INSERT INTO chats (user_id, receiver_id, receiver_name, latest_message) VALUES (?, ?, ?, ?)");
        $stmt->execute([2, 3, 'Alice Johnson', 'Wait me up, I  am on the way.']);

        // Get the last inserted chat ID
        $chatId = $this->db->lastInsertId();

        // Insert messages for this chat
        $this->insertMessage($chatId, 2, 3, 'Hey User 3, are you available?');
        $this->insertMessage($chatId, 3, 2, 'Yes, I am here!');
        $this->insertMessage($chatId, 2, 3, 'Wait me up, I  am on the way.');
    }

    private function insertMessage(int $chatId, int $userId, int $receiverId, string $message): void {
        $stmt = $this->db->prepare("INSERT INTO messages (user_id, receiver_id, chat_id, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $receiverId, $chatId, $message]);
    }
}