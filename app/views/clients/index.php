<?php
$title = 'Clients';
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Clients</h1>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClientModal">
            Add New Client
        </button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?= url('clients') ?>" method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" class="form-control" id="search" name="search" placeholder="Search clients..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($clients)): ?>
    <div class="alert alert-info">
        <?= $search ? 'No clients found matching your search.' : 'No clients have been added yet.' ?>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client->name) ?></td>
                    <td><?= htmlspecialchars($client->email ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($client->phone ?? 'N/A') ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-info view-client" 
                                data-bs-toggle="modal" 
                                data-bs-target="#viewClientModal" 
                                data-id="<?= $client->id ?>"
                                data-name="<?= htmlspecialchars($client->name) ?>"
                                data-email="<?= htmlspecialchars($client->email ?? '') ?>"
                                data-phone="<?= htmlspecialchars($client->phone ?? '') ?>"
                                data-address="<?= htmlspecialchars($client->address ?? '') ?>"
                                data-notes="<?= htmlspecialchars($client->notes ?? '') ?>"
                                data-created="<?= htmlspecialchars($client->created_at ?? '') ?>"
                                data-updated="<?= htmlspecialchars($client->updated_at ?? '') ?>">
                                View
                            </button>
                            <button type="button" class="btn btn-sm btn-warning edit-client" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editClientModal" 
                                data-id="<?= $client->id ?>"
                                data-name="<?= htmlspecialchars($client->name) ?>"
                                data-email="<?= htmlspecialchars($client->email ?? '') ?>"
                                data-phone="<?= htmlspecialchars($client->phone ?? '') ?>"
                                data-address="<?= htmlspecialchars($client->address ?? '') ?>"
                                data-notes="<?= htmlspecialchars($client->notes ?? '') ?>">
                                Edit
                            </button>
                            <a href="<?= url('clients/' . $client->id . '/transactions') ?>" class="btn btn-sm btn-success">Transactions</a>
                            <button type="button" class="btn btn-sm btn-danger delete-client" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteClientModal" 
                                data-id="<?= $client->id ?>"
                                data-name="<?= htmlspecialchars($client->name) ?>">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Create Client Modal -->
<div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createClientModalLabel">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createClientForm" action="<?= url('clients') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Client</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel">Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editClientForm" action="" method="post">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address</label>
                        <textarea class="form-control" id="edit_address" name="address" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Client</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Client Modal -->
<div class="modal fade" id="viewClientModal" tabindex="-1" aria-labelledby="viewClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewClientModalLabel">Client Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> <span id="view_name"></span></p>
                        <p><strong>Email:</strong> <span id="view_email"></span></p>
                        <p><strong>Phone:</strong> <span id="view_phone"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Created:</strong> <span id="view_created"></span></p>
                        <p><strong>Updated:</strong> <span id="view_updated"></span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p><strong>Address:</strong></p>
                        <p id="view_address" class="border p-2 bg-light"></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p><strong>Notes:</strong></p>
                        <p id="view_notes" class="border p-2 bg-light"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="view_transactions_link" href="" class="btn btn-primary">View Transactions</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Client Modal -->
<div class="modal fade" id="deleteClientModal" tabindex="-1" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteClientModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete client: <strong id="delete_client_name"></strong>?</p>
                <p class="text-danger">This will also delete all associated transactions and cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteClientForm" action="" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Client</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit Client Modal
        const editButtons = document.querySelectorAll('.edit-client');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const clientId = this.getAttribute('data-id');
                const form = document.getElementById('editClientForm');
                form.action = `/testv1thinkhug/clients/${clientId}`;
                
                // Populate form with data attributes
                document.getElementById('edit_name').value = this.getAttribute('data-name');
                document.getElementById('edit_email').value = this.getAttribute('data-email');
                document.getElementById('edit_phone').value = this.getAttribute('data-phone');
                document.getElementById('edit_address').value = this.getAttribute('data-address');
                document.getElementById('edit_notes').value = this.getAttribute('data-notes');
            });
        });
        
        // View Client Modal
        const viewButtons = document.querySelectorAll('.view-client');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const clientId = this.getAttribute('data-id');
                
                // Set the transactions link
                document.getElementById('view_transactions_link').href = `/testv1thinkhug/clients/${clientId}/transactions`;
                
                // Populate modal with data attributes
                document.getElementById('view_name').textContent = this.getAttribute('data-name');
                document.getElementById('view_email').textContent = this.getAttribute('data-email') || 'N/A';
                document.getElementById('view_phone').textContent = this.getAttribute('data-phone') || 'N/A';
                document.getElementById('view_address').textContent = this.getAttribute('data-address') || 'N/A';
                document.getElementById('view_notes').textContent = this.getAttribute('data-notes') || 'N/A';
                document.getElementById('view_created').textContent = this.getAttribute('data-created');
                document.getElementById('view_updated').textContent = this.getAttribute('data-updated');
            });
        });
        
        // Delete Client Modal
        const deleteButtons = document.querySelectorAll('.delete-client');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const clientId = this.getAttribute('data-id');
                const clientName = this.getAttribute('data-name');
                
                document.getElementById('delete_client_name').textContent = clientName;
                document.getElementById('deleteClientForm').action = `/testv1thinkhug/clients/${clientId}`;
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
