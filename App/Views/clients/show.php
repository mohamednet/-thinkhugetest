<?php
$title = 'Client Details';
ob_start();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Client Details</h4>
                    <a href="<?= url('clients') ?>" class="btn btn-secondary btn-sm">Back to Clients</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Personal Information</h5>
                            <table class="table">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td><?= htmlspecialchars($client->name) ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?= htmlspecialchars($client->email ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?= htmlspecialchars($client->phone ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td><?= nl2br(htmlspecialchars($client->address ?? 'N/A')) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Additional Information</h5>
                            <table class="table">
                                <tr>
                                    <th width="30%">Client ID:</th>
                                    <td><?= $client->id ?></td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?= date('F j, Y', strtotime($client->created_at)) ?></td>
                                </tr>
                                <tr>
                                    <th>Notes:</th>
                                    <td><?= nl2br(htmlspecialchars($client->notes ?? 'No notes available')) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Actions</h5>
                        <a href="<?= url('clients/' . $client->id . '/transactions') ?>" class="btn btn-primary">View Transactions</a>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editClientModal" 
                            data-client-id="<?= $client->id ?>" 
                            data-client-name="<?= htmlspecialchars($client->name) ?>" 
                            data-client-email="<?= htmlspecialchars($client->email ?? '') ?>" 
                            data-client-phone="<?= htmlspecialchars($client->phone ?? '') ?>" 
                            data-client-address="<?= htmlspecialchars($client->address ?? '') ?>" 
                            data-client-notes="<?= htmlspecialchars($client->notes ?? '') ?>">
                            Edit Client
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteClientModal" data-client-id="<?= $client->id ?>" data-client-name="<?= htmlspecialchars($client->name) ?>">
                            Delete Client
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include the edit and delete modals -->
<?php include APP_ROOT . '/App/Views/clients/partials/edit_modal.php'; ?>
<?php include APP_ROOT . '/App/Views/clients/partials/delete_modal.php'; ?>

<?php
$content = ob_get_clean();
include APP_ROOT . '/App/Views/layouts/main.php';
?>
