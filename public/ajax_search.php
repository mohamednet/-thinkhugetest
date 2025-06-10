<?php
/**
 * Dedicated AJAX search endpoint for clients
 */

// Define APP_ROOT constant
define('APP_ROOT', dirname(__DIR__));

// Load environment variables
require_once APP_ROOT . '/config/env.php';

// Load bootstrap file which includes autoloader
require_once APP_ROOT . '/app/core/bootstrap.php';

// Initialize the database connection
$db = new \App\Core\Database();

// Create repository and service
$clientRepository = new \App\Repositories\ClientRepository($db);
$clientService = new \App\Services\ClientService($clientRepository);

// Set headers for AJAX response
header('Content-Type: application/json');

try {
    // Check if user is authenticated
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Authentication required'
        ]);
        exit;
    }
    
    // Get search term
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    // Log search term for debugging
    error_log('AJAX Search: Searching for "' . $search . '"');
    
    // Get clients matching search term
    $clients = $clientService->searchClients($search);
    error_log('AJAX Search: Found ' . count($clients) . ' clients');
    
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
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'clients' => $clientsArray
    ]);
} catch (Exception $e) {
    // Log the error
    error_log('AJAX Search Error: ' . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
