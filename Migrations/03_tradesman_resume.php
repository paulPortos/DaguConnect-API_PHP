CREATE TABLE tradesman_resume (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(255) NOT NULL,
user_id INT UNSIGNED NOT NULL,
specialties JSON NULL,
profile_pic VARCHAR(255) NULL,
about_me TEXT NULL,
prefered_work_location JSON  NULL,
work_fee INT NULL,
tradesman_full_name VARCHAR(255) NULL,
updated_at  DATETIME NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
is_active TINYINT(1) NOT NULL,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;