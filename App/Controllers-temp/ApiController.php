<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\TransactionService;
use App\Repositories\TransactionRepository;
use App\Repositories\ClientRepository;

class ApiController extends Controller
{
    private $transactionService;
    
    public function __construct()
    {
        parent::__construct();
        $this->transactionService = new TransactionService(
            new TransactionRepository($this->container->get('db')),
            new ClientRepository($this->container->get('db'))
        );
    }
    
    public function getClientTransactions($clientId)
    {
        // Require authentication
        $this->requireAuth();
        
        // Get query parameters
        $startDate = $this->request->get('start_date');
        $endDate = $this->request->get('end_date');
        
        try {
            // Get transactions
            $transactions = $this->transactionService->getClientTransactions($clientId);
            
            // Filter by date range if provided
            if ($startDate && $endDate) {
                $transactions = array_filter($transactions, function($transaction) use ($startDate, $endDate) {
                    $date = $transaction->date;
                    return $date >= $startDate && $date <= $endDate;
                });
            }
            
            // Calculate balance
            $balance = 0;
            foreach ($transactions as $transaction) {
                if ($transaction->isIncome()) {
                    $balance += $transaction->amount;
                } else {
                    $balance -= $transaction->amount;
                }
            }
            
            // Format transactions for API response
            $formattedTransactions = array_map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => (float) $transaction->amount,
                    'formatted_amount' => $transaction->getFormattedAmount(),
                    'description' => $transaction->description,
                    'date' => $transaction->date,
                    'formatted_date' => $transaction->getFormattedDate()
                ];
            }, $transactions);
            
            // Return JSON response
            return $this->json([
                'success' => true,
                'data' => [
                    'client_id' => (int) $clientId,
                    'transactions' => array_values($formattedTransactions),
                    'balance' => $balance,
                    'formatted_balance' => number_format($balance, 2),
                    'count' => count($formattedTransactions)
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
