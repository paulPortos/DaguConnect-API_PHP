CREATE TABLE reports (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
reported_by_id INT UNSIGNED NOT NULL,
reported_id INT UNSIGNED NOT NULL,
report_reason VARCHAR(255) NOT NULL,
report_details VARCHAR(255) NOT NULL,
reporters_email VARCHAR(255) NOT NULL,
reporters_profile VARCHAR(255) NOT NULL,
reported_by VARCHAR(255) NOT NULL,
reported VARCHAR(255) NOT NULL,
reporter VARCHAR(255) NOT NULL,
report_attachment VARCHAR(255) NOT NULL,
report_status ENUM ('Pending', 'Dismissed', 'Suspend') NOT NULL,
reported_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (reported_by_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (reported_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;
