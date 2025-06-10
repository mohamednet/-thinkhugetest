<?php
$title = 'Dashboard';
ob_start();
?>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Dashboard</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Clients</h5>
                <p class="card-text">Manage your clients and their information.</p>
                <a href="/clients" class="btn btn-primary">View Clients</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Transactions</h5>
                <p class="card-text">Add and manage client transactions.</p>
                <a href="/clients" class="btn btn-primary">Select Client</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Reports</h5>
                <p class="card-text">View financial reports and statistics.</p>
                <a href="/reports" class="btn btn-primary">View Reports</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_ROOT . '/app/views/layouts/main.php';
?>
