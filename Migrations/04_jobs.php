CREATE TABLE jobs (
id INT UNSIGNED AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
client_fullname VARCHAR(255) NOT NULL,
client_profile_picture VARCHAR(255) NOT NULL,
salary DECIMAL(10,2) NOT NULL,
job_type ENUM(
'Carpenter',
'Painter',
'Welder',
'Electrician',
'Plumber',
'Mason',
'Roofer',
'Ac_technician',
'Mechanic',
'Cleaner'
) NOT NULL,
job_description TEXT NOT NULL,
address TEXT NOT NULL,
latitude DECIMAL(9,6) NULL,
longitude DECIMAL(9,6) NULL,
status ENUM('Pending','Active','Declined','Completed','Failed','Cancelled') NOT NULL,
deadline DATE NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
INDEX idx_job_type (job_type),
INDEX idx_location_coords (latitude, longitude)
) ENGINE = InnoDB;