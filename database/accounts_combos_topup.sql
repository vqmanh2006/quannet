CREATE DATABASE IF NOT EXISTS quan_net CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quan_net;

ALTER TABLE computers ADD COLUMN IF NOT EXISTS machine_group ENUM('fps','couple') NOT NULL DEFAULT 'fps' AFTER computer_name;

CREATE TABLE IF NOT EXISTS customer_accounts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    balance DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('active','locked') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS combos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    machine_group ENUM('all','fps','couple') NOT NULL DEFAULT 'all',
    starts_at TIME NOT NULL,
    ends_at TIME NOT NULL,
    crosses_midnight TINYINT(1) NOT NULL DEFAULT 0,
    price DECIMAL(12,2) NOT NULL,
    duration_minutes INT UNSIGNED NOT NULL DEFAULT 300,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS account_combos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_id INT UNSIGNED NOT NULL,
    combo_id INT UNSIGNED NOT NULL,
    remaining_minutes INT UNSIGNED NOT NULL,
    expires_at DATETIME NULL,
    status ENUM('available','using','depleted','expired') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_account_combos_account FOREIGN KEY (account_id) REFERENCES customer_accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_account_combos_combo FOREIGN KEY (combo_id) REFERENCES combos(id) ON DELETE RESTRICT,
    INDEX idx_account_combos_active (account_id, status, expires_at)
);

CREATE TABLE IF NOT EXISTS account_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_id INT UNSIGNED NOT NULL,
    type ENUM('deposit','buy_combo','usage_charge','refund','manual_adjustment') NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    description VARCHAR(255) NULL,
    reference_id BIGINT UNSIGNED NULL,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_account_transactions_account FOREIGN KEY (account_id) REFERENCES customer_accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_account_transactions_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_account_transactions_account (account_id, created_at)
);

INSERT INTO combos (name, machine_group, starts_at, ends_at, crosses_midnight, price, duration_minutes, status)
SELECT 'Combo sáng', 'all', '07:00:00', '12:00:00', 0, 23000, 300, 'active' WHERE NOT EXISTS (SELECT 1 FROM combos WHERE name = 'Combo sáng');
INSERT INTO combos (name, machine_group, starts_at, ends_at, crosses_midnight, price, duration_minutes, status)
SELECT 'Combo tối', 'all', '17:00:00', '22:00:00', 0, 27000, 300, 'active' WHERE NOT EXISTS (SELECT 1 FROM combos WHERE name = 'Combo tối');
INSERT INTO combos (name, machine_group, starts_at, ends_at, crosses_midnight, price, duration_minutes, status)
SELECT 'Combo đêm', 'all', '22:00:00', '07:00:00', 1, 30000, 540, 'active' WHERE NOT EXISTS (SELECT 1 FROM combos WHERE name = 'Combo đêm');
