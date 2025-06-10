<?php
$password = 'admin123';
$hash = '$2y$10$9YAzYDGzTRW7bTJVPAucJuABfH4ORUgW7FzYhOW6M9k.SvzMQkFcG';

echo "Password: $password\n";
echo "Hash: $hash\n";
echo "Verification result: " . (password_verify($password, $hash) ? 'VALID' : 'INVALID') . "\n";

// Generate a new hash for comparison
echo "\nGenerating a new hash for the same password:\n";
$newHash = password_hash($password, PASSWORD_DEFAULT);
echo "New hash: $newHash\n";
echo "New hash verification: " . (password_verify($password, $newHash) ? 'VALID' : 'INVALID') . "\n";
?>
