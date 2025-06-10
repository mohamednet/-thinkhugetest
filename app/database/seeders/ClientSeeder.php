<?php
namespace App\Database\Seeders;

class ClientSeeder
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function run()
    {
        echo "Seeding clients table...\n";
        
        // Clear existing data
        $this->db->exec("TRUNCATE TABLE clients");
        
        // Sample client data
        $clients = [
            [
                'name' => 'Acme Corporation',
                'email' => 'contact@acmecorp.com',
                'phone' => '555-123-4567',
                'address' => '123 Main St, Business District, City',
                'notes' => 'Major corporate client with multiple projects'
            ],
            [
                'name' => 'TechStart Inc',
                'email' => 'info@techstart.com',
                'phone' => '555-987-6543',
                'address' => '456 Innovation Ave, Tech Park, City',
                'notes' => 'Startup client with growing needs'
            ],
            [
                'name' => 'Global Retail Ltd',
                'email' => 'support@globalretail.com',
                'phone' => '555-456-7890',
                'address' => '789 Market St, Shopping District, City',
                'notes' => 'Retail chain with multiple locations'
            ],
            [
                'name' => 'City Hospital',
                'email' => 'admin@cityhospital.org',
                'phone' => '555-111-2222',
                'address' => '101 Health Blvd, Medical District, City',
                'notes' => 'Non-profit healthcare provider'
            ],
            [
                'name' => 'Green Energy Solutions',
                'email' => 'contact@greenenergy.com',
                'phone' => '555-333-4444',
                'address' => '202 Sustainable Way, Eco Park, City',
                'notes' => 'Renewable energy contractor'
            ],
            [
                'name' => 'Education First Academy',
                'email' => 'principal@educationfirst.edu',
                'phone' => '555-555-5555',
                'address' => '303 Learning Lane, University District, City',
                'notes' => 'Private educational institution'
            ],
            [
                'name' => 'Luxury Hotels Group',
                'email' => 'reservations@luxuryhotels.com',
                'phone' => '555-777-8888',
                'address' => '404 Comfort Road, Tourist Area, City',
                'notes' => 'High-end hospitality chain'
            ],
            [
                'name' => 'Fresh Food Distributors',
                'email' => 'orders@freshfood.com',
                'phone' => '555-999-0000',
                'address' => '505 Produce Lane, Industrial Zone, City',
                'notes' => 'Food supply company'
            ]
        ];
        
        // Insert clients
        $stmt = $this->db->prepare("
            INSERT INTO clients (name, email, phone, address, notes, created_at, updated_at)
            VALUES (:name, :email, :phone, :address, :notes, :created_at, :updated_at)
        ");
        
        foreach ($clients as $client) {
            $stmt->execute([
                'name' => $client['name'],
                'email' => $client['email'],
                'phone' => $client['phone'],
                'address' => $client['address'],
                'notes' => $client['notes'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        echo "Clients seeded successfully!\n";
    }
}
