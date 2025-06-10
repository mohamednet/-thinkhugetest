<?php
/**
 * Database Migration Script
 * 
 * This script creates the necessary tables for the application
 */

// Define the APP_ROOT constant
define('APP_ROOT', dirname(dirname(dirname(__DIR__))));

// Load environment variables
require_once APP_ROOT . '/config/env.php';

// Create database connection
try {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $database = getenv('DB_DATABASE') ?: 'finance_app';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    
    $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
    
    $db = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    echo "Connected to database successfully!\n";
    
    // Create database if not exists
    $db->exec("CREATE DATABASE IF NOT EXISTS `$database`");
    $db->exec("USE `$database`");
    
    echo "Using database: $database\n";
    
    // Create users table
    $db->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    echo "Created users table\n";
    
    // Create clients table
    $db->exec("
        CREATE TABLE IF NOT EXISTS `clients` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(100) DEFAULT NULL,
            `phone` VARCHAR(20) DEFAULT NULL,
            `address` TEXT DEFAULT NULL,
            `notes` TEXT DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    echo "Created clients table\n";
    
    // Create transactions table
    $db->exec("
        CREATE TABLE IF NOT EXISTS `transactions` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `client_id` INT UNSIGNED NOT NULL,
            `type` ENUM('income', 'expense') NOT NULL,
            `amount` DECIMAL(10, 2) NOT NULL,
            `date` DATE NOT NULL,
            `description` TEXT DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    echo "Created transactions table\n";
    
    echo "Database migration completed successfully!\n";
    
} catch (PDOException $e) {
    die("Database migration failed: " . $e->getMessage());
}
