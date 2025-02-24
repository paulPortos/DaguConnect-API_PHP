CREATE TABLE job_applications (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT UNSIGNED NOT NULL,
resume_id INT UNSIGNED NOT NULL,
job_id INT UNSIGNED NOT NULL,
tradesman_profile_picture VARCHAR(255) NOT NULL,
job_type ENUM('Carpentry','Painting','Welding','Electrical_work','Plumbing','Masonry','Roofing','Ac_repair','Mechanics','Drywalling','Cleaning'),
qualification_summary TEXT NOT NULL,
status ENUM('Pending','Active','Declined','Completed','Failed','Cancelled'),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
INDEX (`user_id`),
INDEX (`resume_id`),
INDEX (`job_id`),
FOREIGN KEY (user_id) REFERENCES users(id),
FOREIGN KEY (resume_id) REFERENCES tradesman_resume(id),
FOREIGN KEY (job_id) REFERENCES jobs(id)
) ENGINE = InnoDB;

