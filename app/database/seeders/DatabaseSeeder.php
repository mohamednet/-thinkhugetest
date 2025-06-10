<?php
namespace App\Database\Seeders;

require_once __DIR__ . '/../../core/bootstrap.php';

use App\Database\Seeders\UserSeeder;
use App\Database\Seeders\ClientSeeder;
use App\Database\Seeders\TransactionSeeder;

class DatabaseSeeder
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function run()
    {
        echo "Starting database seeding...\n";
        
        try {
            // Disable foreign key checks temporarily
            $this->db->exec('SET FOREIGN_KEY_CHECKS = 0');
            
            // Run seeders in order
            (new UserSeeder($this->db))->run();
            (new ClientSeeder($this->db))->run();
            (new TransactionSeeder($this->db))->run();
            
            // Re-enable foreign key checks
            $this->db->exec('SET FOREIGN_KEY_CHECKS = 1');
            
            echo "Database seeding completed successfully!\n";
        } catch (\Exception $e) {
            // Make sure to re-enable foreign key checks even if there's an error
            $this->db->exec('SET FOREIGN_KEY_CHECKS = 1');
            throw $e;
        }
    }
}
