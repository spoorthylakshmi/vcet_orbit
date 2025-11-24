-- Init script for VCET ORBIT project
-- Creates database `vcet_orbit` and `lost_found` table used by the app

CREATE DATABASE IF NOT EXISTS `vcet_orbit` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vcet_orbit`;

CREATE TABLE IF NOT EXISTS `lost_found` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `item_name` VARCHAR(255) NOT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  `description` TEXT,
  `contact` VARCHAR(255) DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'Lost',
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `image_path` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `lost_found` (`item_name`, `location`, `description`, `contact`, `status`, `image_path`)
VALUES ('Sample Wallet', 'Library', 'Black leather wallet with ID', '9876543210', 'Lost', NULL);
