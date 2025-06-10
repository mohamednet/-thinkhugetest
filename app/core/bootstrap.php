<?php
/**
 * Bootstrap file that loads all necessary components
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader function
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $prefix = 'App\\';
    $base_dir = APP_ROOT . '/app/';
    
    // Check if class uses the prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Get the relative class name
    $relative_class = substr($class, $len);
    
    // Convert namespace separators to directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Load helper functions
require_once APP_ROOT . '/app/core/helpers.php';

// Set default timezone
date_default_timezone_set('UTC');

// Configure session for subdirectory environment
// Use a fixed session path to ensure consistency
$session_path = '/testv1thinkhug';

// Set session cookie parameters to work with subdirectory
session_set_cookie_params([
    'path' => $session_path,
    'httponly' => true
]);

// Start session
session_start();
