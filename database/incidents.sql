-- Run this ONCE in HeidiSQL while using the kenya_wildlifeguard database.
-- Does not delete existing users or records.
USE kenya_wildlifeguard;

CREATE TABLE IF NOT EXISTS incidents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    incident_type VARCHAR(60) NOT NULL,
    county VARCHAR(100) NOT NULL,
    location VARCHAR(160) NOT NULL,
    incident_date DATETIME NOT NULL,
    description TEXT NOT NULL,
    photo_filename VARCHAR(120) DEFAULT NULL,
    status ENUM('Pending','In Progress','Resolved') NOT NULL DEFAULT 'Pending',
    assigned_ranger_id INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_reporter_created (reporter_id, created_at),
    INDEX idx_status (status),
    CONSTRAINT fk_incident_reporter FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_incident_ranger FOREIGN KEY (assigned_ranger_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
