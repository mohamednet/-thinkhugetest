<?php
namespace App\Services;

use App\Core\Database;

class AuthService
{
    private $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    
    public function login($username, $password)
    {
        // Find user by username
        $user = $this->db->fetch(
            "SELECT * FROM administrators WHERE username = ?",
            [$username]
        );
        
        if (!$user) {
            return false;
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        
        return $user;
    }
    
    public function register($username, $password)
    {
        // Check if username already exists
        $existingUser = $this->db->fetch(
            "SELECT id FROM administrators WHERE username = ?",
            [$username]
        );
        
        if ($existingUser) {
            return false;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $userId = $this->db->insert('administrators', [
            'username' => $username,
            'password' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $userId;
    }
    
    public function getUserById($id)
    {
        return $this->db->fetch(
            "SELECT id, username, created_at FROM administrators WHERE id = ?",
            [$id]
        );
    }
}
