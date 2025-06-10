<?php
/**
 * Test script for the client search functionality
 */

// Define APP_ROOT constant
define('APP_ROOT', __DIR__);

// Load environment variables
require_once APP_ROOT . '/config/env.php';

// Load bootstrap file which includes autoloader
require_once APP_ROOT . '/app/core/bootstrap.php';

// Initialize the database connection
$db = new \App\Core\Database();

// Create repository and service
$clientRepository = new \App\Repositories\ClientRepository($db);
$clientService = new \App\Services\ClientService($clientRepository);

// Test search with a simple term
$searchTerm = 'a'; // This should match at least some clients
$clients = $clientService->searchClients($searchTerm);

// Output results
echo "<h1>Search Test Results</h1>";
echo "<p>Search term: '{$searchTerm}'</p>";
echo "<p>Found " . count($clients) . " clients</p>";

// Display client data
if (count($clients) > 0) {
    echo "<h2>Client Data:</h2>";
    echo "<pre>";
    foreach ($clients as $client) {
        // Convert to array for display
        $clientArray = [
            'id' => $client->id,
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone,
            'address' => $client->address,
            'notes' => $client->notes
        ];
        
        print_r($clientArray);
    }
    echo "</pre>";
    
    // Test JSON encoding
    echo "<h2>JSON Encoding Test:</h2>";
    
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
    
    // Test JSON encoding
    $json = json_encode($response);
    $jsonError = json_last_error();
    
    echo "<p>JSON encoding result: " . ($jsonError === JSON_ERROR_NONE ? "SUCCESS" : "FAILED") . "</p>";
    
    if ($jsonError !== JSON_ERROR_NONE) {
        echo "<p>JSON error: " . json_last_error_msg() . "</p>";
    } else {
        echo "<p>JSON output (first 500 chars):</p>";
        echo "<pre>" . htmlspecialchars(substr($json, 0, 500)) . "...</pre>";
    }
}
