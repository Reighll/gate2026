<style>
    /* A quick hover effect for the adaptive cancel button */
    .btn-adaptive-cancel:hover {
        background-color: rgba(150, 160, 175, 0.2) !important;
    }
</style>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-auto" style="max-width: 360px; width: 90%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-body text-center p-4">

                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 80px; height: 80px; background-color: rgba(250, 137, 107, 0.15); border-radius: 50%;">
                    <i class="ti ti-trash text-danger" style="font-size: 38px; color: #fa896b !important;"></i>
                </div>

                <h4 class="fw-bolder mb-2">Are you sure?</h4>
                <p class="text-muted mb-4" id="deleteModalMessage" style="font-size: 0.9rem;">You won't be able to revert this!</p>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <button type="button" class="btn btn-adaptive-cancel fw-semibold py-2 w-100" data-bs-dismiss="modal" style="border-radius: 10px; background-color: rgba(150, 160, 175, 0.1); border: 1px solid rgba(150, 160, 175, 0.2); color: inherit; transition: all 0.2s;">
                        Cancel
                    </button>

                    <a href="#" id="deleteModalConfirmBtn" class="btn btn-danger fw-semibold py-2 w-100 d-flex align-items-center justify-content-center" style="border-radius: 10px; background-color: #fa896b; border: none; box-shadow: 0 4px 10px rgba(250, 137, 107, 0.3); transition: all 0.2s;">
                        Yes, delete it
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteConfirmModal');

        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                // Find the specific button that triggered the modal
                const button = event.relatedTarget;

                // Extract the URL and custom message from the button's data attributes
                const targetUrl = button.getAttribute('data-bs-url');
                const targetMessage = button.getAttribute('data-bs-message');

                // Inject the correct delete URL into our "Yes, delete it" button
                const confirmBtn = document.getElementById('deleteModalConfirmBtn');
                if (targetUrl) {
                    confirmBtn.setAttribute('href', targetUrl);
                }

                // Update the text message if one was provided
                if (targetMessage) {
                    document.getElementById('deleteModalMessage').textContent = targetMessage;
                }
            });
        }
    });
</script>