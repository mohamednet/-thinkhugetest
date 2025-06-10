<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\ClientService;
use App\Services\TransactionService;
use App\Repositories\ClientRepository;
use App\Repositories\TransactionRepository;

class HomeController extends Controller
{
    private $clientService;
    private $transactionService;
    
    public function __construct()
    {
        parent::__construct();
        $this->clientService = new ClientService(
            new ClientRepository($this->container->get('db'))
        );
        $this->transactionService = new TransactionService(
            new TransactionRepository($this->container->get('db')),
            new ClientRepository($this->container->get('db'))
        );
    }
    
    public function index()
    {
        // Require authentication for dashboard
        $this->requireAuth();
        
        // Get dashboard data
        $totalClients = $this->clientService->getTotalClients();
        $recentClients = $this->clientService->getRecentClients(5);
        
        $totalIncome = $this->transactionService->getTotalIncome();
        $totalExpenses = $this->transactionService->getTotalExpenses();
        $recentTransactions = $this->transactionService->getRecentTransactions(5);
        
        return $this->render('home/dashboard', [
            'title' => 'Dashboard',
            'active_page' => 'dashboard',
            'totalClients' => $totalClients,
            'recentClients' => $recentClients,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'recentTransactions' => $recentTransactions,
            'username' => $_SESSION['username'] ?? 'Guest'
        ]);
    }
}
