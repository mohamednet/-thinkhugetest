<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\TransactionService;
use App\Services\ClientService;
use App\Repositories\TransactionRepository;
use App\Repositories\ClientRepository;

class ReportController extends Controller
{
    private $transactionService;
    private $clientService;
    
    public function __construct()
    {
        parent::__construct();
        $this->transactionService = new TransactionService(
            new TransactionRepository($this->container->get('db')),
            new ClientRepository($this->container->get('db'))
        );
        $this->clientService = new ClientService(
            new ClientRepository($this->container->get('db'))
        );
    }
    
    public function index()
    {
        // Require authentication
        $this->requireAuth();
        
        // Get all clients for the dropdown
        $clients = $this->clientService->getAllClients();
        
        return $this->render('reports/index', [
            'title' => 'Financial Reports',
            'clients' => $clients
        ]);
    }
    
    public function generate()
    {
        // Require authentication
        $this->requireAuth();
        
        // Validate CSRF token
        $token = $this->request->get('csrf_token');
        if (!verify_csrf_token($token)) {
            flash('error', 'Invalid CSRF token');
            return $this->redirect(url('reports'));
        }
        
        // Get form data
        $clientId = $this->request->get('client_id');
        $reportType = $this->request->get('report_type');
        $startDate = $this->request->get('start_date');
        $endDate = $this->request->get('end_date');
        
        // Validate report type
        if (!in_array($reportType, ['detailed', 'grouped'])) {
            flash('error', 'Invalid report type');
            return $this->redirect(url('reports'));
        }
        
        try {
            // Get client if specified
            $client = null;
            if ($clientId) {
                $client = $this->clientService->getClientById($clientId);
            }
            
            // Get transactions
            $transactions = $this->transactionService->getFilteredTransactions($clientId, $startDate, $endDate);
            
            // Calculate totals
            $income = 0;
            $expenses = 0;
            
            foreach ($transactions as $transaction) {
                if ($transaction->type === 'income') {
                    $income += $transaction->amount;
                } else {
                    $expenses += $transaction->amount;
                }
            }
            
            $balance = $income - $expenses;
            
            // Group transactions by client if needed
            $groupedTransactions = [];
            if ($reportType === 'grouped' && !$clientId) {
                foreach ($transactions as $transaction) {
                    $clientId = $transaction->client_id;
                    if (!isset($groupedTransactions[$clientId])) {
                        $groupedTransactions[$clientId] = [
                            'client_name' => $transaction->client_name,
                            'income' => 0,
                            'expenses' => 0,
                            'balance' => 0
                        ];
                    }
                    
                    if ($transaction->type === 'income') {
                        $groupedTransactions[$clientId]['income'] += $transaction->amount;
                    } else {
                        $groupedTransactions[$clientId]['expenses'] += $transaction->amount;
                    }
                    
                    $groupedTransactions[$clientId]['balance'] = 
                        $groupedTransactions[$clientId]['income'] - $groupedTransactions[$clientId]['expenses'];
                }
            }
            
            return $this->render('reports/results', [
                'title' => 'Report Results',
                'client' => $client,
                'reportType' => $reportType,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'transactions' => $transactions,
                'groupedTransactions' => $groupedTransactions,
                'income' => $income,
                'expenses' => $expenses,
                'balance' => $balance
            ]);
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            return $this->redirect(url('reports'));
        }
    }
}
