<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">

            <div class="mb-3">
                <i class="ti ti-alert-triangle text-danger" style="font-size: 4.5rem; animation: pulse 2s infinite;"></i>
            </div>

            <h4 class="fw-bold text-dark mb-2">Are you sure?</h4>
            <p class="text-muted mb-4" id="deleteModalMessage">Do you really want to delete this record? This action cannot be undone.</p>

            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary px-4 fw-semibold" data-bs-dismiss="modal">Cancel</button>

                <a href="#" id="deleteModalConfirmBtn" class="btn btn-danger px-4 fw-semibold shadow-sm">
                    Yes, Delete
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteConfirmModal');

        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                const button = event.relatedTarget;

                // Extract info from data-bs-* attributes
                const targetUrl = button.getAttribute('data-bs-url');
                const targetMessage = button.getAttribute('data-bs-message');

                // Update the modal's confirm button link
                document.getElementById('deleteModalConfirmBtn').setAttribute('href', targetUrl);

                // Update the modal's text message (if provided)
                if (targetMessage) {
                    document.getElementById('deleteModalMessage').textContent = targetMessage;
                }
            });
        }
    });
</script>