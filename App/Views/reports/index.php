<?php
$title = 'Financial Reports';
ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <h1>Financial Reports</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= url('reports/generate') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="client_id" class="form-label">Client</label>
                    <select class="form-select" id="client_id" name="client_id">
                        <option value="">All Clients</option>
                        <?php foreach ($clients as $client): ?>
                        <option value="<?= $client->id ?>"><?= htmlspecialchars($client->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="transaction_type" class="form-label">Report Type</label>
                    <select class="form-select" id="transaction_type" name="transaction_type">
                        <option value="all">All Transactions</option>
                        <option value="income">Income Only</option>
                        <option value="expense">Expenses Only</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= date('Y-m-d') ?>">
                </div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_ROOT . '/App/Views/layouts/main.php';
?>
