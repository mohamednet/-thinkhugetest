<?php
/**
 * Web routes for the application
 */

use App\Core\Application;

$router = Application::getInstance()->getRouter();

// Home routes
$router->get('/', 'HomeController@index');

// Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Client routes
$router->get('/clients', 'ClientController@index');
$router->post('/clients', 'ClientController@store');

// Client search route - must come before the {id} route
$router->get('/clients/search', 'ClientController@search');

// Client detail routes
$router->get('/clients/{id}', 'ClientController@show');
$router->post('/clients/{id}', 'ClientController@update');
$router->post('/clients/{id}/delete', 'ClientController@delete');

// Transaction routes
$router->get('/clients/{id}/transactions', 'TransactionController@index');
$router->post('/clients/{id}/transactions/store', 'TransactionController@store');
$router->post('/transactions/{id}/update', 'TransactionController@update');
$router->post('/transactions/{id}/delete', 'TransactionController@delete');

// Report routes
$router->get('/reports', 'ReportController@index');
$router->post('/reports/generate', 'ReportController@generate');

// API routes
$router->get('/api/clients/{id}/transactions', 'ApiController@getClientTransactions');

// 404 handler
$router->notFound(function() {
    // Set the HTTP status code to 404
    http_response_code(404);
    
    // Render the 404 view with the layout
    $title = 'Page Not Found';
    ob_start();
    include APP_ROOT . '/App/Views/errors/404.php';
    $content = ob_get_clean();
    
    // Output the content
    echo $content;
    exit;
});
