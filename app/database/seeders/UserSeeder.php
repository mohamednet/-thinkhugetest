<?php
namespace App\Database\Seeders;

class UserSeeder
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function run()
    {
        echo "Seeding users table...\n";
        
        // Clear existing data
        $this->db->exec("TRUNCATE TABLE users");
        
        // Insert admin user
        $stmt = $this->db->prepare("
            INSERT INTO users (name, username, password, created_at, updated_at)
            VALUES (:name, :username, :password, :created_at, :updated_at)
        ");
        
        $stmt->execute([
            'name' => 'Admin User',
            'username' => 'admin',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Insert additional users
        $users = [
            [
                'name' => 'John Doe',
                'username' => 'john',
                'password' => 'password123'
            ],
            [
                'name' => 'Jane Smith',
                'username' => 'jane',
                'password' => 'password123'
            ]
        ];
        
        foreach ($users as $user) {
            $stmt->execute([
                'name' => $user['name'],
                'username' => $user['username'],
                'password' => password_hash($user['password'], PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        echo "Users seeded successfully!\n";
    }
}
