<?= $this->extend('Admin/layout/main') ?>
<?= $this->section('title') ?>Registered Items | Admin Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <div class="row">
            <div class="col-12">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="fw-semibold mb-0">Student Equipment Management</h4>
                </div>

                <div class="row mb-4 skeleton-wrapper">
                    <?php for($i=0; $i<3; $i++): ?>
                        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                            <div class="card border-0 shadow-sm h-100 rounded-4">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="w-100">
                                            <div class="skeleton skeleton-text w-75 mb-3"></div>
                                            <div class="skeleton skeleton-title w-25 mb-0"></div>
                                        </div>
                                        <div class="skeleton skeleton-avatar ms-3" style="width: 54px; height: 54px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="row mb-4 real-wrapper d-none">
                    <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-sm h-100 border-bottom border-4 border-warning rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted fw-normal mb-2">Pending Requests</h6>
                                        <h3 class="fw-bold mb-0 text-warning"><?= esc($pendingItemsCount ?? 0) ?></h3>
                                    </div>
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                                        <i class="ti ti-device-laptop fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-sm h-100 border-bottom border-4 border-success rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted fw-normal mb-2">Approved Items</h6>
                                        <h3 class="fw-bold mb-0 text-success"><?= esc($approvedItemsCount ?? 0) ?></h3>
                                    </div>
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                                        <i class="ti ti-check fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100 border-bottom border-4 border-secondary rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted fw-normal mb-2">Declined Items</h6>
                                        <h3 class="fw-bold mb-0 text-secondary"><?= esc($rejectedItemsCount ?? 0) ?></h3>
                                    </div>
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                                        <i class="ti ti-x fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success p-3 shadow-sm border-0 rounded-3 mb-4"><i class="ti ti-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger p-3 shadow-sm border-0 rounded-3 mb-4"><i class="ti ti-alert-triangle me-2"></i><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <?php
                $unregisterRequests = array_filter($items, function($item) {
                    return $item['status'] === 'unregister requested';
                });
                ?>

                <?php if (!empty($unregisterRequests)): ?>
                    <div class="card border-0 shadow-sm mb-4 rounded-4 border-top border-4 border-warning real-wrapper d-none">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-warning-dark mb-2"><i class="ti ti-alert-triangle me-2"></i> Pending Unregistration Requests</h5>
                            <p class="text-muted small mb-4">The following students have requested to remove items from their active gatepass.</p>
                            <div class="table-responsive">
                                <table class="table align-middle text-nowrap mb-0 border-light">
                                    <thead style="border-bottom: 2px solid #f0f0f0;">
                                    <tr>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Student</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Photo</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Item Name</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Serial Number</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($unregisterRequests as $req): ?>
                                        <tr style="border-bottom: 1px solid #f6f6f6;">
                                            <td data-label="Student" class="py-3 text-muted"><?= esc($req['first_name'] . ' ' . $req['last_name']) ?></td>
                                            <td data-label="Photo" class="py-3">
                                                <?php if (!empty($req['photo'])): ?>
                                                    <a href="<?= base_url('uploads/items/' . esc($req['photo'])) ?>" target="_blank" title="Click to view full image">
                                                        <img src="<?= base_url('uploads/items/' . esc($req['photo'])) ?>" class="rounded shadow-sm border border-light" width="40" height="40" style="object-fit: cover;">
                                                    </a>
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                        <i class="ti ti-device-laptop text-muted fs-5"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Item Name" class="py-3 text-muted"><?= esc($req['name'] ?? $req['item_name'] ?? 'Unknown Item') ?></td>
                                            <td data-label="Serial Number" class="py-3 text-muted"><?= esc($req['serial_number'] ?? 'N/A') ?></td>
                                            <td data-label="Action" class="py-3">
                                                <div class="d-flex gap-2">
                                                    <a href="<?= base_url('admin/items/process/approve_unregister/' . $req['id']) ?>" class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm" style="background-color: #39cb7f; border: none;">Approve</a>
                                                    <a href="<?= base_url('admin/items/process/deny_unregister/' . $req['id']) ?>" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold shadow-sm" style="background-color: #e46a76; border: none;">Deny</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="ti ti-device-laptop me-2 text-primary"></i> Master Equipment List</h5>
                            <div class="input-group shadow-sm" style="max-width: 350px;">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="ti ti-search"></i></span>
                                <input type="text" id="itemSearch" class="form-control border-start-0 ps-0" placeholder="Search by TUPT ID or Name...">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle text-nowrap mb-0 border-light" id="itemsTable">
                                <thead style="border-bottom: 2px solid #f0f0f0;">
                                <tr>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">ID</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Student Name</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Student Number</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Photo</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Item Name</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Serial/Brand</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">RFID/NFC Tag</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Status</th>
                                    <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Actions</th>
                                </tr>
                                </thead>

                                <tbody class="skeleton-wrapper">
                                <?php for($i=0; $i<5; $i++): ?>
                                    <tr>
                                        <td><div class="skeleton skeleton-text w-50 mb-0"></div></td>
                                        <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                        <td><div class="skeleton skeleton-text w-100 mb-0"></div></td>
                                        <td><div class="skeleton rounded-3" style="width: 45px; height: 45px;"></div></td>
                                        <td><div class="skeleton skeleton-text w-100 mb-0"></div></td>
                                        <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                        <td><div class="skeleton skeleton-badge rounded-1" style="width: 80px; height: 26px;"></div></td>
                                        <td><div class="skeleton skeleton-badge rounded-1" style="width: 70px; height: 26px;"></div></td>
                                        <td><div class="skeleton skeleton-badge rounded-2" style="width: 85px; height: 30px;"></div></td>
                                    </tr>
                                <?php endfor; ?>
                                </tbody>

                                <tbody class="real-wrapper d-none">
                                <?php if (empty($items)) : ?>
                                    <tr class="no-data-row">
                                        <td colspan="9" class="text-center py-5 text-muted">No items found in the system.</td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($items as $item) : ?>
                                        <tr style="border-bottom: 1px solid #f6f6f6;">
                                            <td data-label="ID" class="py-3 text-muted"><?= $item['id'] ?></td>
                                            <td data-label="Student Name" class="py-3 text-muted"><?= esc($item['first_name'] . ' ' . $item['last_name']) ?></td>
                                            <td data-label="Student Number" class="py-3 text-muted"><?= esc($item['student_number'] ?? 'N/A') ?></td>
                                            <td data-label="Photo" class="py-3">
                                                <?php if (!empty($item['photo'])): ?>
                                                    <a href="<?= base_url('uploads/items/' . esc($item['photo'])) ?>" target="_blank" title="Click to view full image">
                                                        <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>" class="rounded-3 shadow-sm border border-light" width="45" height="45" style="object-fit: cover;">
                                                    </a>
                                                <?php else: ?>
                                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                                        <i class="ti ti-device-laptop text-muted fs-5"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Item Name" class="py-3 text-muted"><?= esc($item['name'] ?? $item['item_name'] ?? 'Unknown Item') ?></td>
                                            <td data-label="Serial/Brand" class="py-3 text-muted"><?= esc($item['serial_number'] ?? 'N/A') ?></td>
                                            <td data-label="RFID/NFC Tag" class="py-3">
                                                <?php if (!empty($item['rfid'])): ?>
                                                    <span class="badge rounded-1 shadow-sm px-3 py-2 fw-bold text-white" style="background-color: #1fc2ff; font-size: 0.8rem;">
                    <?= esc($item['rfid']) ?>
                </span>
                                                <?php else: ?>
                                                    <span class="text-muted small">Not Assigned</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Status" class="py-3">
                                                <?php
                                                $badgeStyle = 'background-color: #6c757d;'; // secondary
                                                if ($item['status'] === 'approved') $badgeStyle = 'background-color: #39cb7f;'; // success green
                                                if ($item['status'] === 'pending') $badgeStyle = 'background-color: #ffae1f; color: #fff;'; // warning orange
                                                if ($item['status'] === 'rejected') $badgeStyle = 'background-color: #e46a76;'; // danger red
                                                if ($item['status'] === 'inactive') $badgeStyle = 'background-color: #2a3547;'; // dark
                                                if ($item['status'] === 'unregister requested') $badgeStyle = 'background-color: #ffae1f; color: #fff;';
                                                ?>
                                                <span class="badge rounded-1 shadow-sm px-3 py-2 fw-bold" style="<?= $badgeStyle ?> font-size: 0.8rem;">
                <?= ucfirst(esc($item['status'])) ?>
            </span>
                                            </td>
                                            <td data-label="Actions" class="py-3">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary bg-white shadow-sm dropdown-toggle fw-semibold rounded-2 px-3 py-1" style="border-color: #5d87ff; color: #5d87ff;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu custom-dropdown shadow-lg border-0 rounded-4 p-2">
                                                        <li>
                                                            <button type="button" class="dropdown-item approve-item py-2" data-bs-toggle="modal" data-bs-target="#approveModal<?= $item['id'] ?>">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-box bg-light-success text-success me-3"><i class="ti ti-check"></i></div>
                                                                    <span class="fw-semibold">Approve & Assign</span>
                                                                </div>
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item reject-item py-2" href="<?= base_url('admin/items/process/reject/' . $item['id']) ?>">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-box bg-light-warning text-warning me-3"><i class="ti ti-x"></i></div>
                                                                    <span class="fw-semibold">Reject Request</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider border-light my-2"></li>
                                                        <li>
                                                            <a class="dropdown-item delete-item py-2" href="javascript:void(0)"
                                                               data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                               data-bs-url="<?= base_url('admin/items/process/delete/' . $item['id']) ?>"
                                                               data-bs-message="Are you sure you want to completely delete this item record?">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-box bg-light-danger text-danger me-3"><i class="ti ti-trash"></i></div>
                                                                    <span class="fw-semibold">Hard Delete</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
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
<?php if (!empty($items)) : ?>
    <?php foreach ($items as $item) : ?>
        <div class="modal fade" id="approveModal<?= $item['id'] ?>" tabindex="-1" aria-labelledby="approveModalLabel<?= $item['id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title fw-bold text-success" id="approveModalLabel<?= $item['id'] ?>"><i class="ti ti-nfc me-2"></i> Approve & Link RFID</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- THE FIX 1: We removed HTMX from here entirely and added a specific class "rfid-approval-form" to control it securely with Javascript -->
                    <form action="<?= base_url('admin/items/approveItem/' . $item['id']) ?>" method="POST" class="rfid-approval-form">

                        <?= csrf_field() ?>
                        <div class="modal-body text-start bg-light rounded-3 mx-3 p-4">
                            <p class="mb-3">Assigning an RFID/NFC card to a student</p>

                            <div class="mb-2">
                                <label for="rfid_<?= $item['id'] ?>" class="form-label fw-bold text-dark">Scan RFID/NFC Card</label>

                                <!-- The scanner acts as a keyboard. We let it naturally press "Enter" which triggers the form submit instantly! -->
                                <input type="text" class="form-control form-control-lg border-success text-success fw-bold shadow-sm"
                                       id="rfid_<?= $item['id'] ?>"
                                       name="rfid"
                                       placeholder="Tap card on scanner..."
                                       required autofocus autocomplete="off">

                                <small class="text-muted d-block mt-2"><i class="ti ti-info-circle me-1"></i> Ensure your cursor is in the field above before tapping the card.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-light fw-bold text-muted px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm" style="background-color: #39cb7f; border: none;">Link Card</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

    <script>
        window.scanApiUrl = "<?= base_url('admin/items/check-latest-scan') ?>";

        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }
        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);
    </script>
    <script src="<?= base_url('assets/js/admin/admin-items.js') ?>"></script>

<?= $this->endSection() ?>