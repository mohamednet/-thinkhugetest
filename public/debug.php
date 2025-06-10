<?php
// Enable all error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define APP_ROOT constant first
define('APP_ROOT', dirname(__DIR__));

echo "<h1>Debug Information</h1>";
echo "<h2>PHP Version: " . phpversion() . "</h2>";

echo "<h2>Server Variables:</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";

echo "<h2>Environment Variables:</h2>";
echo "<pre>";
print_r($_ENV);
echo "</pre>";

echo "<h2>Include Path:</h2>";
echo get_include_path();

echo "<h2>Current Working Directory:</h2>";
echo getcwd();

// Test database connection
echo "<h2>Database Connection Test:</h2>";
try {
    require_once APP_ROOT . '/config/env.php';
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db = $_ENV['DB_DATABASE'] ?? 'finance_app';
    $user = $_ENV['DB_USERNAME'] ?? 'root';
    $pass = $_ENV['DB_PASSWORD'] ?? '';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!";
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
