-- Emergency Alert System — Phase 1 schema (Public/Access pages)
-- Import into phpMyAdmin (or `mysql -u root -p < schema.sql`) on your WAMP MySQL.

CREATE DATABASE IF NOT EXISTS eas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eas_db;

CREATE TABLE IF NOT EXISTS users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(150)        NOT NULL,
    email         VARCHAR(190)        NOT NULL UNIQUE,
    password_hash VARCHAR(255)        NOT NULL,
    role          ENUM('student','staff','admin') NOT NULL DEFAULT 'student',
    department    VARCHAR(150)        NULL,
    phone         VARCHAR(30)         NULL,
    created_at    DATETIME            NOT NULL,
    INDEX idx_users_role (role)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS password_resets (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    token      VARCHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used       TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Optional: seed an admin account for testing.
-- Generate your own hash locally with: php -r "echo password_hash('YourPassword', PASSWORD_DEFAULT);"
-- INSERT INTO users (full_name, email, password_hash, role, department, created_at)
-- VALUES ('System Admin', 'admin@eas.local', '<paste generated hash here>', 'admin', 'IT', NOW());
