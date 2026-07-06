<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Remove Item | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <h4 class="fw-semibold mb-3">Item Unregistration</h4>

        <div id="alertContainer"></div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <p class="text-muted mb-4">Select an item to request unregistration (e.g., if lost, sold, or no longer used). An admin must approve this request.</p>

                <div class="skeleton-wrapper">
                    <?php for($i=0; $i<3; $i++): ?>
                        <div class="border rounded p-3 d-flex justify-content-between align-items-center mb-3 bg-light">
                            <div class="w-75">
                                <div class="skeleton skeleton-title w-50 mb-1" style="height: 18px;"></div>
                                <div class="skeleton skeleton-text w-25 mb-0" style="height: 12px;"></div>
                            </div>
                            <div class="skeleton rounded-2" style="width: 90px; height: 30px;"></div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="real-wrapper d-none">
                    <?php
                    $approvedItems = array_filter($items, function($item) {
                        return $item['status'] === 'approved';
                    });
                    ?>

                    <?php if (empty($approvedItems)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="ti ti-device-laptop fs-1 d-block mb-2"></i>
                            No active items available to unregister.
                        </div>
                    <?php else: ?>
                        <div id="approvedItemsContainer">
                            <?php foreach ($approvedItems as $item): ?>

                                <div class="border rounded p-3 d-flex justify-content-between align-items-center mb-3 border-warning bg-light item-card" id="itemCard<?= $item['id'] ?>">
                                    <div>
                                        <h6 class="fw-bold mb-0"><?= esc($item['brand_model']) ?></h6>
                                        <small class="text-muted">SN: <?= esc($item['serial_number']) ?></small>
                                    </div>
                                    <button type="button"
                                            class="btn btn-outline-warning btn-sm fw-bold"
                                            data-bs-toggle="modal"
                                            data-bs-target="#unregisterModal<?= $item['id'] ?>">
                                        Unregister
                                    </button>
                                </div>

                                <div class="modal fade" id="unregisterModal<?= $item['id'] ?>" tabindex="-1" aria-labelledby="unregisterModalLabel<?= $item['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg rounded-4">

                                            <div class="modal-header bg-warning-subtle border-0 rounded-top-4">
                                                <h5 class="modal-title fw-bold text-warning-emphasis" id="unregisterModalLabel<?= $item['id'] ?>">
                                                    <i class="ti ti-alert-triangle me-1"></i> Confirm Unregistration
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-4 text-center">
                                                <div class="mb-3 text-warning">
                                                    <i class="ti ti-device-laptop" style="font-size: 3.5rem;"></i>
                                                </div>
                                                <h5 class="fw-bold mb-2">Are you sure?</h5>
                                                <p class="text-muted mb-0">You are about to request unregistration for:</p>

                                                <p class="fw-bold fs-5 text-dark mt-1 mb-3"><?= esc($item['brand_model']) ?></p>

                                                <div class="alert alert-light border shadow-sm small text-start mb-0 text-dark">
                                                    <i class="ti ti-info-circle text-primary me-1"></i>
                                                    This item will be flagged for Admin review. You will not be able to use it as a valid gate pass until this is resolved.
                                                </div>
                                            </div>

                                            <div class="modal-footer border-0 bg-light rounded-bottom-4 d-flex justify-content-center gap-2 pb-4">
                                                <button type="button" class="btn btn-light border fw-medium px-4" data-bs-dismiss="modal">Cancel</button>

                                                <button type="button"
                                                        class="btn btn-warning fw-bold px-4 shadow-sm"
                                                        onclick="window.processUnregister(this, '<?= base_url('student/items/request-unregister/' . $item['id']) ?>', '<?= $item['id'] ?>')">
                                                    Yes, Request Unregister
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>

                        <div class="text-center text-muted py-4 d-none" id="noItemsMessage">
                            <i class="ti ti-device-laptop fs-1 d-block mb-2"></i>
                            No active items available to unregister.
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <script>
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }

        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // --- NEW: Inline Alert System ---
        function showInlineAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            if (alertContainer) {
                // Pick the right icon based on success/error
                const icon = type === 'success' ? 'ti-check-circle' : 'ti-alert-triangle';

                // Build the HTML matching your dashboard's style
                const alertHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4 d-flex align-items-center" role="alert">
                        <i class="ti ${icon} fs-5 me-2 text-${type}"></i>
                        <span class="text-${type}-emphasis fw-medium">${message}</span>
                        <button type="button" class="btn-close m-0 ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Inject it above the card
                alertContainer.innerHTML = alertHTML;

                // Optional: Auto-hide the alert after 4 seconds so it doesn't sit there forever
                setTimeout(() => {
                    const alertNode = alertContainer.querySelector('.alert');
                    if (alertNode) {
                        const bsAlert = new bootstrap.Alert(alertNode);
                        bsAlert.close();
                    }
                }, 4000);
            }
        }

        // --- Background Processor ---
        window.processUnregister = async function(btn, url, itemId) {
            const originalText = btn.innerHTML;

            // Show loading state
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            btn.classList.add('disabled');
            btn.disabled = true;

            try {
                // Send the background request
                await fetch(url, {
                    method: 'GET',
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });

                // Close the modal cleanly
                const modalEl = document.getElementById('unregisterModal' + itemId);
                if (modalEl) {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalInstance.hide();
                }

                // Clean up grey backdrops
                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';

                    // TRIGGER THE INLINE ALERT HERE!
                    showInlineAlert("Item unregistration requested successfully!");
                }, 300);

                // Animate the item away
                const itemCard = document.getElementById('itemCard' + itemId);
                if (itemCard) {
                    itemCard.style.transition = 'all 0.4s ease';
                    itemCard.style.opacity = '0';
                    itemCard.style.transform = 'scale(0.9)';

                    setTimeout(() => {
                        itemCard.remove();

                        // Show empty state if needed
                        const remainingItems = document.querySelectorAll('.item-card');
                        if(remainingItems.length === 0) {
                            const emptyMsg = document.getElementById('noItemsMessage');
                            if(emptyMsg) emptyMsg.classList.remove('d-none');
                        }
                    }, 400);
                }

            } catch (error) {
                console.error("Error unregistering item:", error);
                btn.innerHTML = originalText;
                btn.classList.remove('disabled');
                btn.disabled = false;

                // Show a red inline alert if something breaks
                showInlineAlert("Something went wrong. Please try again.", "danger");
            }
        };
    </script>

<?= $this->endSection() ?>