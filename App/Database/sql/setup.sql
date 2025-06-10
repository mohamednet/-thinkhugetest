-- Setup script for Finance App
-- Creates database, tables and adds initial data

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS finance_app;
USE finance_app;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create clients table
CREATE TABLE IF NOT EXISTS clients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create transactions table
CREATE TABLE IF NOT EXISTS transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id INT UNSIGNED NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add initial admin user (password: admin123)
INSERT INTO users (name, username, password) VALUES 
('Admin User', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Add some sample clients
INSERT INTO clients (name, email, phone, address, notes) VALUES
('John Smith', 'john@example.com', '555-123-4567', '123 Main St, Anytown, USA', 'Regular client'),
('Jane Doe', 'jane@example.com', '555-987-6543', '456 Oak Ave, Somewhere, USA', 'Premium client'),
('Acme Corporation', 'info@acme.com', '555-111-2222', '789 Business Blvd, Metropolis, USA', 'Corporate account');

-- Add some sample transactions
INSERT INTO transactions (client_id, type, amount, date, description) VALUES
(1, 'income', 1500.00, CURDATE(), 'Website development'),
(1, 'expense', 200.00, CURDATE(), 'Hosting fees'),
(2, 'income', 3000.00, CURDATE(), 'Logo design'),
(3, 'income', 5000.00, CURDATE(), 'Marketing campaign'),
(3, 'expense', 1200.00, CURDATE(), 'Advertising costs');
