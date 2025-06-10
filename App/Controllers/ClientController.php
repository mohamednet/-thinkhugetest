<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\ClientService;
use App\Repositories\ClientRepository;

class ClientController extends Controller
{
    private $clientService;
    
    public function __construct()
    {
        parent::__construct();
        $this->clientService = new ClientService(
            new ClientRepository($this->container->get('db'))
        );
    }
    
    public function index()
    {
        // Require authentication
        $this->requireAuth();
        
        // Get search term if provided
        $search = $this->request->get('search');
        
        // Get clients
        if ($search) {
            $clients = $this->clientService->searchClients($search);
        } else {
            $clients = $this->clientService->getAllClients();
        }
        
        return $this->render('clients/index', [
            'title' => 'Clients',
            'active_page' => 'clients',
            'clients' => $clients,
            'search' => $search
        ]);
    }
    
    // Show method removed as it's now handled by the modal in index view
    
    // Create method removed as it's now handled by the modal in index view
    
    public function store()
    {
        // Require authentication
        $this->requireAuth();
        
        // Validate CSRF token
        $token = $this->request->get('csrf_token');
        if (!verify_csrf_token($token)) {
            flash('error', 'Invalid CSRF token');
            return $this->redirect(url('clients'));
        }
        
        // Get form data
        $data = [
            'name' => $this->request->get('name'),
            'email' => $this->request->get('email'),
            'phone' => $this->request->get('phone'),
            'address' => $this->request->get('address'),
            'notes' => $this->request->get('notes')
        ];
        
        // Store form data for repopulation on error
        $_SESSION['old'] = $data;
        
        try {
            // Create client
            $client = $this->clientService->createClient($data);
            
            // Clear old input
            unset($_SESSION['old']);
            
            flash('success', 'Client created successfully');
            return $this->redirect(url('clients'));
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            return $this->redirect(url('clients'));
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
            return $this->redirect(url('clients'));
        }
        
        // Get form data
        $data = [
            'id' => $id,
            'name' => $this->request->get('name'),
            'email' => $this->request->get('email'),
            'phone' => $this->request->get('phone'),
            'address' => $this->request->get('address'),
            'notes' => $this->request->get('notes')
        ];
        
        try {
            // Update client
            $this->clientService->updateClient($id, $data);
            
            flash('success', 'Client updated successfully');
            return $this->redirect(url('clients'));
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            return $this->redirect(url('clients'));
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
            // Delete client
            $this->clientService->deleteClient($id);
            
            flash('success', 'Client deleted successfully');
            return $this->redirect(url('clients'));
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            return $this->redirect(url('clients'));
        }
    }
    
    /**
     * Search clients via AJAX
     * 
     * @return string JSON response with search results
     */
    public function search()
    {
        // Debug log
        error_log('Search endpoint called');
        
        // Require authentication
        $this->requireAuth();
        
        // Set proper headers for AJAX response
        header('Content-Type: application/json');
        
        try {
            // Get search term
            $search = $this->request->get('search');
            error_log('Search term: ' . $search);
            
            // Get clients matching search term
            $clients = $this->clientService->searchClients($search);
            error_log('Found ' . count($clients) . ' clients');
            
            // Convert client objects to arrays for JSON serialization
            $clientsArray = [];
            foreach ($clients as $client) {
                $clientsArray[] = [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'address' => $client->address,
                    'notes' => $client->notes
                ];
            }
            
            // Create response array
            $response = [
                'success' => true,
                'clients' => $clientsArray
            ];
            
            // Debug log
            error_log('Response: ' . json_encode($response));
            
            // Return JSON response
            echo json_encode($response);
        } catch (\Exception $e) {
            // Log the error
            error_log('Search error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            // Return error response
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
    
    public function show($id)
    {
        // Require authentication
        $this->requireAuth();
        
        try {
            // Get client
            $client = $this->clientService->getClientById($id);
            
            return $this->render('clients/show', [
                'title' => 'Client Details',
                'client' => $client
            ]);
        } catch (\Exception $e) {
            flash('error', $e->getMessage());
            return $this->redirect(url('clients'));
        }
    }
}
