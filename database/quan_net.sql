CREATE DATABASE IF NOT EXISTS quan_net
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE quan_net;

DROP TABLE IF EXISTS gaming_sessions;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS computers;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE computers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    computer_code VARCHAR(20) NOT NULL UNIQUE,
    computer_name VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    hourly_rate DECIMAL(10,2) NOT NULL DEFAULT 10000,
    status ENUM('available', 'playing', 'maintenance') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    note TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE gaming_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    computer_id INT NOT NULL,
    customer_id INT DEFAULT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME DEFAULT NULL,
    hourly_rate DECIMAL(10,2) NOT NULL,
    total_fee DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('playing', 'completed', 'cancelled') NOT NULL DEFAULT 'playing',
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_gaming_sessions_computer
        FOREIGN KEY (computer_id) REFERENCES computers (id)
        ON UPDATE CASCADE,
    CONSTRAINT fk_gaming_sessions_customer
        FOREIGN KEY (customer_id) REFERENCES customers (id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_gaming_sessions_user
        FOREIGN KEY (created_by) REFERENCES users (id)
        ON UPDATE CASCADE ON DELETE SET NULL
);

INSERT INTO users (username, password, full_name, role)
VALUES
    ('admin', '$2y$10$XczYb9xJj3QprJcIyhxDzuitaNv09gOhFtwgQ1KQbFHm7b/zeZ/vy', 'Quản trị viên', 'admin'),
    ('staff', '$2y$10$lZAimorjOU00VZVIIHg30uYfA6roH7iVZMwP.OeFLblimWdLd8gBi', 'Nhân viên demo', 'staff');

INSERT INTO computers (computer_code, computer_name, ip_address, hourly_rate, status)
VALUES
    ('PC01', 'Máy 01', '192.168.1.101', 12000, 'available'),
    ('PC02', 'Máy 02', '192.168.1.102', 12000, 'maintenance');

INSERT INTO customers (full_name, phone, email, note)
VALUES
    ('Nguyễn Văn A', '0900000001', 'a@example.com', 'Khách quen');
