CREATE TABLE client_booking (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT UNSIGNED NOT NULL,
resume_id INT UNSIGNED NOT NULL,
tradesman_id INT UNSIGNED NOT NULL,
phone_number VARCHAR(255) NOT NULL,
address VARCHAR(255) NOT NULL,
task_type ENUM('Carpentry','Painting','Welding','Electrical_work','Plumbing','Masonry','Roofing','Ac repair','Mechanics','Drywalling','glazing') NOT NULL,
task_description TEXT NOT NULL,
booking_status ENUM('Pending', 'Accepted', 'Rejected') NOT NULL,
work_status ENUM('Active', 'Finished', 'Failed')  NULL,
created_at DATETIME NOT NULL,
INDEX (`user_id`),
INDEX (`tradesman_id`),
INDEX (`resume_id`),
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
FOREIGN KEY (resume_id) REFERENCES tradesman_resume(id) ON DELETE CASCADE,
FOREIGN KEY (tradesman_id) REFERENCES tradesman_resume(user_id) ON DELETE CASCADE
);