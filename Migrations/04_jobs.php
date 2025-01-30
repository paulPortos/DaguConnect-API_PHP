CREATE TABLE jobs (
id INT UNSIGNED AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
client_fullname VARCHAR(255) NOT NULL,
client_profile_picture VARCHAR(255) NULL,
salary DECIMAL(10,2) NOT NULL,
job_type ENUM(
'carpentry',
'painting',
'welding',
'electrical_work',
'plumbing',
'masonry',
'roofing',
'ac_repair',
'mechanics',
'drywalling',
'cleaning'
) NOT NULL,
job_description TEXT NOT NULL,
status ENUM('available','ongoing','done') NOT NULL,
deadline DATE NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE = InnoDB;