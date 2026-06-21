<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-2 px-sm-0">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">

            <div class="modal-header border-bottom-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bolder text-dark d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                        <i class="ti ti-key fs-5"></i>
                    </div>
                    Generate Admin Invite Key
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 py-3 text-center">
                <p class="text-muted mb-4 text-start" style="font-size: 0.9rem;">
                    Instead of manually creating an account, generate a secure, one-time key. Send this key to the new administrator so they can register themselves securely.
                </p>

                <div id="keyAlertContainer"></div>

                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden mb-4" style="border: 1px solid #eef2f6;">
                    <input type="text" class="form-control bg-light border-0 fw-bold font-monospace text-center text-primary fs-5 shadow-none" id="modalGeneratedKey" placeholder="Click generate below..." readonly>
                    <button class="btn border border-start-0 bg-white px-3 shadow-none" type="button" id="modalBtnCopyKey" title="Copy to clipboard">
                        <i class="ti ti-copy fs-5 text-muted"></i>
                    </button>
                </div>

                <button class="btn btn-primary fw-bold px-4 py-3 w-100 shadow-sm" style="border-radius: 12px;" type="button" id="modalBtnGenerateKey">
                    <i class="ti ti-wand me-1"></i> Generate New Key
                </button>
            </div>

            <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-end">
                <button type="button" class="btn btn-light fw-bold px-4 py-2 m-0" style="border-radius: 10px; color: #5a6a85;" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnGenerate = document.getElementById('modalBtnGenerateKey');
        const inputKey = document.getElementById('modalGeneratedKey');
        const btnCopy = document.getElementById('modalBtnCopyKey');
        const alertContainer = document.getElementById('keyAlertContainer');

        // Helper function to show beautiful in-modal alerts
        function showModalAlert(message, type) {
            alertContainer.innerHTML = `
            <div class="alert alert-${type} text-start small p-2 mb-3 fw-medium d-flex align-items-center">
                <i class="ti ti-info-circle me-2 fs-5"></i> ${message}
            </div>
        `;
        }

        if (btnGenerate) {
            btnGenerate.addEventListener('click', function() {

                // Clear previous alerts
                alertContainer.innerHTML = '';

                // Show loading state on the button
                const originalText = btnGenerate.innerHTML;
                btnGenerate.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Generating...';
                btnGenerate.disabled = true;

                // Fetch the key from the server
                fetch('<?= base_url('admin/users/generate-admin-key') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            inputKey.value = data.key;
                            showModalAlert('Key generated successfully! Copy and send it to the new admin.', 'success');
                        } else {
                            showModalAlert('Error: ' + data.message, 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showModalAlert('Something went wrong. Please check your connection.', 'danger');
                    })
                    .finally(() => {
                        // Restore the button state
                        btnGenerate.innerHTML = originalText;
                        btnGenerate.disabled = false;
                    });
            });
        }

        if (btnCopy) {
            btnCopy.addEventListener('click', function() {
                if (!inputKey.value) return;

                // Copy the text
                inputKey.select();
                document.execCommand('copy');

                // Visual feedback
                const originalIcon = this.innerHTML;
                this.innerHTML = '<i class="ti ti-check fs-5 text-success"></i>';
                setTimeout(() => {
                    this.innerHTML = originalIcon;
                }, 2000);
            });
        }

        // Clear the input and alerts when the modal is closed so it's fresh next time
        const adminModalEl = document.getElementById('addAdminModal');
        if(adminModalEl) {
            adminModalEl.addEventListener('hidden.bs.modal', function () {
                inputKey.value = '';
                alertContainer.innerHTML = '';
            });
        }
    });
</script>