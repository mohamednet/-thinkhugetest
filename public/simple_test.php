<?php
// Simple test file to check if PHP is working
echo "<h1>Simple Test Page</h1>";
echo "<p>If you can see this, PHP is working correctly.</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";

// Show some server information
echo "<h2>Server Information</h2>";
echo "<pre>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "</pre>";

// Try to include the bootstrap file
echo "<h2>Testing Application Files</h2>";
try {
    require_once dirname(__DIR__) . '/app/core/bootstrap.php';
    echo "<p style='color:green'>Successfully included bootstrap.php</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>Error including bootstrap.php: " . $e->getMessage() . "</p>";
}
?>
