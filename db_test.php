<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Database Connection Test</h1>";

// Define constants
define('APP_ROOT', __DIR__);

// Load environment variables manually
function loadEnv($path) {
    if (!file_exists($path)) {
        echo "ENV file not found at: $path<br>";
        return false;
    }
    
    echo "Loading ENV from: $path<br>";
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Remove quotes if present
            if (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
                $value = substr($value, 1, -1);
            } elseif (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1) {
                $value = substr($value, 1, -1);
            }
            
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            
            echo "Set ENV: $name = $value<br>";
        }
    }
    
    return true;
}

// Load .env file
loadEnv(__DIR__ . '/.env');

// Get database connection info
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_DATABASE') ?: 'finance_app';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

echo "<h2>Database Configuration</h2>";
echo "Host: $host<br>";
echo "Port: $port<br>";
echo "Database: $database<br>";
echo "Username: $username<br>";
echo "Password: " . (empty($password) ? "(empty)" : "(set)") . "<br>";

// Try to connect
echo "<h2>Connection Test</h2>";
try {
    echo "Attempting to connect to database...<br>";
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$database",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    echo "<strong style='color:green'>Connection successful!</strong><br>";
    
    // Test a simple query
    echo "<h2>Testing Query</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables in database:<br>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<strong style='color:red'>Connection failed: " . $e->getMessage() . "</strong><br>";
}
?>
