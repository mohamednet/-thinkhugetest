<?php
$title = 'Add Transaction';
ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <h1>Add Transaction</h1>
        <h5 class="text-muted">Client: <?= htmlspecialchars($client->name) ?></h5>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= url('clients/' . $client->id . '/transactions/store') ?>" method="POST">
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
                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" value="<?= htmlspecialchars(old('amount', '')) ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="date" class="form-label">Date *</label>
                <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars(old('date', date('Y-m-d'))) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars(old('description', '')) ?></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="<?= url('clients/' . $client->id . '/transactions') ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Transaction</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_ROOT . '/app/views/layouts/main.php';
?>
