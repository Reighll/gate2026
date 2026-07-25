<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Remove Item | Student Portal<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="page-transition-container pt-5 mt-4">
        <h4 class="fw-semibold mb-3">Item Unregistration</h4>

        <div id="alertContainer"></div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <p class="text-muted mb-4">Select an item to request unregistration (e.g., if lost, sold, or no longer used). An admin must approve this request.</p>

                <?php
                $approvedItems = array_filter($items, function($item) {
                    return $item['status'] === 'approved';
                });
                ?>

                <?php if (!empty($approvedItems)): ?>
                    <div class="skeleton-wrapper">
                        <?php for($i=0; $i<count($approvedItems); $i++): ?>
                            <div class="border rounded p-3 d-flex justify-content-between align-items-center mb-3 bg-light">
                                <div class="w-75">
                                    <div class="skeleton skeleton-title w-50 mb-1" style="height: 18px;"></div>
                                    <div class="skeleton skeleton-text w-25 mb-0" style="height: 12px;"></div>
                                </div>
                                <div class="skeleton rounded-2" style="width: 90px; height: 30px;"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

                <div class="real-wrapper <?= empty($approvedItems) ? '' : 'd-none' ?>" id="removeItemRealWrapper">
                    <?php if (empty($approvedItems)): ?>
                        <div class="text-center text-muted py-4" id="removeItemEmptyState">
                            <i class="ti ti-device-laptop d-block mb-3 text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
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
                                    <div class="modal-dialog modal-dialog-centered mx-auto px-3 px-sm-0">
                                        <div class="modal-content border-0 shadow-lg rounded-4">

                                            <form action="<?= base_url('student/items/request-unregister/' . $item['id']) ?>" method="POST">
                                                <?= csrf_field() ?>

                                                <div class="modal-header bg-warning-subtle border-0 rounded-top-4 sticky-top z-3 pt-3 pb-3 w-100 d-flex justify-content-between align-items-center">

                                                    <h5 class="modal-title fw-bold text-warning-emphasis mb-0 text-truncate pe-2" id="unregisterModalLabel<?= $item['id'] ?>">
                                                        <i class="ti ti-alert-triangle me-1"></i> Confirm Unregistration
                                                    </h5>

                                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                                        <!-- NEW: Cancel Button moved to the header! -->
                                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                    </div>

                                                </div>

                                                <div class="modal-body p-4 text-center">
                                                    <h5 class="fw-bold mb-2">Are you sure?</h5>
                                                    <p class="text-muted mb-0">You are about to request unregistration for:</p>

                                                    <div class="my-3 d-flex justify-content-center">
                                                        <?php if(!empty($item['photo'])): ?>
                                                            <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>"
                                                                 class="img-fluid rounded-3 shadow-sm border border-light"
                                                                 alt="Item image"
                                                                 style="max-height: 140px; width: auto; object-fit: contain;">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="height: 120px; width: 100%; max-width: 200px;">
                                                                <i class="ti ti-photo fs-2 text-muted opacity-50"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <p class="fw-bold fs-5 text-dark mb-3"><?= esc($item['brand_model']) ?></p>

                                                    <div class="alert alert-light border shadow-sm small text-start mb-0 text-dark">
                                                        <i class="ti ti-info-circle text-primary me-1"></i>
                                                        This item will be flagged for Admin review. You will not be able to use it as a valid gate pass until this is resolved.
                                                    </div>

                                                    <div class="text-start mb-4">
                                                        <label class="form-label fw-bold text-muted small">Reason for Unregistration <span class="text-danger">*</span></label>
                                                        <textarea class="form-control bg-light" name="reason" rows="2" placeholder="e.g., Sold the item, broken, no longer using it..." required></textarea>
                                                    </div>

                                                </div>

                                                <div class="modal-footer border-0 bg-light rounded-bottom-4 d-flex justify-content-center pb-4 modal-actions-mobile">
                                                    <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">
                                                        Yes, Request Unregister
                                                    </button>
                                                </div>
                                            </form>

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

    <link rel="stylesheet" href="<?= base_url('assets/css/student/remove-item.css') ?>">

    <script src="<?= base_url('assets/js/student/remove-item.js') ?>"></script>

<?= $this->endSection() ?>