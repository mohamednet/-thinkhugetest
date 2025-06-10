<?php
namespace App\Services;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Models\Transaction;

class TransactionService
{
    private $transactionRepository;
    private $clientRepository;
    
    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        ClientRepositoryInterface $clientRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->clientRepository = $clientRepository;
    }
    
    public function getAllTransactions()
    {
        return $this->transactionRepository->findAll();
    }
    
    public function getTotalIncome()
    {
        return $this->transactionRepository->getTotalByType(Transaction::TYPE_INCOME);
    }
    
    public function getTotalExpenses()
    {
        return $this->transactionRepository->getTotalByType(Transaction::TYPE_EXPENSE);
    }
    
    public function getRecentTransactions($limit = 5)
    {
        $transactions = $this->transactionRepository->findRecent($limit);
        
        // Attach client names to transactions for display
        foreach ($transactions as $transaction) {
            $client = $this->clientRepository->findById($transaction->client_id);
            if ($client) {
                $transaction->client_name = $client->name;
            }
        }
        
        return $transactions;
    }
    
    public function getTransactionById($id)
    {
        return $this->transactionRepository->findById($id);
    }
    
    public function getClientTransactions($clientId)
    {
        // Check if client exists
        $client = $this->clientRepository->findById($clientId);
        if (!$client) {
            throw new \Exception("Client not found");
        }
        
        return $this->transactionRepository->findByClientId($clientId);
    }
    
    public function getTransactionsByDateRange($startDate, $endDate)
    {
        // Validate dates
        if (!$this->isValidDate($startDate) || !$this->isValidDate($endDate)) {
            throw new \Exception("Invalid date format");
        }
        
        return $this->transactionRepository->findByDateRange($startDate, $endDate);
    }
    
    public function getClientBalance($clientId)
    {
        // Check if client exists
        $client = $this->clientRepository->findById($clientId);
        if (!$client) {
            throw new \Exception("Client not found");
        }
        
        return $this->transactionRepository->getClientBalance($clientId);
    }
    
    public function createTransaction(array $data)
    {
        // Validate data
        $this->validateTransactionData($data);
        
        // Check if client exists
        $client = $this->clientRepository->findById($data['client_id']);
        if (!$client) {
            throw new \Exception("Client not found");
        }
        
        // Create transaction
        return $this->transactionRepository->create([
            'client_id' => $data['client_id'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'date' => $data['date'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function updateTransaction($id, array $data)
    {
        // Validate data
        $this->validateTransactionData($data);
        
        // Check if transaction exists
        $transaction = $this->transactionRepository->findById($id);
        if (!$transaction) {
            throw new \Exception("Transaction not found");
        }
        
        // Check if client exists
        $client = $this->clientRepository->findById($data['client_id']);
        if (!$client) {
            throw new \Exception("Client not found");
        }
        
        // Update transaction
        return $this->transactionRepository->update($id, [
            'client_id' => $data['client_id'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'date' => $data['date'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function deleteTransaction($id)
    {
        // Check if transaction exists
        $transaction = $this->transactionRepository->findById($id);
        if (!$transaction) {
            throw new \Exception("Transaction not found");
        }
        
        // Delete transaction
        return $this->transactionRepository->delete($id);
    }
    
    private function validateTransactionData(array $data)
    {
        if (empty($data['client_id'])) {
            throw new \Exception("Client is required");
        }
        
        if (empty($data['type']) || !in_array($data['type'], [Transaction::TYPE_INCOME, Transaction::TYPE_EXPENSE])) {
            throw new \Exception("Invalid transaction type");
        }
        
        if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
            throw new \Exception("Amount must be a positive number");
        }
        
        if (empty($data['date']) || !$this->isValidDate($data['date'])) {
            throw new \Exception("Invalid date format");
        }
    }
    
    private function isValidDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
