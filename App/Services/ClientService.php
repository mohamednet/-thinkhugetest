<?php
namespace App\Services;

use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Models\Client;

class ClientService
{
    private $clientRepository;
    
    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    
    public function getAllClients()
    {
        return $this->clientRepository->findAll();
    }
    
    public function getTotalClients()
    {
        return $this->clientRepository->count();
    }
    
    public function getRecentClients($limit = 5)
    {
        return $this->clientRepository->findRecent($limit);
    }
    
    public function getClientById($id)
    {
        return $this->clientRepository->findById($id);
    }
    
    public function searchClients($term)
    {
        return $this->clientRepository->findByName($term);
    }
    
    public function createClient(array $data)
    {
        // Validate data
        $this->validateClientData($data);
        
        // Create client
        return $this->clientRepository->create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function updateClient($id, array $data)
    {
        // Validate data
        $this->validateClientData($data);
        
        // Check if client exists
        $client = $this->clientRepository->findById($id);
        if (!$client) {
            throw new \Exception("Client not found");
        }
        
        // Update client
        return $this->clientRepository->update($id, [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'notes' => $data['notes'] ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function deleteClient($id)
    {
        // Check if client exists
        $client = $this->clientRepository->findById($id);
        if (!$client) {
            throw new \Exception("Client not found");
        }
        
        // Delete client
        return $this->clientRepository->delete($id);
    }
    
    private function validateClientData(array $data)
    {
        if (empty($data['name'])) {
            throw new \Exception("Name is required");
        }
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format");
        }
        
        // Check for duplicate email if provided
        if (!empty($data['email'])) {
            $existingClient = $this->clientRepository->findByEmail($data['email']);
            if ($existingClient && (!isset($data['id']) || $existingClient->id != $data['id'])) {
                throw new \Exception("Email already in use");
            }
        }
    }
}
