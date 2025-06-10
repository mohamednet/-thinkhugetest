<?php
// Enable full error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Application Diagnostic</h1>";

try {
    // Define the application root directory
    define('APP_ROOT', dirname(__DIR__));
    
    echo "<h2>Loading Environment Variables</h2>";
    // Load environment variables
    require_once APP_ROOT . '/config/env.php';
    echo "<p style='color:green'>✓ Environment variables loaded</p>";
    
    echo "<h2>Loading Bootstrap</h2>";
    // Load the bootstrap file
    require_once APP_ROOT . '/app/core/bootstrap.php';
    echo "<p style='color:green'>✓ Bootstrap loaded</p>";
    
    echo "<h2>Initializing Application</h2>";
    // Initialize the application
    try {
        $app = new \App\Core\Application();
        echo "<p style='color:green'>✓ Application initialized</p>";
    } catch (\Exception $e) {
        echo "<p style='color:red'>✗ Application initialization failed: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        exit;
    }
    
    echo "<h2>Loading Routes</h2>";
    try {
        // Load routes manually
        require_once APP_ROOT . '/app/routes/web.php';
        echo "<p style='color:green'>✓ Routes loaded</p>";
    } catch (\Exception $e) {
        echo "<p style='color:red'>✗ Routes loading failed: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        exit;
    }
    
    echo "<h2>Resolving Route</h2>";
    try {
        // Get the current path
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        
        // Remove the base path for subdirectory installations
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $scriptDir = dirname($scriptName);
        if ($scriptDir !== '/') {
            $basePath = str_replace('\\', '/', $scriptDir);
            if (strpos($path, $basePath) === 0) {
                $path = substr($path, strlen($basePath));
            }
        }
        
        // Ensure path starts with /
        if (!$path) {
            $path = '/';
        } elseif ($path[0] !== '/') {
            $path = '/' . $path;
        }
        
        echo "<p>Current path: <code>$path</code></p>";
        
        // Try to resolve the route
        $router = $app->getRouter();
        $result = $router->resolve();
        echo "<p style='color:green'>✓ Route resolved</p>";
        echo "<p>Result: " . (is_string($result) ? htmlspecialchars($result) : gettype($result)) . "</p>";
    } catch (\Exception $e) {
        echo "<p style='color:red'>✗ Route resolution failed: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        exit;
    }
    
} catch (\Exception $e) {
    echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
