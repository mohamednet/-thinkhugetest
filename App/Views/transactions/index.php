<?php
$title = 'Transactions for ' . $client->name;
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Transactions</h1>
        <h5 class="text-muted">Client: <?= htmlspecialchars($client->name) ?></h5>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            Add Transaction
        </button>
        <a href="<?= url('clients/' . $client->id) ?>" class="btn btn-secondary">Back to Client</a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Current Balance</h5>
                <h2 class="<?= $balance >= 0 ? 'text-success' : 'text-danger' ?>">
                    <?= $balance >= 0 ? '+' : '' ?><?= number_format($balance, 2) ?>
                </h2>
            </div>
        </div>
    </div>
</div>

<?php if (empty($transactions)): ?>
    <div class="alert alert-info">
        No transactions have been recorded for this client yet.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
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
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewTransactionModal<?= $transaction->id ?>">View</button>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTransactionModal<?= $transaction->id ?>">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTransactionModal<?= $transaction->id ?>">Delete</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= url('clients/' . $client->id . '/transactions/store') ?>" method="POST">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Transaction Type *</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="income" <?= old('type') === 'income' ? 'selected' : '' ?>>Income</option>
                            <option value="expense" <?= old('type') === 'expense' ? 'selected' : '' ?>>Expense</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" max="99999999.99" value="<?= htmlspecialchars(old('amount', '')) ?>" required>
                        </div>
                        <div class="form-text">Maximum amount: $99,999,999.99</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date" class="form-label">Date *</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars(old('date', date('Y-m-d'))) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars(old('description', '')) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transaction Modals for each transaction -->
<?php foreach ($transactions as $transaction): ?>
    <!-- View Transaction Modal -->
    <div class="modal fade" id="viewTransactionModal<?= $transaction->id ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">Transaction Type</h6>
                        <p>
                            <span class="badge <?= $transaction->isIncome() ? 'bg-success' : 'bg-danger' ?>">
                                <?= ucfirst($transaction->type) ?>
                            </span>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Amount</h6>
                        <p class="<?= $transaction->isIncome() ? 'text-success' : 'text-danger' ?> fs-4">
                            <?= $transaction->getFormattedAmount() ?>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Date</h6>
                        <p><?= $transaction->getFormattedDate() ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Description</h6>
                        <p><?= htmlspecialchars($transaction->description ?? 'N/A') ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Created</h6>
                        <p><?= date('M j, Y g:i A', strtotime($transaction->created_at)) ?></p>
                    </div>
                    
                    <?php if ($transaction->updated_at != $transaction->created_at): ?>
                    <div class="mb-3">
                        <h6 class="fw-bold">Last Updated</h6>
                        <p><?= date('M j, Y g:i A', strtotime($transaction->updated_at)) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Transaction Modal -->
    <div class="modal fade" id="editTransactionModal<?= $transaction->id ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= url('transactions/' . $transaction->id . '/update') ?>" method="POST">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="type<?= $transaction->id ?>" class="form-label">Transaction Type *</label>
                            <select class="form-select" id="type<?= $transaction->id ?>" name="type" required>
                                <option value="income" <?= $transaction->type === 'income' ? 'selected' : '' ?>>Income</option>
                                <option value="expense" <?= $transaction->type === 'expense' ? 'selected' : '' ?>>Expense</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="amount<?= $transaction->id ?>" class="form-label">Amount *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="amount<?= $transaction->id ?>" name="amount" step="0.01" min="0.01" max="99999999.99" value="<?= htmlspecialchars($transaction->amount) ?>" required>
                            </div>
                            <div class="form-text">Maximum amount: $99,999,999.99</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date<?= $transaction->id ?>" class="form-label">Date *</label>
                            <input type="date" class="form-control" id="date<?= $transaction->id ?>" name="date" value="<?= htmlspecialchars($transaction->date) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description<?= $transaction->id ?>" class="form-label">Description</label>
                            <textarea class="form-control" id="description<?= $transaction->id ?>" name="description" rows="3"><?= htmlspecialchars($transaction->description ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Transaction Modal -->
    <div class="modal fade" id="deleteTransactionModal<?= $transaction->id ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this transaction?</p>
                    <p><strong>Date:</strong> <?= $transaction->getFormattedDate() ?></p>
                    <p><strong>Type:</strong> <?= ucfirst($transaction->type) ?></p>
                    <p><strong>Amount:</strong> <?= $transaction->getFormattedAmount() ?></p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="<?= url('transactions/' . $transaction->id . '/delete') ?>" method="POST">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
include APP_ROOT . '/App/Views/layouts/main.php';
?>
