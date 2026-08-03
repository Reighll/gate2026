<div class="modal fade" id="addPassModal" tabindex="-1" aria-labelledby="addPassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bolder d-flex align-items-center mb-0 text-truncate pe-2" style="color: #fff !important;">
                    Register New Visitor Pass
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="addPassForm" action="<?= base_url('admin/visitors/add-tag') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="modal-body p-5 text-center">

                    <div class="mb-4">
                        <i class="ti ti-wifi text-primary" style="font-size: 4rem; animation: pulse 1.5s infinite;"></i>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">Ready to Scan</h5>
                    <p class="text-muted mb-4">Tap the new RFID card on the scanner.</p>

                    <input type="text" id="rfid" name="rfid" required autocomplete="off" style="position: absolute; opacity: 0; height: 1px; width: 1px; bottom: 0;">

                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center justify-content-center mb-0" role="alert">
                        <i class="ti ti-info-circle me-2"></i>
                        <small>The name (Visitor Pass 1, etc.) will be assigned automatically.</small>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addPassModalElement = document.getElementById('addPassModal');
        const rfidInput = document.getElementById('rfid');
        const addPassForm = document.getElementById('addPassForm');

        let scanInterval = null;

        if (addPassModalElement && rfidInput) {

            // 1. When the modal opens, START polling the server
            addPassModalElement.addEventListener('shown.bs.modal', function () {

                // Check the server every 1 second (1000ms)
                scanInterval = setInterval(() => {

                    // Pointing to your existing check-latest-scan route
                    fetch('<?= base_url('admin/items/check-latest-scan') ?>', {
                        headers: { "X-Requested-With": "XMLHttpRequest" }
                    })
                        .then(response => response.json())
                        .then(data => {
                            // If the ESP32 successfully updated the server with a scan
                            if (data.status === 'success' && data.epc) {
                                console.log("ESP32 Scan Detected: " + data.epc);

                                // Stop polling so we don't spam the server
                                clearInterval(scanInterval);

                                // Visual UI update so the admin knows it worked
                                const icon = addPassModalElement.querySelector('.ti-wifi');
                                if(icon) {
                                    icon.style.animation = 'none';
                                    icon.className = 'ti ti-check text-success display-3';
                                }

                                // Drop the EPC into the hidden input and auto-submit!
                                rfidInput.value = data.epc;
                                addPassForm.submit();
                            }
                        })
                        .catch(err => {
                            // Fail silently (server busy, etc.)
                        });

                }, 1000);
            });

            // 2. If the admin closes the modal or clicks Cancel, STOP polling
            addPassModalElement.addEventListener('hidden.bs.modal', function () {
                if (scanInterval) {
                    clearInterval(scanInterval);
                }
            });
        }
    });
</script>