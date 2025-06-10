<?php
// Enable full error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Mod Rewrite Test</h1>";

// Check if mod_rewrite is loaded
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    $mod_rewrite = in_array('mod_rewrite', $modules);
} else {
    $mod_rewrite = getenv('HTTP_MOD_REWRITE') == 'On' ? true : false;
}

echo "<p>Mod Rewrite is " . ($mod_rewrite ? "enabled" : "not enabled") . "</p>";

// Display server variables
echo "<h2>Server Variables</h2>";
echo "<pre>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "</pre>";

// Check if .htaccess is being processed
echo "<h2>.htaccess Test</h2>";
echo "<p>If you see this message, it means that the .htaccess file is not blocking direct access to PHP files.</p>";
echo "<p>This is expected behavior for PHP files, but routes like /login should be handled by the router.</p>";
