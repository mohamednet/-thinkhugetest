<?php
// Database connection
$host = 'localhost';
$dbname = 'finance_app';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Generate hash for admin123
    $newPassword = 'admin123';
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update admin user
    $stmt = $db->prepare("UPDATE administrators SET password = ? WHERE username = 'admin'");
    $result = $stmt->execute([$newHash]);
    
    if ($result) {
        echo "Admin password updated successfully!\n";
        echo "Username: admin\n";
        echo "Password: $newPassword\n";
        echo "New hash: $newHash\n";
    } else {
        echo "Failed to update admin password.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
