-- Migration: Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(100)    NOT NULL,
    `username`   VARCHAR(50)     NOT NULL UNIQUE,
    `password`   VARCHAR(255)    NOT NULL,
    `role`       ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    `is_active`  TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin user (password: Admin@1234 — GANTI SEGERA!)
INSERT INTO `users` (`name`, `username`, `password`, `role`) VALUES
('Administrator', 'admin', '$2y$12$eImiTXuWVxfM37uY4JANjQ==', 'admin');
