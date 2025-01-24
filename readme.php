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
    email_verified_at DATE NULL,
    email VARCHAR(255) NOT NULL,
    is_client TINYINT(1) NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATE NOT NULL
    );

 *create the user_resume

    CREATE TABLE user_resume (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    *create admin table

    CREATE TABLE `daguconnect-db`.`admin` (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    token VARCHAR(255),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY idx_email (email) -- Creates a unique index for faster searches on email
    );

*create table reports

    CREATE TABLE `daguconnect-db`.`reports` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `reason` ENUM('inactive','scam','spam','harassment','unprofessional_behavior','violating_terms','impersonation') NOT NULL,
    `reported_user` INT NOT NULL,
    `is_solved` BOOLEAN NOT NULL DEFAULT FALSE,
    `statement` TEXT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX (`user_id`),
    INDEX (`reported_user`),
    INDEX (`reason`),
    INDEX (`created_at`),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_user) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE = InnoDB;


*create chat table

    CREATE TABLE `daguconnect-db`.`chat` (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    profile_picture BLOB NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES `users`(`id`),
    FOREIGN KEY (receiver_id) REFERENCES `users`(`id`)
    ) ENGINE = InnoDB;
 */