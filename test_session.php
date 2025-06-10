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

echo "<h1>Session Test</h1>";

// Display session information
echo "<h2>Session Information</h2>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Path: " . session_get_cookie_params()['path'] . "\n";
echo "Session Cookie: " . (isset($_COOKIE[session_name()]) ? $_COOKIE[session_name()] : 'Not set') . "\n";
echo "</pre>";

// Display session contents
echo "<h2>Session Contents</h2>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

// Test login form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3>Test Login Form</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo url('login'); ?>" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="admin" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="admin123" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
