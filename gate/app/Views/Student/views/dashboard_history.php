<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Scan History | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="page-transition-container pt-5 mt-4">
        <div class="card border-0 shadow-sm w-100">
            <div class="card-body p-4">
                <h4 class="card-title fw-semibold mb-4">Gatepass Scan History</h4>

                <div class="skeleton-wrapper">
                    <div class="d-block d-md-none">
                        <?php for($i=0; $i<4; $i++): ?>
                            <div class="px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="w-50">
                                        <div class="skeleton skeleton-title w-75 mb-1" style="height: 18px;"></div>
                                        <div class="skeleton skeleton-text w-50 mb-0"></div>
                                    </div>
                                    <div class="skeleton skeleton-badge rounded-pill" style="width: 80px; height: 26px;"></div>
                                </div>
                                <div class="skeleton rounded-3 w-100 mt-2" style="height: 60px;"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <!-- Desktop Skeleton -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead class="border-bottom border-2">
                            <tr>
                                <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                <th><div class="skeleton skeleton-text w-50 mb-0 ms-auto"></div></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for($i=0; $i<5; $i++): ?>
                                <tr>
                                    <td class="py-3"><div class="skeleton skeleton-text w-75 mb-1"></div><div class="skeleton skeleton-text w-50 mb-0"></div></td>
                                    <td class="py-3"><div class="skeleton skeleton-title w-100 mb-0" style="height: 20px;"></div></td>
                                    <td class="py-3"><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                    <td class="py-3 text-end"><div class="skeleton skeleton-badge rounded-pill ms-auto" style="width: 100px; height: 32px;"></div></td>
                                </tr>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="real-wrapper d-none">
                    <?php if (empty($logs)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="ti ti-history d-block mb-2 opacity-50" style="font-size: 3rem;"></i>
                            No scan history found for your devices yet.
                        </div>
                    <?php else: ?>

                        <div class="d-block d-md-none">
                            <div class="list-group list-group-flush">
                                <?php foreach($logs as $log): ?>
                                    <div class="list-group-item px-0 py-3 border-bottom">

                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="d-block fw-bold text-dark fs-4"><?= date('M d, Y', strtotime($log['created_at'])) ?></span>
                                                <span class="small text-muted fw-medium"><?= date('h:i A', strtotime($log['created_at'])) ?></span>
                                            </div>
                                            <div>
                                                <?php if ($log['action'] === 'time_in'): ?>
                                                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 fs-2 shadow-sm rounded-pill">
                                                        <i class="ti ti-login me-1"></i> Time In
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 fs-2 shadow-sm rounded-pill">
                                                        <i class="ti ti-logout me-1"></i> Time Out
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="bg-light rounded-3 p-2 mt-2 border">
                                            <h6 class="fw-semibold mb-1 text-dark fs-4 text-truncate">
                                                <?= esc($log['brand_model'] ?? $log['name'] ?? 'Unknown Item') ?>
                                            </h6>
                                            <div class="d-flex align-items-center text-muted small" style="font-size: 0.75rem;">
                                                <i class="ti ti-barcode me-1"></i> SN: <span class="fw-bold ms-1 text-dark"><?= esc($log['serial_number'] ?? 'N/A') ?></span>
                                            </div>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="table-responsive d-none d-md-block">
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
                                </tbody>
                            </table>
                        </div>

                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }

        document.addEventListener("DOMContentLoaded", hideMySkeletons);

        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);
    </script>
<?= $this->endSection() ?>