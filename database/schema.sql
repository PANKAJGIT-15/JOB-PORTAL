CREATE DATABASE IF NOT EXISTS `job_portal` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `job_portal`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('candidate', 'employer', 'admin') NOT NULL DEFAULT 'candidate',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Categories Table Migration
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (name, slug, description) VALUES
('Software Development', 'software-development', 'Engineering, web, mobile, and cloud roles'),
('UI/UX Design', 'ui-ux-design', 'Product design, UX research, and design systems'),
('Digital Marketing', 'digital-marketing', 'SEO, content marketing, and growth hacking'),
('Data & Analytics', 'data-analytics', 'Data science, BI analytics, and data engineering')
ON DUPLICATE KEY UPDATE name=VALUES(name);
