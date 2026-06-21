<?= $this->extend('Student/layout/main') ?>
<?= $this->section('title') ?>Scan History | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <div class="card border-0 shadow-sm w-100">
            <div class="card-body p-4">
                <h4 class="card-title fw-semibold mb-4">Gatepass Scan History</h4>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0">
                        <thead class="text-dark fs-4 border-bottom border-2">
                        <tr>
                            <th class="border-0"><h6 class="fw-semibold mb-0">Date & Time</h6></th>
                            <th class="border-0"><h6 class="fw-semibold mb-0">Item Details</h6></th>
                            <th class="border-0"><h6 class="fw-semibold mb-0">Serial Number</h6></th>
                            <th class="border-0 text-end"><h6 class="fw-semibold mb-0">Action</h6></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="ti ti-history fs-8 d-block mb-2 opacity-50"></i>
                                    No scan history found for your devices yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($logs as $log): ?>
                                <tr>
                                    <td class="border-bottom-0">
                                        <span class="d-block fw-semibold text-dark"><?= date('M d, Y', strtotime($log['created_at'])) ?></span>
                                        <span class="small text-muted"><?= date('h:i A', strtotime($log['created_at'])) ?></span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0 text-dark"><?= esc($log['brand_model'] ?? $log['name'] ?? 'Unknown Item') ?></h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <p class="mb-0 fw-normal"><?= esc($log['serial_number'] ?? 'N/A') ?></p>
                                    </td>
                                    <td class="border-bottom-0 text-end">
                                        <?php if ($log['action'] === 'time_in'): ?>
                                            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 fs-3 shadow-sm rounded-pill">
                                                <i class="ti ti-login me-1"></i> Time In
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-2 fs-3 shadow-sm rounded-pill">
                                                <i class="ti ti-logout me-1"></i> Time Out
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>