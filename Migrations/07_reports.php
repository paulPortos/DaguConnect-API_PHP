CREATE TABLE reports (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT UNSIGNED NOT NULL,
reason ENUM('inactive','scam','spam','harassment','unprofessional_behavior','violating_terms','impersonation') NOT NULL,
reported_user INT UNSIGNED NOT NULL,
status ENUM('open','dismissed','solved') NOT NULL DEFAULT 'open',
statement TEXT NOT NULL,
created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (reported_user) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;