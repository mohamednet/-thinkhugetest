<?php
$title = 'Report Results';
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Report Results</h1>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= url('reports') ?>" class="btn btn-secondary">Back to Reports</a>
        <button class="btn btn-primary" onclick="window.print()">Print Report</button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Report Parameters</h5>
                <table class="table table-sm">
                    <tr>
                        <th>Client:</th>
                        <td><?= $client ? htmlspecialchars($client->name) : 'All Clients' ?></td>
                    </tr>
                    <?php if ($startDate && $endDate): ?>
                    <tr>
                        <th>Date Range:</th>
                        <td><?= date('M j, Y', strtotime($startDate)) ?> to <?= date('M j, Y', strtotime($endDate)) ?></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <th>Date Range:</th>
                        <td>All Time</td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Report Type:</th>
                        <td><?= ucfirst($reportType) ?></td>
                    </tr>
                    <tr>
                        <th>Generated On:</th>
                        <td><?= date('M j, Y H:i') ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Total Balance</h5>
                        <h2 class="<?= $balance >= 0 ? 'text-success' : 'text-danger' ?>">
                            <?= $balance >= 0 ? '+' : '' ?><?= number_format($balance, 2) ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($reportType === 'grouped' && !$client): ?>
    <!-- Grouped by client report -->
    <?php if (empty($groupedTransactions)): ?>
        <div class="alert alert-info">No transactions found for the selected criteria.</div>
    <?php else: ?>
        <?php foreach ($groupedTransactions as $clientId => $data): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= htmlspecialchars($data['client']->name) ?></h5>
                        <span class="badge <?= $data['balance'] >= 0 ? 'bg-success' : 'bg-danger' ?>">
                            Balance: <?= $data['balance'] >= 0 ? '+' : '' ?><?= number_format($data['balance'], 2) ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($data['transactions'])): ?>
                        <p class="text-muted">No transactions for this client.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['transactions'] as $transaction): ?>
                                    <tr>
                                        <td><?= $transaction->getFormattedDate() ?></td>
                                        <td>
                                            <span class="badge <?= $transaction->isIncome() ? 'bg-success' : 'bg-danger' ?>">
                                                <?= ucfirst($transaction->type) ?>
                                            </span>
                                        </td>
                                        <td class="<?= $transaction->isIncome() ? 'text-success' : 'text-danger' ?>">
                                            <?= $transaction->getFormattedAmount() ?>
                                        </td>
                                        <td><?= htmlspecialchars($transaction->description ?? 'N/A') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else: ?>
    <!-- Detailed transactions report -->
    <?php if (empty($transactions)): ?>
        <div class="alert alert-info">No transactions found for the selected criteria.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <?php if (!$client): ?>
                        <th>Client</th>
                        <?php endif; ?>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= $transaction->getFormattedDate() ?></td>
                        <?php if (!$client): ?>
                        <td>
                            <?= htmlspecialchars($transaction->client_name ?? 'Unknown') ?>
                        </td>
                        <?php endif; ?>
                        <td>
                            <span class="badge <?= $transaction->isIncome() ? 'bg-success' : 'bg-danger' ?>">
                                <?= ucfirst($transaction->type) ?>
                            </span>
                        </td>
                        <td class="<?= $transaction->isIncome() ? 'text-success' : 'text-danger' ?>">
                            <?= $transaction->getFormattedAmount() ?>
                        </td>
                        <td><?= htmlspecialchars($transaction->description ?? 'N/A') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>

<style type="text/css" media="print">
    @media print {
        .btn, .navbar, footer {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
        }
        .card-header {
            background-color: #f8f9fa !important;
        }
        body {
            padding: 0 !important;
            margin: 0 !important;
        }
        .container {
            max-width: 100% !important;
            width: 100% !important;
        }
    }
</style>

<?php
$content = ob_get_clean();
include APP_ROOT . '/app/views/layouts/main.php';
?>
