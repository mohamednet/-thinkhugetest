<?php
// Enable full error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Testing Links</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .route-section { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Route Testing Links</h1>
        
        <div class="route-section">
            <h2>Direct Links</h2>
            <p>These links test direct access to routes:</p>
            <ul class="list-group">
                <li class="list-group-item"><a href="/testv1thinkhug/" target="_blank">Home (Root)</a></li>
                <li class="list-group-item"><a href="/testv1thinkhug/login" target="_blank">Login</a></li>
                <li class="list-group-item"><a href="/testv1thinkhug/clients" target="_blank">Clients</a></li>
                <li class="list-group-item"><a href="/testv1thinkhug/reports" target="_blank">Reports</a></li>
            </ul>
        </div>
        
        <div class="route-section">
            <h2>Subdirectory Links</h2>
            <p>These links test access through the public subdirectory:</p>
            <ul class="list-group">
                <li class="list-group-item"><a href="/testv1thinkhug/public/" target="_blank">Home (through public)</a></li>
                <li class="list-group-item"><a href="/testv1thinkhug/public/login" target="_blank">Login (through public)</a></li>
                <li class="list-group-item"><a href="/testv1thinkhug/public/clients" target="_blank">Clients (through public)</a></li>
            </ul>
        </div>
        
        <div class="route-section">
            <h2>Dynamic Route Links</h2>
            <p>These links test dynamic routes (you may need to adjust IDs):</p>
            <ul class="list-group">
                <li class="list-group-item"><a href="/testv1thinkhug/clients/1/transactions" target="_blank">Client 1 Transactions</a></li>
                <li class="list-group-item"><a href="/testv1thinkhug/clients/1/transactions/create" target="_blank">Create Transaction for Client 1</a></li>
            </ul>
        </div>
        
        <div class="route-section">
            <h2>Server Information</h2>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Server Variables</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>REQUEST_URI:</strong> <?= $_SERVER['REQUEST_URI'] ?></li>
                        <li class="list-group-item"><strong>SCRIPT_NAME:</strong> <?= $_SERVER['SCRIPT_NAME'] ?></li>
                        <li class="list-group-item"><strong>DOCUMENT_ROOT:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?></li>
                        <li class="list-group-item"><strong>PHP_SELF:</strong> <?= $_SERVER['PHP_SELF'] ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
