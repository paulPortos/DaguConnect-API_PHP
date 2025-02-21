CREATE TABLE reports (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
tradesman_id INT UNSIGNED NOT NULL,
client_id INT UNSIGNED NOT NULL,
report_reason VARCHAR(255) NOT NULL,
report_details VARCHAR(255) NOT NULL,
tradesman_email VARCHAR(255) NOT NULL,
tradesman_profile VARCHAR(255) NOT NULL,
tradesman_fullname VARCHAR(255) NOT NULL,
client_fullname VARCHAR(255) NOT NULL,
report_attachment VARCHAR(255) NOT NULL,
report_status ENUM ('Pending', 'Dismissed', 'Solved') NOT NULL,
reported_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (tradesman_id) REFERENCES tradesman_resume(user_id) ON DELETE CASCADE,
FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;
