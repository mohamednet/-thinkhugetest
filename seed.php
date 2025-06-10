<?php
/**
 * Database Seeder Script
 * 
 * This script populates the database with sample data for testing
 */

// Define the APP_ROOT constant
define('APP_ROOT', __DIR__);

// Load environment variables
require_once APP_ROOT . '/config/env.php';

// Autoloader for seeder classes
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $prefix = 'App\\Database\\Seeders\\';
    $base_dir = APP_ROOT . '/app/database/seeders/';
    
    // Check if class uses the prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Get the relative class name
    $relative_class = substr($class, $len);
    
    // Convert namespace separators to directory separators
    $file = $base_dir . $relative_class . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Create database connection
try {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $database = getenv('DB_DATABASE') ?: 'finance_app';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    
    $db = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    echo "Connected to database successfully!\n";
    
    // Run the database seeder
    $seeder = new App\Database\Seeders\DatabaseSeeder($db);
    $seeder->run();
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
