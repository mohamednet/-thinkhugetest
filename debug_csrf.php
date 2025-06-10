<?php
// Start session
session_start();

// Display current CSRF token
echo "<h1>CSRF Token Debug</h1>";
echo "<p>Current CSRF token in session: " . ($_SESSION['csrf_token'] ?? 'Not set') . "</p>";

// Generate a new token if needed
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo "<p>Generated new CSRF token: " . $_SESSION['csrf_token'] . "</p>";
}

// Display session information
echo "<h2>Session Information</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Display cookie information
echo "<h2>Cookie Information</h2>";
echo "<pre>";
print_r($_COOKIE);
echo "</pre>";

// Display session configuration
echo "<h2>Session Configuration</h2>";
echo "<p>Session name: " . session_name() . "</p>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session save path: " . session_save_path() . "</p>";
echo "<p>Session cookie params: </p>";
echo "<pre>";
print_r(session_get_cookie_params());
echo "</pre>";

// Display PHP info related to sessions
echo "<h2>PHP Session Settings</h2>";
echo "<table border='1'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>session.cookie_path</td><td>" . ini_get('session.cookie_path') . "</td></tr>";
echo "<tr><td>session.cookie_domain</td><td>" . ini_get('session.cookie_domain') . "</td></tr>";
echo "<tr><td>session.cookie_secure</td><td>" . ini_get('session.cookie_secure') . "</td></tr>";
echo "<tr><td>session.cookie_httponly</td><td>" . ini_get('session.cookie_httponly') . "</td></tr>";
echo "<tr><td>session.cookie_samesite</td><td>" . ini_get('session.cookie_samesite') . "</td></tr>";
echo "</table>";
?>
