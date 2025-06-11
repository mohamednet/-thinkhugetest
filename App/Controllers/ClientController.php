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
        $this->requireAuth();
        
        $search = $this->request->get('search');
        
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
        $this->requireAuth();
        
        $token = $this->request->get('csrf_token');
        if (!verify_csrf_token($token)) {
            flash('error', 'Invalid CSRF token');
            return $this->redirect(url('clients'));
        }
        
        $data = [
            'name' => $this->request->get('name'),
            'email' => $this->request->get('email'),
            'phone' => $this->request->get('phone'),
            'address' => $this->request->get('address'),
            'notes' => $this->request->get('notes')
        ];
        
        $_SESSION['old'] = $data;
        
        try {
            $client = $this->clientService->createClient($data);
            
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
        $this->requireAuth();
        
        $token = $this->request->get('csrf_token');
        if (!verify_csrf_token($token)) {
            flash('error', 'Invalid CSRF token');
            return $this->redirect(url('clients'));
        }
        
        $data = [
            'id' => $id,
            'name' => $this->request->get('name'),
            'email' => $this->request->get('email'),
            'phone' => $this->request->get('phone'),
            'address' => $this->request->get('address'),
            'notes' => $this->request->get('notes')
        ];
        
        try {
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
        $this->requireAuth();
        
        $token = $this->request->get('csrf_token');
        if (!verify_csrf_token($token)) {
            flash('error', 'Invalid CSRF token');
            return $this->redirect(url('clients'));
        }
        
        try {
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

        
        $this->requireAuth();
        
        header('Content-Type: application/json');
        
        try {
            $search = $this->request->get('search');
            
            $clients = $this->clientService->searchClients($search);
            
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
            
            $response = [
                'success' => true,
                'clients' => $clientsArray
            ];
            

            
            echo json_encode($response);
        } catch (\Exception $e) {

            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
    
    public function show($id)
    {
        $this->requireAuth();
        
        try {
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
