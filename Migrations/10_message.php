CREATE TABLE messages (
id INT UNSIGNED AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
receiver_id INT UNSIGNED NOT NULL,
chat_id INT UNSIGNED NOT NULL,
message TEXT NOT NULL,
created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (chat_id) REFERENCES chats(id) ON DELETE CASCADE,
INDEX idx_chat_id (chat_id)
) ENGINE = InnoDB;