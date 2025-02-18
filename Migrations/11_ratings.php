CREATE TABLE ratings(
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
client_id INT UNSIGNED NOT NULL,
booking_id INT UNSIGNED NOT NULL,
profile VARCHAR(255) NULL,
ratings INT NOT NULL,
message VARCHAR(500) NOT NULL,
client_name VARCHAR(255) NOT NULL,
rated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (client_id) REFERENCES client_booking(user_id) ON DELETE CASCADE,
FOREIGN KEY (booking_id) REFERENCES client_booking(id) ON DELETE CASCADE
)ENGINE = InnoDB;