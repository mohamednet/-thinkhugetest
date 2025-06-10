<?php
/**
 * Main entry point for the application
 */

// Enable full error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the APP_ROOT constant
define('APP_ROOT', dirname(__DIR__));

try {
    // Load environment variables first
    require_once APP_ROOT . '/config/env.php';
    
    // Load bootstrap file which includes autoloader
    require_once APP_ROOT . '/app/core/bootstrap.php';
    
    // Initialize the application using the singleton pattern
    $app = \App\Core\Application::getInstance();
    
    // Explicitly load routes
    require_once APP_ROOT . '/app/routes/web.php';
    
    // Run the application
    $app->run();
    
} catch (\Exception $e) {
    // Display the exception for debugging
    echo '<div style="background: #f8d7da; color: #721c24; padding: 15px; margin: 10px; border-radius: 5px;">';
    echo '<h2>Application Error</h2>';
    echo '<p><strong>Message:</strong> ' . $e->getMessage() . '</p>';
    echo '<p><strong>File:</strong> ' . $e->getFile() . '</p>';
    echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
    echo '<h3>Stack Trace:</h3>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
    echo '</div>';
}
