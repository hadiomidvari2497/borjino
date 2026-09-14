-- Borjino Database Initialization Script
-- This runs on first container startup

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `borjino` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE `borjino`;

-- Create user if not exists (for external connections)
CREATE USER IF NOT EXISTS 'borjino'@'%' IDENTIFIED BY 'borjino_secret';
GRANT ALL PRIVILEGES ON `borjino`.* TO 'borjino'@'%';
FLUSH PRIVILEGES;

-- Set default timezone
SET time_zone = '+03:30';

-- Basic tables for initial setup
-- These will be replaced by proper migrations later

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(30) NULL,
    `full_name` VARCHAR(255) NULL,
    `avatar` VARCHAR(500) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_system_admin` TINYINT(1) NOT NULL DEFAULT 0,
    `last_login_at` DATETIME NULL,
    `email_verified_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_username` (`username`),
    UNIQUE KEY `uq_users_email` (`email`),
    KEY `ix_users_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Buildings table
CREATE TABLE IF NOT EXISTS `buildings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `postal_code` VARCHAR(20) NULL,
    `building_type` ENUM('residential','commercial','office','educational','other') NOT NULL,
    `province` VARCHAR(100) NULL,
    `city` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `construction_date` DATE NULL,
    `total_parking_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `total_storage_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `status` ENUM('active','inactive','under_construction') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `ix_buildings_type` (`building_type`),
    KEY `ix_buildings_status` (`status`),
    KEY `ix_buildings_province_city` (`province`, `city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blocks table
CREATE TABLE IF NOT EXISTS `blocks` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `building_id` BIGINT UNSIGNED NOT NULL,
    `block_number` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NULL,
    `floor_count` INT UNSIGNED NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_blocks_building_number` (`building_id`, `block_number`),
    KEY `ix_blocks_building` (`building_id`),
    CONSTRAINT `fk_blocks_building` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Units table
CREATE TABLE IF NOT EXISTS `units` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `building_id` BIGINT UNSIGNED NOT NULL,
    `block_id` BIGINT UNSIGNED NOT NULL,
    `unit_number` VARCHAR(50) NOT NULL,
    `floor_number` INT NOT NULL,
    `area_sqm` DECIMAL(12,2) NOT NULL,
    `status` ENUM('sold','rented','vacant','under_repair') NOT NULL DEFAULT 'vacant',
    `financial_status` ENUM('debtor','creditor','settled') NOT NULL DEFAULT 'settled',
    `direction` ENUM('north','south','east','west') NULL,
    `postal_code` VARCHAR(20) NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_units_block_number` (`block_id`, `unit_number`),
    KEY `ix_units_building` (`building_id`),
    KEY `ix_units_block` (`block_id`),
    KEY `ix_units_status` (`status`),
    KEY `ix_units_financial_status` (`financial_status`),
    CONSTRAINT `fk_units_building` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_units_block` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CHECK (`floor_number` >= 0),
    CHECK (`area_sqm` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - should be changed!)
INSERT IGNORE INTO `users` (`username`, `password_hash`, `full_name`, `is_system_admin`, `is_active`)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 1, 1);

-- Insert sample building data
INSERT IGNORE INTO `buildings` (`id`, `name`, `building_type`, `province`, `city`, `address`, `construction_date`, `total_parking_count`, `total_storage_count`, `status`)
VALUES 
(1, 'ساختمان سپهر', 'residential', 'تهران', 'تهران', 'تهران، خیابان ولیعصر، پلاک ۱۲۳', '1398-03-15', 50, 48, 'active'),
(2, 'مجتمع تجاری پارسیان', 'commercial', 'تهران', 'شهریار', 'شهریار، بلوار شهیدان، پلاک ۴۵', '1400-06-20', 30, 20, 'active'),
(3, 'ساختمان اداری آفتاب', 'office', 'البرز', 'کرج', 'کرج، خیابان غدیر، پلاک ۱۲', '1399-11-10', 15, 10, 'under_construction');

-- Insert sample blocks
INSERT IGNORE INTO `blocks` (`id`, `building_id`, `block_number`, `name`, `floor_count`)
VALUES
(1, 1, 1, 'بلوک الف', 8),
(2, 1, 2, 'بلوک ب', 8),
(3, 1, 3, 'بلوک ج', 6),
(4, 2, 1, 'بلوک تجاری ۱', 4),
(5, 2, 2, 'بلوک تجاری ۲', 4);

-- Insert sample units
INSERT IGNORE INTO `units` (`id`, `building_id`, `block_id`, `unit_number`, `floor_number`, `area_sqm`, `status`, `financial_status`, `direction`)
VALUES
(1, 1, 1, '۱۰۱', 1, 120.00, 'sold', 'settled', 'north'),
(2, 1, 1, '۱۰۲', 1, 95.00, 'rented', 'debtor', 'south'),
(3, 1, 1, '۲۰۱', 2, 140.00, 'vacant', 'settled', 'north'),
(4, 1, 2, '۱۰۱', 1, 110.00, 'sold', 'creditor', 'east'),
(5, 2, 4, 'م-۱', 1, 80.00, 'rented', 'settled', 'west');