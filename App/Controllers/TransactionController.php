<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\TransactionService;
use App\Services\ClientService;
use App\Repositories\TransactionRepository;
use App\Repositories\ClientRepository;

class TransactionController extends Controller
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
    
    public function index($clientId)
    {
        // Require authentication
        $this->requireAuth();
        
        try {
            // Get client
            $client = $this->clientService->getClientById($clientId);
            
            if (!$client) {
                flash('error', 'Client not found');
                return $this->redirect(url('clients'));
            }
            
            // Get transactions
            $transactions = $this->transactionService->getClientTransactions($clientId);
            
            // Get client balance
            $balance = $this->transactionService->getClientBalance($clientId);
            
            return $this->render('transactions/index', [
                'title' => 'Transactions for ' . $client->name,
                'client' => $client,
                'transactions' => $transactions,
                'balance' => $balance
            ]);
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            return $this->redirect(url('clients'));
        }
    }
    
    // Create method removed as it's now handled by the modal in index view
    
    public function store($clientId)
    {
        // Require authentication
        $this->requireAuth();
        
        // Validate CSRF token
        $token = $this->request->get('csrf_token');
        if (!verify_csrf_token($token)) {
            flash('error', 'Invalid CSRF token');
            return $this->redirect(url('clients/' . $clientId . '/transactions'));
        }
        
        try {
            // Get client
            $client = $this->clientService->getClientById($clientId);
            
            if (!$client) {
                flash('error', 'Client not found');
                return $this->redirect(url('clients'));
            }
            
            // Get form data and validate amount
            $amount = $this->request->get('amount');
            
            // Check if amount exceeds database limit (DECIMAL(10,2) = max 99999999.99)
            if (is_numeric($amount) && $amount > 99999999.99) {
                flash('error', 'Amount exceeds maximum allowed value (99,999,999.99). Please enter a smaller amount.');
                return $this->redirect(url('clients/' . $clientId . '/transactions'));
            }
            
            $data = [
                'client_id' => $clientId,
                'type' => $this->request->get('type'),
                'amount' => $amount,
                'date' => $this->request->get('date'),
                'description' => $this->request->get('description')
            ];
            
            // Store form data for repopulation on error
            $_SESSION['old'] = $data;
            
            // Create transaction
            $transaction = $this->transactionService->createTransaction($data);
            
            // Clear old input
            unset($_SESSION['old']);
            
            flash('success', 'Transaction added successfully');
            return $this->redirect(url('clients/' . $clientId . '/transactions'));
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            return $this->redirect(url('clients/' . $clientId . '/transactions/create'));
        }
    }
    
    // Edit method removed as it's now handled by the modal in index view
    
    public function update($id)
    {
        // Require authentication
        $this->requireAuth();
        
        // Validate CSRF token
        $token = $this->request->get('csrf_token');
        if (!verify_csrf_token($token)) {
            flash('error', 'Invalid CSRF token');
            return $this->redirect(url('transactions/' . $id . '/edit'));
        }
        
        try {
            // Get transaction
            $transaction = $this->transactionService->getTransactionById($id);
            
            if (!$transaction) {
                flash('error', 'Transaction not found');
                return $this->redirect(url('clients'));
            }
            
            // Get form data and validate amount
            $amount = $this->request->get('amount');
            
            // Check if amount exceeds database limit (DECIMAL(10,2) = max 99999999.99)
            if (is_numeric($amount) && $amount > 99999999.99) {
                flash('error', 'Amount exceeds maximum allowed value (99,999,999.99). Please enter a smaller amount.');
                return $this->redirect(url('transactions/' . $id . '/edit'));
            }
            
            $data = [
                'id' => $id,
                'client_id' => $transaction->client_id,
                'type' => $this->request->get('type'),
                'amount' => $amount,
                'date' => $this->request->get('date'),
                'description' => $this->request->get('description')
            ];
            
            // Update transaction
            $this->transactionService->updateTransaction($id, $data);
            
            flash('success', 'Transaction updated successfully');
            return $this->redirect(url('clients/' . $transaction->client_id . '/transactions'));
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            return $this->redirect(url('transactions/' . $id . '/edit'));
        }
    }
    
    public function delete($id)
    {
        // Require authentication
        $this->requireAuth();
        
        // Validate CSRF token
        $token = $this->request->get('csrf_token');
        if (!verify_csrf_token($token)) {
            flash('error', 'Invalid CSRF token');
            return $this->redirect(url('clients'));
        }
        
        try {
            // Get transaction
            $transaction = $this->transactionService->getTransactionById($id);
            
            if (!$transaction) {
                flash('error', 'Transaction not found');
                return $this->redirect(url('clients'));
            }
            
            $clientId = $transaction->client_id;
            
            // Delete transaction
            $this->transactionService->deleteTransaction($id);
            
            flash('success', 'Transaction deleted successfully');
            return $this->redirect(url('clients/' . $clientId . '/transactions'));
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            return $this->redirect(url('clients'));
        }
    }
}
