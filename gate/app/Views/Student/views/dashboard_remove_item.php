<?= $this->extend('Student/layout/main') ?>
<?= $this->section('title') ?>Remove Item | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <h4 class="fw-semibold mb-3">Item Unregistration</h4>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <p class="text-muted mb-4">Select an item to request unregistration (e.g., if lost, sold, or no longer used). An admin must approve this request.</p>

                <?php
                // Filter only approved items for this view
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
                    <?php foreach ($approvedItems as $item): ?>
                        <div class="border rounded p-3 d-flex justify-content-between align-items-center mb-3 border-warning bg-light">
                            <div>
                                <h6 class="fw-bold mb-0"><?= esc($item['brand_model']) ?></h6>
                                <small class="text-muted">SN: <?= esc($item['serial_number']) ?></small>
                            </div>
                            <button type="button"
                                    class="btn btn-outline-warning btn-sm fw-bold"
                                    data-bs-toggle="modal"
                                    data-bs-target="#unregisterModal"
                                    data-item-name="<?= esc($item['brand_model']) ?>"
                                    data-action-url="<?= base_url('student/items/request-unregister/' . $item['id']) ?>">
                                Unregister
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="unregisterModal" tabindex="-1" aria-labelledby="unregisterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-header bg-warning-subtle border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold text-warning-emphasis" id="unregisterModalLabel">
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

                    <p class="fw-bold fs-5 text-dark mt-1 mb-3" id="modalItemName"></p>

                    <div class="alert alert-light border shadow-sm small text-start mb-0 text-dark">
                        <i class="ti ti-info-circle text-primary me-1"></i>
                        This item will be flagged for Admin review. You will not be able to use it as a valid gate pass until this is resolved.
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light rounded-bottom-4 d-flex justify-content-center gap-2 pb-4">
                    <button type="button" class="btn btn-light border fw-medium px-4" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmUnregisterBtn" class="btn btn-warning fw-bold px-4 shadow-sm">Yes, Request Unregister</a>
                </div>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/student/student-remove-item.js') ?>"></script>
<?= $this->endSection() ?>