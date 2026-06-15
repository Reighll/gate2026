<?= $this->extend('Admin/layout/main') ?>

<?= $this->section('content') ?>
    <div class="pt-5 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-semibold  mb-0">Reported Items & Flags</h4>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-3 mb-md-4">
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

            <div class="col-lg-4 col-md-6 mb-3 mb-md-4">
                <div class="card border-0 shadow-sm h-100 border-bottom border-4 border-warning rounded-4">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted fw-normal mb-2">Flagged Items</h6>
                                <h3 class="fw-bold mb-0 text-warning"><?= esc($flaggedCount ?? 0) ?></h3>
                            </div>
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                <i class="ti ti-flag fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3 mb-md-4">
                <div class="card border-0 shadow-sm h-100 border-bottom border-4 border-secondary rounded-4">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted fw-normal mb-2">Inactive / Archived</h6>
                                <h3 class="fw-bold mb-0 text-secondary"><?= esc($inactiveCount ?? 0) ?></h3>
                            </div>
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                <i class="ti ti-archive fs-4"></i>
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

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3 p-md-4">
                <h5 class="fw-bold text-dark mb-4"><i class="ti ti-list me-2 text-danger"></i> Currently Missing Items</h5>
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0 border-light">
                        <thead style="border-bottom: 2px solid #f0f0f0;">
                        <tr>
                            <th>Reporter Name</th><th>Item Name</th><th>Date Reported</th><th>Action</th><th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(empty($reports)): ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-5 w-100">
                                        <i class="ti ti-check-circle fs-1 mb-2 text-muted"></i>
                                        <p class="mb-0 text-muted">No missing items reported. All clear!</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($reports as $report): ?>
                                <tr>
                                    <td data-label="Reporter Name"><?= esc($report['first_name'] . ' ' . $report['last_name']) ?></td>
                                    <td data-label="Item Name"><?= esc($report['brand_model'] ?? $report['name'] ?? $report['item_name']) ?></td>
                                    <td data-label="Date Reported"><?= date('m-d-y', strtotime($report['updated_at'])) ?></td>
                                    <td data-label="Action"><button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#viewModal<?= $report['id'] ?>">VIEW</button></td>
                                    <td data-label="Status"><span class="badge bg-danger rounded-pill">NEW</span></td>
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

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
<?= $this->endSection() ?>