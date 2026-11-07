-- Safe to run if the database/table were already created in HeidiSQL.
CREATE DATABASE IF NOT EXISTS kenya_wildlifeguard
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kenya_wildlifeguard;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('citizen','ranger','officer') NOT NULL DEFAULT 'citizen',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
