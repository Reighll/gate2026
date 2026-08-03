<?= $this->extend('Admin/layout/main') ?>
<?= $this->section('title') ?>Registered Items | Admin Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="page-transition-container pt-5 mt-4">
        <div class="row">
            <div class="col-12">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="fw-semibold mb-0">Student Equipment Management</h4>
                </div>

                <div class="row mb-4 skeleton-wrapper">
                    <?php for($i=0; $i<4; $i++): ?>
                        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                            <div class="card border-0 shadow-sm h-100 rounded-4">
                                <div class="card-body p-3 p-md-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="w-100">
                                            <div class="skeleton skeleton-text w-75 mb-3"></div>
                                            <div class="skeleton skeleton-title w-25 mb-0"></div>
                                        </div>
                                        <div class="skeleton skeleton-avatar ms-3 stat-icon" style="width: 54px; height: 54px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="row mb-4 real-wrapper d-none" id="statusFilterCards">
                    <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card status-filter-card border-0 shadow-sm h-100 border-bottom border-4 border-warning rounded-4" data-status-filter="pending" role="button" tabindex="0">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted fw-normal mb-2 stat-label">Pending Requests</h6>
                                        <h3 class="fw-bold mb-0 text-warning stat-number"><?= esc($pendingItemsCount ?? 0) ?></h3>
                                    </div>
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm stat-icon" style="width: 54px; height: 54px;">
                                        <i class="ti ti-device-laptop fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card status-filter-card border-0 shadow-sm h-100 border-bottom border-4 border-success rounded-4" data-status-filter="approved" role="button" tabindex="0">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted fw-normal mb-2 stat-label">Approved Items</h6>
                                        <h3 class="fw-bold mb-0 text-success stat-number"><?= esc($approvedItemsCount ?? 0) ?></h3>
                                    </div>
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm stat-icon" style="width: 54px; height: 54px;">
                                        <i class="ti ti-check fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card status-filter-card border-0 shadow-sm h-100 border-bottom border-4 border-danger rounded-4" data-status-filter="rejected" role="button" tabindex="0">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted fw-normal mb-2 stat-label">Declined Items</h6>
                                        <h3 class="fw-bold mb-0 text-danger stat-number"><?= esc($rejectedItemsCount ?? 0) ?></h3>
                                    </div>
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm stat-icon" style="width: 54px; height: 54px;">
                                        <i class="ti ti-x fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card status-filter-card border-0 shadow-sm h-100 border-bottom border-4 border-secondary rounded-4" data-status-filter="archived" role="button" tabindex="0">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted fw-normal mb-2 stat-label">Archived</h6>
                                        <h3 class="fw-bold mb-0 text-secondary stat-number"><?= esc($archivedItemsCount ?? 0) ?></h3>
                                    </div>
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm stat-icon" style="width: 54px; height: 54px;">
                                        <i class="ti ti-archive fs-4"></i>
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
                $items = $items ?? [];
                $unregisterRequests = array_filter($items, function($item) {
                    return $item['status'] === 'staged';
                });
                ?>

                <?php if (!empty($unregisterRequests)): ?>
                    <?php $unregSkeletonRows = min(count($unregisterRequests), 8); ?>
                    <div class="card border-0 shadow-sm mb-4 rounded-4 border-top border-4 border-warning skeleton-wrapper">
                        <div class="card-body p-4">
                            <div class="skeleton skeleton-title w-50 mb-3" style="height: 22px;"></div>
                            <div class="skeleton skeleton-text w-75 mb-4"></div>
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
                                    <?php for($i=0; $i<$unregSkeletonRows; $i++): ?>
                                        <tr>
                                            <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                            <td><div class="skeleton rounded" style="width: 40px; height: 40px;"></div></td>
                                            <td><div class="skeleton skeleton-text w-100 mb-0"></div></td>
                                            <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                            <td><div class="skeleton skeleton-badge rounded-pill" style="width: 60px; height: 28px;"></div></td>
                                        </tr>
                                    <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
                                            <td data-label="Item Name" class="py-3 text-muted"><?= esc($req['brand_model'] ?? $req['name'] ?? $req['item_name'] ?? 'Unknown Item') ?></td>
                                            <td data-label="Serial Number" class="py-3 text-muted"><?= esc($req['serial_number'] ?? 'N/A') ?></td>
                                            <td data-label="Action" class="py-3">
                                                <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#viewUnregisterModal<?= $req['id'] ?>">VIEW</button>
                                            </td>
                                        </tr>

                                        <!-- Modal: Unregistration Request -->
                                        <div class="modal fade" id="viewUnregisterModal<?= $req['id'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content border-0 rounded-4 shadow">
                                                    <div class="modal-body p-4">
                                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                                            <h5 class="fw-bold mb-0">REPORT TYPE- <span class="text-warning-dark">REQUEST FOR UNREGISTRATION</span></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-7">
                                                                <p class="mb-2 fs-5"><?= esc($req['name'] ?? $req['item_name'] ?? 'Unknown Item') ?></p>
                                                                <?php if (!empty($req['category'])): ?>
                                                                    <p class="mb-3 text-muted"><?= esc($req['category']) ?></p>
                                                                <?php endif; ?>
                                                                <h6 class="fw-bold">Reason for Unregistration:</h6>
                                                                <div class="bg-light rounded-3 p-3 text-muted fst-italic">
                                                                    <?= !empty($req['reason']) ? esc($req['reason']) : 'No reason provided.' ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5 text-center mt-4 mt-md-0">
                                                                <?php if (!empty($req['photo'])): ?>
                                                                    <img src="<?= base_url('uploads/items/' . esc($req['photo'])) ?>" class="img-fluid rounded-3" alt="Item image">
                                                                <?php else: ?>
                                                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height:180px;">
                                                                        <i class="ti ti-photo fs-1 text-muted"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 justify-content-center pb-4 gap-2">
                                                        <a href="<?= base_url('admin/items/process/approve_unregister/' . $req['id']) ?>" class="btn px-4 py-2 rounded-pill fw-semibold text-white" style="background-color: #39cb7f; border: none;">APPROVE</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="table-container-fixed">
                    <div class="equipment-list-toolbar d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <h5 class="fw-bold text-dark mb-0"><span class="px-2"></span><i class="ti ti-device-laptop me-2 text-primary"></i> Master Equipment List</h5>
                        <div class="input-group shadow-sm" style="max-width: 350px;">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="ti ti-search"></i></span>
                            <input type="text" id="itemSearch" class="form-control border-start-0 ps-0" placeholder="Search by TUPT ID or Name...">
                        </div>
                    </div>

                    <style>
                        @media (min-width: 992px) {
                            #itemsTable col.col-id        { width: 60px; }
                            #itemsTable col.col-student   { width: 160px; }
                            #itemsTable col.col-number    { width: 130px; }
                            #itemsTable col.col-photo     { width: 80px; }
                            #itemsTable col.col-item      { width: 160px; }
                            #itemsTable col.col-serial    { width: 140px; }
                            #itemsTable col.col-date      { width: 140px; }
                            #itemsTable col.col-rfid      { width: 110px; }
                            #itemsTable col.col-status    { width: 110px; }
                            #itemsTable col.col-processed { width: 140px; }
                            #itemsTable col.col-actions   { width: 110px; }

                            #itemsTable th {
                                white-space: normal;
                                overflow-wrap: normal;
                            }

                            /* ---- Sticky ID (left) and Actions (right) columns ---- */
                            #itemsTable th.col-id,
                            #itemsTable td.col-id {
                                position: sticky;
                                left: 0;
                                z-index: 2;
                                background-color: #fff;
                                box-shadow: 2px 0 4px -2px rgba(0,0,0,0.08);
                            }

                            #itemsTable th.col-actions,
                            #itemsTable td.col-actions {
                                position: sticky;
                                right: 0;
                                z-index: 2;
                                background-color: #fff;
                                box-shadow: -2px 0 4px -2px rgba(0,0,0,0.08);
                            }

                            #itemsTable thead th {
                                background-color: #fff;
                                z-index: 3;
                            }
                        }

                        /* ---- Mobile: collapse into stacked cards ---- */
                        @media (max-width: 991.98px) {
                            #itemsTable, #itemsTable thead, #itemsTable tbody,
                            #itemsTable th, #itemsTable td, #itemsTable tr {
                                display: block;
                                width: 100% !important;
                                position: static !important;
                            }

                            #itemsTable {
                                table-layout: auto !important;
                            }

                            #itemsTable thead {
                                display: none;
                            }

                            #itemsTable tbody.real-wrapper tr {
                                margin-bottom: 1rem;
                                border: 1px solid #f0f0f0;
                                border-radius: 12px;
                                padding: 0.75rem 1rem;
                                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                            }

                            #itemsTable td {
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                text-align: right;
                                padding: 0.5rem 0;
                                border: none !important;
                                border-bottom: 1px solid #f6f6f6 !important;
                            }

                            #itemsTable td:last-child {
                                border-bottom: none !important;
                            }

                            #itemsTable td::before {
                                content: attr(data-label);
                                font-weight: 600;
                                color: #2a3547;
                                text-align: left;
                                margin-right: 1rem;
                                flex-shrink: 0;
                            }

                            #itemsTable td[data-label="Actions"] {
                                justify-content: flex-end;
                            }

                            #itemsTable td[data-label="Photo"] {
                                justify-content: flex-start;
                            }
                            #itemsTable td[data-label="Photo"]::before {
                                display: none;
                            }
                        }
                    </style>

                    <div class="table-scroll-wrapper" style="overflow-x: auto; position: relative;">
                        <table class="table align-middle mb-0 border-light" id="itemsTable" style="table-layout: fixed; width: 100%; min-width: 1250px;">
                            <colgroup>
                                <col class="col-id">
                                <col class="col-student">
                                <col class="col-number">
                                <col class="col-photo">
                                <col class="col-item">
                                <col class="col-serial">
                                <col class="col-date">
                                <col class="col-rfid">
                                <col class="col-status">
                                <col class="col-processed">
                                <col class="col-actions">
                            </colgroup>
                            <thead style="border-bottom: 2px solid #f0f0f0;">
                            <tr>
                                <th class="col-id border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">ID</th>
                                <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Student Name</th>
                                <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Student Number</th>
                                <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Photo</th>
                                <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Item Name</th>
                                <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Serial/Brand</th>
                                <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Registered On</th>
                                <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">RFID</th>
                                <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Status</th>
                                <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Processed By</th>
                                <th class="col-actions border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Actions</th>
                            </tr>
                            </thead>

                            <?php $itemsSkeletonRows = !empty($items) ? min(count($items), 8) : 1; ?>
                            <tbody class="skeleton-wrapper">
                            <?php for($i=0; $i<$itemsSkeletonRows; $i++): ?>
                                <tr>
                                    <td><div class="skeleton skeleton-text w-50 mb-0"></div></td>
                                    <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                    <td><div class="skeleton skeleton-text w-100 mb-0"></div></td>
                                    <td><div class="skeleton rounded-3" style="width: 45px; height: 45px;"></div></td>
                                    <td><div class="skeleton skeleton-text w-100 mb-0"></div></td>
                                    <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                    <td><div class="skeleton skeleton-text w-100 mb-0"></div></td>
                                    <td><div class="skeleton skeleton-badge rounded-1" style="width: 80px; height: 24px;"></div></td>
                                    <td><div class="skeleton skeleton-badge rounded-1" style="width: 80px; height: 24px;"></div></td>
                                    <td><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                    <td><div class="skeleton skeleton-badge rounded-2" style="width: 90px; height: 32px;"></div></td>
                                </tr>
                            <?php endfor; ?>
                            </tbody>

                            <tbody class="real-wrapper d-none">
                            <?php if (empty($items)) : ?>
                                <tr class="no-data-row">
                                    <td colspan="11" class="text-center py-5 text-muted">No items found in the system.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($items as $item) : ?>
                                    <tr style="border-bottom: 1px solid #f6f6f6;" data-status="<?= esc($item['status']) ?>">
                                        <td class="col-id py-3 text-muted" data-label="ID"><?= $item['id'] ?></td>
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
                                        <td data-label="Item Name" class="py-3 text-muted"><?= esc($item['brand_model'] ?? $item['name'] ?? $item['item_name'] ?? 'Unknown Item') ?></td>
                                        <td data-label="Serial/Brand" class="py-3 text-muted"><?= esc($item['serial_number'] ?? 'N/A') ?></td>
                                        <td data-label="Registered On" class="py-3 text-muted">
                                            <?php if (!empty($item['created_at'])): ?>
                                                <span class="d-block fw-semibold text-dark" style="font-size: 0.85rem;"><?= date('M d, Y', strtotime($item['created_at'])) ?></span>
                                                <span class="small text-muted"><?= date('h:i A', strtotime($item['created_at'])) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="RFID" class="py-3">
                                            <?php if (!empty($item['rfid'])): ?>
                                                <span class="badge rounded-1 shadow-sm px-3 py-2 fw-bold text-white" style="background-color: #1fc2ff; font-size: 0.8rem;" title="<?= esc($item['rfid']) ?>">Assigned</span>
                                            <?php else: ?>
                                                <span class="text-muted small">Not Assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Status" class="py-3">
                                            <?php
                                            $badgeStyle = 'background-color: #6c757d;';
                                            if ($item['status'] === 'approved') $badgeStyle = 'background-color: #39cb7f;';
                                            if ($item['status'] === 'pending') $badgeStyle = 'background-color: #ffae1f; color: #fff;';
                                            if ($item['status'] === 'rejected') $badgeStyle = 'background-color: #e46a76;';
                                            if ($item['status'] === 'missing') $badgeStyle = 'background-color: #fc4b6c;';
                                            if ($item['status'] === 'staged') $badgeStyle = 'background-color: #ffae1f; color: #fff;';
                                            if ($item['status'] === 'archived') $badgeStyle = 'background-color: #2a3547;';
                                            ?>
                                            <span class="badge rounded-1 shadow-sm px-3 py-2 fw-bold" style="<?= $badgeStyle ?> font-size: 0.8rem;">
                                                <?= ucfirst(esc($item['status'])) ?>
                                            </span>
                                        </td>
                                        <td data-label="Processed By" class="py-3 text-muted">
                                            <?php if ($item['status'] === 'archived' && !empty($item['unregistered_by_first_name'])): ?>
                                                <span class="fw-semibold"><?= esc($item['unregistered_by_first_name'] . ' ' . $item['unregistered_by_last_name']) ?></span>
                                            <?php elseif (!empty($item['approved_by_first_name'])): ?>
                                                <span class="fw-semibold"><?= esc($item['approved_by_first_name'] . ' ' . $item['approved_by_last_name']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small fst-italic">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="col-actions py-3" data-label="Actions">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary bg-white shadow-sm dropdown-toggle fw-semibold rounded-2 px-3 py-1" style="border-color: #5d87ff; color: #5d87ff;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu custom-dropdown shadow-lg border-0 rounded-4 p-2">
                                                    <?php if ($item['status'] === 'pending'): ?>
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
                                                    <?php elseif ($item['status'] === 'approved'): ?>
                                                        <li>
                                                            <button type="button" class="dropdown-item approve-item py-2" data-bs-toggle="modal" data-bs-target="#approveModal<?= $item['id'] ?>">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-box bg-light-success text-success me-3"><i class="ti-id-badge-2"></i></div>
                                                                    <span class="fw-semibold">Assign</span>
                                                                </div>
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider border-light my-2"></li>
                                                    <?php endif; ?>
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
<?php if (!empty($items)) : ?>
    <?php foreach ($items as $item) : ?>
        <div class="modal fade" id="approveModal<?= $item['id'] ?>" tabindex="-1" aria-labelledby="approveModalLabel<?= $item['id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title fw-bold text-success" id="approveModalLabel<?= $item['id'] ?>"><i class="ti ti-id-badge-2 me-2"></i> Approve & Link RFID</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="<?= base_url('admin/items/approveItem/' . $item['id']) ?>" method="POST" class="rfid-approval-form">

                        <?= csrf_field() ?>
                        <div class="modal-body text-start bg-light rounded-3 mx-3 p-4">
                            <p class="mb-3">Assigning an RFID card to a student</p>

                            <div class="mb-2">
                                <label for="rfid_<?= $item['id'] ?>" class="form-label fw-bold text-dark">Scan RFID Card</label>

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
    <script>window.scanApiUrl = "<?= base_url('admin/items/check-latest-scan') ?>";</script>
<?= $this->endSection() ?>