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
    return view('errors/404');
});
