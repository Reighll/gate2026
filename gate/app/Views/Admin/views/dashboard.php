<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Admin/layout/htmx' : 'Admin/layout/main';

$referrer = service('request')->getHeaderLine('HX-Current-URL');
$slideIn = (strpos($referrer, 'profile') !== false);
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Dashboard | Admin Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <link rel="stylesheet" href="<?= base_url('assets/css/admin/print.css') ?>" media="print" />

    <div id="dashboard-container" class="page-transition-container pt-5 mt-4 <?= $slideIn ? 'page-slide-in' : '' ?>">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <h4 class="fw-semibold mb-0">Dashboard Overview</h4>

            <form action="<?= base_url('admin/dashboard') ?>" method="GET" class="d-flex align-items-center gap-2 bg-white p-2 rounded-3 shadow-sm border">
                <select name="filter" id="dateFilter" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary cursor-pointer" style="width: auto;">
                    <option value="today" <?= $filter === 'today' ? 'selected' : '' ?>>Today</option>
                    <option value="7days" <?= $filter === '7days' ? 'selected' : '' ?>>Past 7 Days</option>
                    <option value="month" <?= $filter === 'month' ? 'selected' : '' ?>>This Month</option>
                    <option value="year" <?= $filter === 'year' ? 'selected' : '' ?>>This Year</option>
                    <option value="custom" <?= $filter === 'custom' ? 'selected' : '' ?>>Custom Date</option>
                </select>

                <div id="customDateContainer" class="d-flex align-items-center gap-2 <?= $filter === 'custom' ? '' : 'd-none' ?>">
                    <input type="date" name="start_date" class="form-control form-control-sm border-0 bg-light text-secondary" value="<?= esc($startDateRaw ?? '') ?>">
                    <span class="text-muted fw-bold">-</span>
                    <input type="date" name="end_date" class="form-control form-control-sm border-0 bg-light text-secondary" value="<?= esc($endDateRaw ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary btn-sm fw-bold px-3"><i class="ti ti-filter me-1"></i> Filter</button>
            </form>
        </div>

        <div class="row skeleton-wrapper">
            <?php for($i=0; $i<3; $i++): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="w-100">
                                    <div class="skeleton skeleton-text w-50 mb-3"></div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="skeleton skeleton-title w-25 mb-0"></div>
                                        <div class="skeleton skeleton-badge" style="width: 100px;"></div>
                                    </div>
                                </div>
                                <div class="skeleton skeleton-avatar ms-3" style="width: 54px; height: 54px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="row real-wrapper d-none">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card dashboard-filter-card border-0 shadow-sm h-100" data-filter-action="time_in" role="button" tabindex="0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted fw-normal mb-2">Student Entries</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="fw-bold mb-0 text-dark"><?= esc($studentEntryCount ?? 0) ?></h3>
                                    <span class="badge bg-light-subtle text-muted border fs-2 fw-semibold" title="All-Time Total">All-Time: <?= esc($totalStudentEntries ?? 0) ?></span>
                                </div>
                            </div>
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                                <i class="ti ti-login fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card dashboard-filter-card border-0 shadow-sm h-100" data-filter-action="visitor" role="button" tabindex="0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted fw-normal mb-2">Visitor Entries</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="fw-bold mb-0 text-dark"><?= esc($visitorEntryCount ?? 0) ?></h3>
                                    <span class="badge bg-light-subtle text-muted border fs-2 fw-semibold" title="All-Time Total">All-Time: <?= esc($totalVisitorEntries ?? 0) ?></span>
                                </div>
                            </div>
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                                <i class="ti ti-walk fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card dashboard-filter-card border-0 shadow-sm h-100 border-bottom border-4 border-danger" data-filter-action="missing" role="button" tabindex="0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted fw-normal mb-2">Missing Item Reports</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="fw-bold mb-0 text-danger"><?= esc($itemReportsCount ?? 0) ?></h3>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-2 fw-semibold" title="All-Time Total">All-Time: <?= esc($totalItemReports ?? 0) ?></span>
                                </div>
                            </div>
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                                <i class="ti ti-alert-triangle fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2" id="printableTable">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 w-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 no-print">
                            <h5 class="fw-bold text-dark mb-0"><i class="ti ti-activity me-2 text-primary"></i> Live Gatepass Activity</h5>

                            <button class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3 shadow-sm" onclick="window.print()">
                                <i class="ti ti-printer me-1"></i> Print
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle text-nowrap mb-0 border-light table-hover" id="gatepassLogsTable">
                                <thead style="border-bottom: 2px solid #f0f0f0;">
                                <tr>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Date & Time</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Action</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Student</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Equipment Details</th>
                                </tr>
                                </thead>

                                <?php $logsSkeletonRows = !empty($recentLogs) ? count($recentLogs) : 1; ?>
                                <tbody class="skeleton-wrapper">
                                <?php for($i=0; $i<$logsSkeletonRows; $i++): ?>
                                    <tr style="border-bottom: 1px solid #f6f6f6;">
                                        <td class="py-3">
                                            <div class="skeleton skeleton-text w-75"></div>
                                            <div class="skeleton skeleton-text w-50 mb-0 mt-1"></div>
                                        </td>
                                        <td class="py-3">
                                            <div class="skeleton skeleton-badge rounded-pill" style="width: 100px; height: 32px;"></div>
                                        </td>
                                        <td class="py-3">
                                            <div class="skeleton skeleton-text w-100"></div>
                                            <div class="skeleton skeleton-text w-75 mb-0 mt-1"></div>
                                        </td>
                                        <td class="py-3">
                                            <div class="skeleton skeleton-text w-100"></div>
                                            <div class="skeleton skeleton-text w-50 mb-0 mt-1"></div>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                                </tbody>

                                <tbody class="real-wrapper d-none">
                                <?php if(empty($recentLogs)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="ti ti-history fs-1 d-block mb-2 opacity-50"></i>
                                            No scan history recorded yet for this timeframe.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($recentLogs as $log): ?>
                                        <tr style="border-bottom: 1px solid #f6f6f6;" class="log-row" data-log-action="<?= esc($log['action']) ?>">
                                            <td class="py-3">
                                                <?php
                                                $logTime = new DateTime($log['created_at']);
                                                ?>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark"><?= $logTime->format('M d, Y') ?></span>
                                                    <span class="text-muted small"><i class="ti ti-clock me-1"></i><?= $logTime->format('h:i:s A') ?></span>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <?php if ($log['action'] === 'time_in'): ?>
                                                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 fs-6 shadow-sm rounded-pill" style="border: 1px solid #39cb7f;">
                                                            <i class="ti ti-login me-1"></i> Time In
                                                        </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-2 fs-6 shadow-sm rounded-pill" style="border: 1px solid #6c757d;">
                                                            <i class="ti ti-logout me-1"></i> Time Out
                                                        </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold"><?= esc($log['first_name'] . ' ' . $log['last_name']) ?></span>
                                                    <span class="text-muted small font-monospace"><?= esc($log['student_number'] ?? 'N/A') ?> &bull; <?= esc($log['department'] ?? '') ?></span>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold"><?= esc($log['brand_model'] ?? $log['item_name_fallback'] ?? 'Unknown Item') ?></span>
                                                    <span class="text-muted small font-monospace">SN: <?= esc($log['serial_number'] ?? $log['serial_fallback'] ?? 'N/A') ?></span>
                                                </div>
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
        </div>
    </div>

    <style>
        .dashboard-filter-card { cursor: pointer; transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .dashboard-filter-card:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important; }
        .dashboard-filter-card.dashboard-filter-active { box-shadow: 0 0.5rem 1.25rem rgba(0,0,0,0.18) !important; outline: 2px solid rgba(0,0,0,0.08); }
    </style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
    <script>
        // 5. UPDATED: Standardized HTMX-compatible skeleton script
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }

        // Run on normal refresh
        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        // Run on HTMX navigation
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);
    </script>
<?= $this->endSection() ?>