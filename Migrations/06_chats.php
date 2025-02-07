CREATE TABLE chats (
id INT UNSIGNED AUTO_INCREMENT,
user1_id INT UNSIGNED NOT NULL,
user2_id INT UNSIGNED NOT NULL,
last_sender_id INT UNSIGNED NOT NULL,
last_read_by_user_id INT UNSIGNED NULL,
latest_message TEXT NOT NULL,
is_read TINYINT(1) NOT NULL,
created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
UNIQUE KEY unique_chat_pair (user1_id, user2_id),
FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (last_sender_id) REFERENCES users(id) ON DELETE CASCADE,
INDEX idx_last_sender_id (last_sender_id),
INDEX idx_user1_id (user1_id),
INDEX idx_user2_id (user2_id)
) ENGINE = InnoDB;