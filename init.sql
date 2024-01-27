CREATE DATABASE IF NOT EXISTS `db`;

USE `db`;

GRANT ALL PRIVILEGES ON db.* TO 'root'@'%';
FLUSH PRIVILEGES;

CREATE TABLE IF NOT EXISTS `subscribers` (
    `id` INT AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255),
    `status` ENUM('active', 'inactive') NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE (`email`),
    INDEX (`status`)
);
