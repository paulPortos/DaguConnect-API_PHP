CREATE TABLE tradesman_resume (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(255) NOT NULL,
user_id INT UNSIGNED NOT NULL,
specialties JSON NOT NULL,
profile_pic VARCHAR(255) NULL,
prefered_work_location JSON NOT NULL,
academic_background JSON NULL,
tradesman_full_name VARCHAR(255) NOT NULL,
updated_at  DATETIME NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;