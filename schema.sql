CREATE DATABASE `doingsdone`
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;
USE `doingsdone`;

create table `user` (
	`user_id` INT AUTO_INCREMENT PRIMARY KEY,
	`registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	`name` VARCHAR(50),
	`email` VARCHAR(128) UNIQUE NOT NULL,
	`password` VARCHAR(64)
);

CREATE TABLE `projects` (
	`projects_id` INT AUTO_INCREMENT PRIMARY KEY,
	`user_id` INT NOT NULL,
	`projects_name` VARCHAR(128) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
);

CREATE TABLE `tasks` (
	`task_id` INT AUTO_INCREMENT PRIMARY KEY,
  	`creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  	`execution_date` TIMESTAMP DEFAULT NULL,
  	`status` INT DEFAULT 0,
  	`name` VARCHAR(128) NOT NULL,
  	`file` VARCHAR(128) DEFAULT NULL,
  	`deadline` TIMESTAMP DEFAULT NULL,
  	`user_id` INT NOT NULL,
  	`projects_id` INT NOT NULL,
);