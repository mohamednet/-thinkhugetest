<!-- Delete Client Modal -->
<div class="modal fade" id="deleteClientModal" tabindex="-1" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteClientModalLabel">Delete Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteClientForm" action="" method="POST">
                <input type="hidden" name="csrf_token" id="delete_csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="delete_client_name"></strong>?</p>
                    <p class="text-danger">This action cannot be undone. All client data and transactions will be permanently deleted.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Client</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up the delete client modal
    const deleteClientModal = document.getElementById('deleteClientModal');
    if (deleteClientModal) {
        deleteClientModal.addEventListener('show.bs.modal', function(event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            
            // Extract client info from data attributes
            const clientId = button.getAttribute('data-client-id');
            const clientName = button.getAttribute('data-client-name');
            
            // Update the modal's form action
            const form = deleteClientModal.querySelector('#deleteClientForm');
            form.action = '<?= url('clients/') ?>' + clientId + '/delete';
            
            // Update the modal's content
            deleteClientModal.querySelector('#delete_client_name').textContent = clientName;
        });
    }
});
</script>
