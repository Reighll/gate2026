<?= $this->extend('Admin/layout/main') ?>
<?= $this->section('title') ?>Item Reports | Admin Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <div class="page-transition-container pt-5 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-semibold mb-0">Reported Items & Flags</h4>
        </div>

        <div class="row mb-4 skeleton-wrapper">
            <?php for($i=0; $i<2; $i++): ?>
                <div class="col-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="w-100">
                                    <div class="skeleton skeleton-text w-75 mb-3"></div>
                                    <div class="skeleton skeleton-title w-50 mb-0"></div>
                                </div>
                                <div class="skeleton skeleton-avatar ms-3" style="width: 45px; height: 45px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="row mb-4 real-wrapper d-none">
            <div class="col-6 mb-3">
                <div class="card border-0 shadow-sm h-100 border-bottom border-4 border-danger rounded-4">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted fw-normal mb-2">Active Missing Reports</h6>
                                <h3 class="fw-bold mb-0 text-danger"><?= esc($activeMissingCount ?? 0) ?></h3>
                            </div>
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                <i class="ti ti-alert-triangle fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 mb-3">
                <div class="card border-0 shadow-sm h-100 border-bottom border-4 border-success rounded-4">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted fw-normal mb-2">Resolved Items</h6>
                                <h3 class="fw-bold mb-0 text-success"><?= esc($resolvedCount ?? 0) ?></h3>
                            </div>
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                <i class="ti ti-rotate fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success shadow-sm border-0 mb-4 rounded-3 d-flex align-items-center">
                <i class="ti ti-check-circle fs-4 me-2"></i><?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- ==================== MISSING ITEMS ==================== -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="ti ti-list me-2 text-danger"></i> Missing Items</h5>

                    <form action="<?= base_url('admin/item-reports') ?>" method="GET" class="d-flex align-items-center gap-2 bg-light p-2 rounded-3 border">
                        <input type="hidden" name="resolved_filter" value="<?= esc($resolvedFilter) ?>">
                        <input type="hidden" name="resolved_start_date" value="<?= esc($resolvedStartRaw ?? '') ?>">
                        <input type="hidden" name="resolved_end_date" value="<?= esc($resolvedEndRaw ?? '') ?>">

                        <select name="missing_filter" class="form-select form-select-sm border-0 bg-white fw-bold text-secondary" style="width: auto;" onchange="toggleCustom(this, 'missingCustomContainer')">
                            <option value="today" <?= $missingFilter === 'today' ? 'selected' : '' ?>>Today</option>
                            <option value="7days" <?= $missingFilter === '7days' ? 'selected' : '' ?>>Past 7 Days</option>
                            <option value="month" <?= $missingFilter === 'month' ? 'selected' : '' ?>>This Month</option>
                            <option value="year" <?= $missingFilter === 'year' ? 'selected' : '' ?>>This Year</option>
                            <option value="custom" <?= $missingFilter === 'custom' ? 'selected' : '' ?>>Custom Date</option>
                        </select>

                        <div id="missingCustomContainer" class="d-flex align-items-center gap-2 <?= $missingFilter === 'custom' ? '' : 'd-none' ?>">
                            <input type="date" name="missing_start_date" class="form-control form-control-sm border-0 bg-white" value="<?= esc($missingStartRaw ?? '') ?>">
                            <span class="text-muted fw-bold">-</span>
                            <input type="date" name="missing_end_date" class="form-control form-control-sm border-0 bg-white" value="<?= esc($missingEndRaw ?? '') ?>">
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3"><i class="ti ti-filter me-1"></i> Filter</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0 border-light">
                        <thead style="border-bottom: 2px solid #f0f0f0;">
                        <tr>
                            <th>Reporter Name</th><th>Item Name</th><th>Reported On</th><th>Action</th><th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(empty($missingReports)): ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-5 w-100">
                                        <i class="ti ti-check-circle fs-1 mb-2 text-muted"></i>
                                        <p class="mb-0 text-muted">No missing items reported for this timeframe.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($missingReports as $report): ?>
                                <tr>
                                    <td data-label="Reporter Name"><?= esc($report['first_name'] . ' ' . $report['last_name']) ?></td>
                                    <td data-label="Item Name"><?= esc($report['brand_model'] ?? $report['name'] ?? $report['item_name']) ?></td>
                                    <td data-label="Reported On"><?= date('m-d-y', strtotime($report['updated_at'])) ?></td>
                                    <td data-label="Action">
                                        <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#viewModalMissing<?= $report['id'] ?>">VIEW</button>
                                    </td>
                                    <td data-label="Status"><span class="badge bg-danger rounded-pill">ONGOING</span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================== RESOLVED ITEMS ==================== -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="ti ti-rotate me-2 text-success"></i> Resolved Items</h5>

                    <form action="<?= base_url('admin/item-reports') ?>" method="GET" class="d-flex align-items-center gap-2 bg-light p-2 rounded-3 border">
                        <input type="hidden" name="missing_filter" value="<?= esc($missingFilter) ?>">
                        <input type="hidden" name="missing_start_date" value="<?= esc($missingStartRaw ?? '') ?>">
                        <input type="hidden" name="missing_end_date" value="<?= esc($missingEndRaw ?? '') ?>">

                        <select name="resolved_filter" class="form-select form-select-sm border-0 bg-white fw-bold text-secondary" style="width: auto;" onchange="toggleCustom(this, 'resolvedCustomContainer')">
                            <option value="today" <?= $resolvedFilter === 'today' ? 'selected' : '' ?>>Today</option>
                            <option value="7days" <?= $resolvedFilter === '7days' ? 'selected' : '' ?>>Past 7 Days</option>
                            <option value="month" <?= $resolvedFilter === 'month' ? 'selected' : '' ?>>This Month</option>
                            <option value="year" <?= $resolvedFilter === 'year' ? 'selected' : '' ?>>This Year</option>
                            <option value="custom" <?= $resolvedFilter === 'custom' ? 'selected' : '' ?>>Custom Date</option>
                        </select>

                        <div id="resolvedCustomContainer" class="d-flex align-items-center gap-2 <?= $resolvedFilter === 'custom' ? '' : 'd-none' ?>">
                            <input type="date" name="resolved_start_date" class="form-control form-control-sm border-0 bg-white" value="<?= esc($resolvedStartRaw ?? '') ?>">
                            <span class="text-muted fw-bold">-</span>
                            <input type="date" name="resolved_end_date" class="form-control form-control-sm border-0 bg-white" value="<?= esc($resolvedEndRaw ?? '') ?>">
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3"><i class="ti ti-filter me-1"></i> Filter</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0 border-light">
                        <thead style="border-bottom: 2px solid #f0f0f0;">
                        <tr>
                            <th>Reporter Name</th><th>Item Name</th><th>Resolved On</th><th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(empty($resolvedReports)): ?>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-5 w-100">
                                        <i class="ti ti-history fs-1 mb-2 text-muted opacity-50"></i>
                                        <p class="mb-0 text-muted">No resolved items for this timeframe.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($resolvedReports as $report): ?>
                                <tr>
                                    <td data-label="Reporter Name"><?= esc($report['first_name'] . ' ' . $report['last_name']) ?></td>
                                    <td data-label="Item Name"><?= esc($report['brand_model'] ?? $report['name'] ?? $report['item_name']) ?></td>
                                    <td data-label="Resolved On">
                                        <span class="d-block fw-semibold text-dark" style="font-size: 0.85rem;"><?= date('M d, Y', strtotime($report['resolved_at'])) ?></span>
                                        <span class="small text-muted"><?= date('h:i A', strtotime($report['resolved_at'])) ?></span>
                                    </td>
                                    <td data-label="Status"><span class="badge bg-success rounded-pill">RESOLVED</span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if(!empty($missingReports)): ?>
            <?php foreach($missingReports as $report): ?>
                <div class="modal fade" id="viewModalMissing<?= $report['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 rounded-4 shadow">
                            <div class="modal-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="fw-bold mb-0">REPORT TYPE- <span class="text-danger"><?= esc(strtoupper($report['report_type'] ?? 'MISSING')) ?></span></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <p class="mb-2 fs-5"><?= esc($report['brand_model'] ?? $report['name'] ?? $report['item_name']) ?></p>
                                        <?php if(!empty($report['specs'])): ?>
                                            <p class="mb-2 text-muted"><?= esc($report['specs']) ?></p>
                                        <?php endif; ?>
                                        <?php if(!empty($report['tracking_number'])): ?>
                                            <p class="mb-2 text-muted"><?= esc($report['tracking_number']) ?></p>
                                        <?php endif; ?>
                                        <?php if(!empty($report['category'])): ?>
                                            <p class="mb-3 text-muted"><?= esc($report['category']) ?></p>
                                        <?php endif; ?>
                                        <h6 class="fw-bold">Notes :</h6>
                                        <div class="bg-light rounded-3 p-3 text-muted fst-italic">
                                            <?= !empty($report['notes']) ? esc($report['notes']) : 'Briefly explain specific item notes like (colors, signs and other info might help)' ?>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-center mt-4 mt-md-0">
                                        <?php if(!empty($report['photo'])): ?>
                                            <img src="<?= base_url('uploads/items/' . esc($report['photo'])) ?>" class="img-fluid rounded-3 shadow-sm" alt="Item image" style="max-height: 200px; object-fit: contain;">
                                        <?php else: ?>
                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height:180px;">
                                                <i class="ti ti-photo fs-1 text-muted opacity-50"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 justify-content-center pb-4">
                                <form hx-disable action="<?= base_url('admin/item-reports/resolve/' . $report['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn px-4 py-2 rounded-pill fw-semibold text-white shadow-sm" style="background-color: #39cb7f; border:none;">
                                        <i class="ti ti-check me-1"></i> REPORT RESOLVED
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        function toggleCustom(select, containerId) {
            document.getElementById(containerId).classList.toggle('d-none', select.value !== 'custom');
        }
    </script>
<?= $this->endSection() ?>