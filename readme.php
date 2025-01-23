<?php

 /*
 * To run the server
 * Type this in the terminal
 * php -S localhost:8000 -t public
 */

 /*
  * RULES
  *
  * 1) Follow the format Model -> Controller -> Api
  * 2) Important constant data should be stored in the .env
  * 3)
  */


/*
 * create the users_token table
 *
 * CREATE TABLE user_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

*create the users table
 *
 * CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    age INT(11) NOT NULL,
    suspend TINYINT(1) NOT NULL,
    profile_pic BIGINT(20) NULL DEFAULT NULL,
    email_verified_at DATE NULL,
    email VARCHAR(255) NOT NULL,
    is_client TINYINT(1) NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATE NOT NULL
);


*create the user_resume
 *
 * CREATE TABLE resume (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


 */