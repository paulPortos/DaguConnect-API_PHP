CREATE TABLE client_booking (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT UNSIGNED NOT NULL,
resume_id INT UNSIGNED NOT NULL,
tradesman_id INT UNSIGNED NOT NULL,
phone_number VARCHAR(255) NOT NULL,
tradesman_fullname VARCHAR(255) NOT NULL,
tradesman_profile VARCHAR(255) NOT NULL,
work_fee INT NOT NULL,
client_fullname VARCHAR(255) NOT NULL,
address VARCHAR(255) NOT NULL,
task_type ENUM('Carpentry','Painting','Welding','Electrician','Plumbing','Masonry','Roofing','Mechanics','Cleaning','ACRepair') NOT NULL,
task_description TEXT NULL,
booking_date DATETIME NULL,
booking_status ENUM('Pending', 'Active', 'Declined','Completed','Failed','Cancelled') NOT NULL,
cancel_reason VARCHAR(255) NULL,
created_at DATETIME NOT NULL,
INDEX (`user_id`),
INDEX (`tradesman_id`),
INDEX (`resume_id`),
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (resume_id) REFERENCES tradesman_resume(id) ON DELETE CASCADE,
FOREIGN KEY (tradesman_id) REFERENCES tradesman_resume(user_id) ON DELETE CASCADE
)ENGINE = InnoDB;


