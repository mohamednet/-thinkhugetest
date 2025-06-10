<?php
// Define APP_ROOT constant
define('APP_ROOT', __DIR__);

// Include helper functions
require_once APP_ROOT . '/app/core/helpers.php';

// Start session with explicit parameters
session_set_cookie_params([
    'path' => '/testv1thinkhug',
    'httponly' => true
]);
session_start();

// Check CSRF token
$token = $_POST['csrf_token'] ?? '';
$valid = verify_csrf_token($token);

// Display result
echo "<h1>CSRF Token Verification Result</h1>";

echo "<pre>";
echo "Submitted Token: " . htmlspecialchars($token) . "\n";
echo "Session Token: " . ($_SESSION['csrf_token'] ?? 'Not set') . "\n";
echo "Token Valid: " . ($valid ? 'Yes' : 'No') . "\n";
echo "</pre>";

// Link to go back
echo "<p><a href='" . url('test_csrf_token.php') . "'>Go back to test form</a></p>";
?>
