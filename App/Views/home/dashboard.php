<?php
$title = 'Dashboard';
ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <h1>Dashboard</h1>
        <p class="text-muted">Welcome back, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator') ?>!</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Clients</h5>
                <h2><?= $totalClients ?></h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?= url('clients') ?>">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Income</h5>
                <h2>$<?= number_format($totalIncome, 2) ?></h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?= url('reports') ?>">View Reports</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Expenses</h5>
                <h2>$<?= number_format($totalExpenses, 2) ?></h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?= url('reports') ?>">View Reports</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Clients</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentClients)): ?>
                    <p class="text-muted">No clients have been added yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentClients as $client): ?>
                                <tr>
                                    <td><?= htmlspecialchars($client->name) ?></td>
                                    <td><?= htmlspecialchars($client->email ?? 'N/A') ?></td>
                                    <td>
                                        <a href="<?= url('clients/' . $client->id) ?>" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="<?= url('clients') ?>" class="btn btn-sm btn-outline-primary">View All Clients</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Transactions</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentTransactions)): ?>
                    <p class="text-muted">No transactions have been recorded yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentTransactions as $transaction): ?>
                                <tr>
                                    <td><?= $transaction->getFormattedDate() ?></td>
                                    <td><?= htmlspecialchars($transaction->client_name ?? 'Unknown') ?></td>
                                    <td>
                                        <span class="badge <?= $transaction->isIncome() ? 'bg-success' : 'bg-danger' ?>">
                                            <?= ucfirst($transaction->type) ?>
                                        </span>
                                    </td>
                                    <td class="<?= $transaction->isIncome() ? 'text-success' : 'text-danger' ?>">
                                        <?= $transaction->getFormattedAmount() ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="<?= url('reports') ?>" class="btn btn-sm btn-outline-primary">View All Transactions</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_ROOT . '/App/Views/layouts/main.php';
?>
