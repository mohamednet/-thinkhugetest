<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel">Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editClientForm" action="" method="POST">
                <input type="hidden" name="csrf_token" id="edit_csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
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
                        <textarea class="form-control" id="edit_address" name="address" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up the edit client modal
    const editClientModal = document.getElementById('editClientModal');
    if (editClientModal) {
        editClientModal.addEventListener('show.bs.modal', function(event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            
            // Extract client info from data attributes
            const clientId = button.getAttribute('data-client-id');
            const clientName = button.getAttribute('data-client-name');
            const clientEmail = button.getAttribute('data-client-email');
            const clientPhone = button.getAttribute('data-client-phone');
            const clientAddress = button.getAttribute('data-client-address');
            const clientNotes = button.getAttribute('data-client-notes');
            
            // Update the modal's form action
            const form = editClientModal.querySelector('#editClientForm');
            form.action = '<?= url('clients/') ?>' + clientId;
            
            // Update the modal's content
            editClientModal.querySelector('#edit_name').value = clientName;
            editClientModal.querySelector('#edit_email').value = clientEmail;
            editClientModal.querySelector('#edit_phone').value = clientPhone;
            editClientModal.querySelector('#edit_address').value = clientAddress;
            editClientModal.querySelector('#edit_notes').value = clientNotes;
        });
    }
});
</script>
