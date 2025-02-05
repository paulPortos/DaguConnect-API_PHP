CREATE TABLE chats (
id INT UNSIGNED AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
receiver_id INT UNSIGNED NOT NULL,
receiver_name VARCHAR(255) NOT NULL,
latest_message TEXT NOT NULL,
created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
UNIQUE KEY unique_chat_pair (user_id, receiver_id),
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
INDEX idx_user_id (user_id),
INDEX idx_receiver_id (receiver_id)
) ENGINE = InnoDB;