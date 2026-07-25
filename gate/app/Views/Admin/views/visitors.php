<?= $this->extend('Admin/layout/main') ?>
<?= $this->section('title') ?>Visitors Pass | Admin Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/admin-visitors.css') ?>">


    <div class="page-transition-container pt-5 mt-4">

        <div class="row skeleton-wrapper">
            <?php for($i=0; $i<2; $i++): ?>
                <div class="col-6">
                    <div class="card shadow-none border-0 rounded-4 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="skeleton skeleton-avatar me-3" style="width: 40px; height: 40px;"></div>
                                <div class="w-75">
                                    <div class="skeleton skeleton-title w-50 mb-1"></div>
                                    <div class="skeleton skeleton-text w-25 mb-0"></div>
                                </div>
                            </div>
                            <div class="skeleton skeleton-title w-25 mt-3 mb-0" style="height: 38px;"></div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="row real-wrapper d-none">
            <div class="col-6">
                <div class="card bg-light-info shadow-none border-0 mb-4 rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="round-40 bg-info d-flex align-items-center justify-content-center rounded-circle text-white me-3">
                                <i class="ti ti-users fs-6"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fs-4 fw-semibold text-info">Visitors Inside</h6>
                                <h6 class="mb-0 fs-2 text-muted">Active Sessions</h6>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-0 text-info"><?= $visitors_inside ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card bg-light-success shadow-none border-0 mb-4 rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="round-40 bg-success d-flex align-items-center justify-content-center rounded-circle text-white me-3">
                                <i class="ti ti-id-badge fs-6"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fs-4 fw-semibold text-success">Slots Available</h6>
                                <h6 class="mb-0 fs-2 text-muted">RFID Cards Ready</h6>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-0 text-success"><?= $slots_available ?> <span class="fs-4 text-muted fw-normal">/ <?= $total_tags ?> Total</span></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card w-100 rounded-4 border-0 shadow-sm">
            <div class="card-body p-4">

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <ul class="nav nav-tabs flex-nowrap overflow-auto mb-0" id="visitorTabs" role="tablist" style="scrollbar-width: none;">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#logs">History Logs</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#passes">Manage RFID Passes</button>
                        </li>
                    </ul>

                    <form action="<?= base_url('admin/visitors') ?>" method="GET" class="d-flex align-items-center gap-2 bg-white p-2 rounded-3 shadow-sm border flex-wrap">
                        <select name="filter" id="visitorDateFilter" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary cursor-pointer" style="width: auto;">
                            <option value="today" <?= $filter === 'today' ? 'selected' : '' ?>>Today</option>
                            <option value="7days" <?= $filter === '7days' ? 'selected' : '' ?>>Past 7 Days</option>
                            <option value="month" <?= $filter === 'month' ? 'selected' : '' ?>>This Month</option>
                            <option value="year" <?= $filter === 'year' ? 'selected' : '' ?>>This Year</option>
                            <option value="custom" <?= $filter === 'custom' ? 'selected' : '' ?>>Custom Date</option>
                        </select>

                        <div id="visitorCustomDateContainer" class="d-flex align-items-center gap-2 <?= $filter === 'custom' ? '' : 'd-none' ?>">
                            <input type="date" name="start_date" class="form-control form-control-sm border-0 bg-light text-secondary" value="<?= esc($startDateRaw ?? '') ?>">
                            <span class="text-muted fw-bold">-</span>
                            <input type="date" name="end_date" class="form-control form-control-sm border-0 bg-light text-secondary" value="<?= esc($endDateRaw ?? '') ?>">
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3"><i class="ti ti-filter me-1"></i> Filter</button>
                    </form>
                </div>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="logs">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle table-hover">
                                <thead class="text-dark fs-4 bg-light">
                                <tr>
                                    <th>Visitor</th>
                                    <th>ID Proof</th>
                                    <th>Tag Used</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <?php $logsSkeletonRows = !empty($logs) ? count($logs) : 1; ?>
                                <tbody class="skeleton-wrapper">
                                <?php for($i=0; $i<$logsSkeletonRows; $i++): ?>
                                    <tr>
                                        <td>
                                            <div class="skeleton skeleton-title w-75 mb-1" style="height: 18px;"></div>
                                            <div class="skeleton skeleton-text w-50 mb-0"></div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="skeleton skeleton-avatar me-2" style="width: 35px; height: 35px;"></div>
                                                <div class="skeleton skeleton-text w-50 mb-0"></div>
                                            </div>
                                        </td>
                                        <td><div class="skeleton skeleton-badge rounded-pill" style="width: 70px; height: 24px;"></div></td>
                                        <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                        <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                        <td><div class="skeleton skeleton-badge rounded-pill" style="width: 60px; height: 22px;"></div></td>
                                        <td><div class="skeleton skeleton-badge rounded-2" style="width: 110px; height: 30px;"></div></td>
                                    </tr>
                                <?php endfor; ?>
                                </tbody>

                                <tbody class="real-wrapper d-none">
                                <?php if(empty($logs)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center border-0 py-5">
                                            <div class="d-flex flex-column align-items-center justify-content-center text-muted my-3 opacity-75">
                                                <span class="fw-medium fs-6">No visitor history yet.</span>
                                                <small class="mt-1">Logs will appear here once visitors are recorded.</small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($logs as $log): ?>
                                        <?php
                                        // Initialize variable to prevent Undefined variable error
                                        $isInside = (empty($log['time_out']) || strpos($log['time_out'], '0000') !== false || $log['status'] === 'active');
                                        ?>
                                        <tr>
                                            <td data-label="Visitor">
                                                <h6 class="fw-semibold mb-1"><?= esc($log['name']) ?></h6>
                                                <small class="text-muted"><?= esc($log['purpose']) ?></small>
                                            </td>
                                            <td data-label="ID Proof">
                                                <?php
                                                $photoUrl = $log['id_photo']
                                                        ? base_url('uploads/visitor_ids/' . $log['id_photo'])
                                                        : base_url('assets/images/profile/user-1.jpg');
                                                ?>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0)" onclick="showId('<?= $photoUrl ?>', '<?= esc($log['valid_id']) ?>')" data-bs-toggle="modal" data-bs-target="#idModal">
                                                        <img src="<?= $photoUrl ?>" class="rounded-circle border" width="35" height="35" style="object-fit: cover;" alt="ID">
                                                    </a>
                                                    <div class="ms-3">
                                                        <span class="fw-normal fs-2"><?= esc($log['valid_id']) ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Tag Used"><span class="badge bg-light-secondary text-secondary font-monospace"><?= esc($log['tag_id']) ?></span></td>
                                            <td data-label="Time In">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold text-dark"><?= date('h:i A', strtotime($log['time_in'])) ?></span>
                                                    <span class="text-muted small"><?= date('M d, Y', strtotime($log['time_in'])) ?></span>
                                                </div>
                                            </td>
                                            <td data-label="Time Out">
                                                <?php if ($log['time_out']): ?>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-dark"><?= date('h:i A', strtotime($log['time_out'])) ?></span>
                                                        <span class="text-muted small"><?= date('M d, Y', strtotime($log['time_out'])) ?></span>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Active</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Status">
                                                <?php if ($log['status'] === 'active'): ?>
                                                    <span class="badge bg-success">Inside</span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-dark">Checked Out</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Action">
                                                <?php if ($isInside): ?>
                                                    <a href="<?= base_url('admin/visitors/force-checkout/' . $log['id']) ?>" class="btn btn-sm btn-outline-danger shadow-sm me-1" onclick="return confirm('Force checkout this visitor?');">
                                                        <i class="ti ti-logout"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <a href="javascript:void(0)"
                                                   class="btn btn-sm btn-outline-dark shadow-sm"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#deleteConfirmModal"
                                                   data-bs-url="<?= base_url('admin/visitors/delete-log/'.$log['id']) ?>"
                                                   data-bs-message="Permanently delete this visitor log entry? This cannot be undone.">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="passes">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="card-title">RFID Inventory</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPassModal">
                                <i class="ti ti-scan me-1"></i> Scan New Pass
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table mb-0 align-middle table-hover">
                                <thead class="bg-light">
                                <tr>
                                    <th>Pass Name</th>
                                    <th>RFID UID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <?php $tagsSkeletonRows = !empty($tags) ? count($tags) : 1; ?>
                                <tbody class="skeleton-wrapper">
                                <?php for($i=0; $i<$tagsSkeletonRows; $i++): ?>
                                    <tr>
                                        <td><div class="skeleton skeleton-title w-50 mb-0" style="height: 20px;"></div></td>
                                        <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                        <td><div class="skeleton skeleton-badge rounded-pill" style="width: 70px; height: 24px;"></div></td>
                                        <td><div class="skeleton skeleton-badge rounded-2" style="width: 65px; height: 30px;"></div></td>
                                    </tr>
                                <?php endfor; ?>
                                </tbody>

                                <tbody class="real-wrapper d-none">
                                <?php if(empty($tags)): ?>
                                    <tr><td colspan="4" class="text-center">No passes found. Scan some cards!</td></tr>
                                <?php else: ?>
                                    <?php foreach ($tags as $tag): ?>
                                        <tr>
                                            <td data-label="Pass Name"><span class="fw-bold"><?= esc($tag['pass_number']) ?></span></td>
                                            <td data-label="RFID UID">
                                                <span class="rfid-uid"><?= esc($tag['rfid_uid']) ?></span>
                                            </td>                                            <td data-label="Status">
                                                <?php if($tag['status'] == 'available'): ?>
                                                    <span class="badge bg-success">Available</span>
                                                <?php elseif($tag['status'] == 'in_use'): ?>
                                                    <span class="badge bg-warning">In Use</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Lost</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Action">
                                                <a href="javascript:void(0)"
                                                   class="btn btn-sm btn-outline-danger"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#deleteConfirmModal"
                                                   data-bs-url="<?= base_url('admin/visitors/delete-tag/'.$tag['id']) ?>"
                                                   data-bs-message="Delete this pass?">
                                                    Delete
                                                </a>
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

<?= $this->include('Admin/modals/admin/add_pass') ?>
<?= $this->include('Admin/modals/admin/delete_confirm') ?>

    <div class="modal fade" id="idModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img id="modalIdImage" src="" class="img-fluid rounded" alt="ID Proof">
                    <p id="modalIdText" class="mt-2 fw-bold text-muted"></p>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/admin/admin-profile.js') ?>"></script>
<?= $this->endSection() ?>