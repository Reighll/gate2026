<?= $this->extend('Admin/layout/main') ?>
<?= $this->section('title') ?>Visitors Pass | Admin Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/admin-visitors.css') ?>">


    <div class="pt-5 mt-4">

        <div class="row skeleton-wrapper">
            <?php for($i=0; $i<2; $i++): ?>
                <div class="col-md-6">
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
            <div class="col-md-6">
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

            <div class="col-md-6">
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

                <ul class="nav nav-tabs mb-4" id="visitorTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#logs">History Logs</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#passes">Manage RFID Passes</button>
                    </li>
                </ul>

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

                                <tbody class="skeleton-wrapper">
                                <?php for($i=0; $i<5; $i++): ?>
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
                                    <tr><td colspan="7" class="text-center text-muted">No visitor history yet.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($logs as $log): ?>
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
                                            <td data-label="Time In"><?= date('h:i A', strtotime($log['time_in'])) ?></td>
                                            <td data-label="Time Out"><?= $log['time_out'] ? date('h:i A', strtotime($log['time_out'])) : '<span class="badge bg-warning">Active</span>' ?></td>
                                            <td data-label="Status">
                                                <?php if ($log['status'] === 'active'): ?>
                                                    <span class="badge bg-success">Inside</span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-dark">Checked Out</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Action">
                                                <?php if ($log['status'] === 'active'): ?>
                                                    <a href="<?= base_url('admin/visitors/force-checkout/' . $log['id']) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Force checkout this visitor?');">
                                                        <i class="ti ti-logout me-1"></i> Force Check-out
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted small">--</span>
                                                <?php endif; ?>
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

                                <tbody class="skeleton-wrapper">
                                <?php for($i=0; $i<4; $i++): ?>
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
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger"
                                                   data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
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
    <script src="<?= base_url('assets/js/admin/admin-visitors.js') ?>"></script>
<?= $this->endSection() ?>