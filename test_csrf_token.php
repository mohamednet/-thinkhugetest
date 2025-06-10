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

// Generate a new CSRF token if needed
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Display CSRF token information
echo "<h1>CSRF Token Test</h1>";

echo "<h2>Current Session and CSRF Token</h2>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Path: " . session_get_cookie_params()['path'] . "\n";
echo "CSRF Token: " . $_SESSION['csrf_token'] . "\n";
echo "</pre>";

// Test CSRF token form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Token Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3>CSRF Token Test Form</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= url('test_csrf_result.php') ?>" method="post">
                            <!-- Using csrf_field() helper -->
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        
                        <hr>
                        
                        <h4>Manual CSRF Token Form</h4>
                        <form action="<?= url('test_csrf_result.php') ?>" method="post">
                            <!-- Using direct token value -->
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            
                            <div class="mb-3">
                                <label for="name2" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name2" name="name">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
