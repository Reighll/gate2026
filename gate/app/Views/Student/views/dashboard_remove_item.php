<?= $this->extend('Student/layout/main') ?>

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
                            <a href="<?= base_url('student/items/request-unregister/' . $item['id']) ?>"
                               class="btn btn-outline-warning btn-sm fw-bold"
                               onclick="return confirm('Are you sure you want to unregister this item? It will be flagged for Admin review.');">
                                Unregister
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>