<?php
namespace App\Database\Seeders;

class TransactionSeeder
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function run()
    {
        echo "Seeding transactions table...\n";
        
        // Clear existing data
        $this->db->exec("TRUNCATE TABLE transactions");
        
        // Get all client IDs
        $stmt = $this->db->query("SELECT id FROM clients");
        $clientIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        if (empty($clientIds)) {
            echo "No clients found. Please run the ClientSeeder first.\n";
            return;
        }
        
        // Transaction types
        $types = ['income', 'expense'];
        
        // Description templates
        $incomeDescriptions = [
            'Monthly service fee',
            'Project payment',
            'Consultation fee',
            'Maintenance contract',
            'Product purchase',
            'Annual subscription',
            'Service renewal',
            'Rush order fee'
        ];
        
        $expenseDescriptions = [
            'Materials purchase',
            'Subcontractor payment',
            'Equipment rental',
            'Travel expenses',
            'Software license',
            'Refund',
            'Administrative costs',
            'Discount applied'
        ];
        
        // Generate random transactions for each client
        $stmt = $this->db->prepare("
            INSERT INTO transactions (client_id, type, amount, date, description, created_at, updated_at)
            VALUES (:client_id, :type, :amount, :date, :description, :created_at, :updated_at)
        ");
        
        $transactionCount = 0;
        
        foreach ($clientIds as $clientId) {
            // Generate between 3-10 transactions per client
            $numTransactions = rand(3, 10);
            
            for ($i = 0; $i < $numTransactions; $i++) {
                $type = $types[rand(0, 1)];
                
                // Select appropriate description based on type
                if ($type === 'income') {
                    $description = $incomeDescriptions[array_rand($incomeDescriptions)];
                    $amount = rand(500, 10000) / 10; // Generate amount between $50 and $1000
                } else {
                    $description = $expenseDescriptions[array_rand($expenseDescriptions)];
                    $amount = rand(100, 5000) / 10; // Generate amount between $10 and $500
                }
                
                // Generate a random date within the last 6 months
                $daysAgo = rand(0, 180);
                $date = date('Y-m-d', strtotime("-$daysAgo days"));
                
                $stmt->execute([
                    'client_id' => $clientId,
                    'type' => $type,
                    'amount' => $amount,
                    'date' => $date,
                    'description' => $description,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                
                $transactionCount++;
            }
        }
        
        echo "Transactions seeded successfully! Created $transactionCount transactions.\n";
    }
}
