<?php
/**
 * Database Setup Script
 * 
 * This script initializes the database by creating tables and inserting initial data.
 * Run this script once to set up your application.
 */

// Load environment variables
require_once __DIR__ . '/config/env.php';

// Display header
echo "=================================================\n";
echo "Finance App - Database Setup\n";
echo "=================================================\n\n";

try {
    // Create database connection
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $database = getenv('DB_DATABASE') ?: 'finance_app';
    
    echo "Connecting to MySQL server...\n";
    
    // Connect without database first to create it if needed
    $pdo = new PDO(
        "mysql:host={$host};port={$port}",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Create database if it doesn't exist
    echo "Creating database if it doesn't exist...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}`");
    
    // Switch to the database
    echo "Switching to database '{$database}'...\n";
    $pdo->exec("USE `{$database}`");
    
    // Read SQL file
    echo "Reading SQL schema file...\n";
    $sql = file_get_contents(__DIR__ . '/sql/schema.sql');
    
    // Split SQL file into individual statements
    echo "Executing SQL statements...\n";
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($statement) {
            return !empty($statement) && strpos($statement, '--') !== 0;
        }
    );
    
    // Execute each statement
    foreach ($statements as $statement) {
        // Skip CREATE DATABASE and USE statements as we've already handled them
        if (stripos($statement, 'CREATE DATABASE') === 0 || stripos($statement, 'USE') === 0) {
            continue;
        }
        
        $pdo->exec($statement);
        echo ".";
    }
    
    echo "\n\nDatabase setup completed successfully!\n";
    echo "You can now access your application at: " . (getenv('APP_URL') ?: 'http://localhost') . "\n";
    echo "Default login credentials:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n\n";
    echo "IMPORTANT: For security reasons, please change the default password after logging in.\n";
    
} catch (PDOException $e) {
    echo "\n\nERROR: " . $e->getMessage() . "\n";
    echo "Please check your database configuration in the .env file and try again.\n";
    exit(1);
}
