<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Guard/layout/htmx' : 'Guard/layout/main';

$referrer = service('request')->getHeaderLine('HX-Current-URL');
$slideIn = (strpos($referrer, 'profile') !== false);

$visitorRfid = session()->getFlashdata('visitor_rfid') ?? '';
$autoOpenVisitorModal = !empty($visitorRfid);
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Scanner | Guard Portal<?= $this->endSection() ?>
<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/guard/guard-dashboard.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div id="dashboard-container" class="page-transition-container mt-5 pt-5 d-flex flex-column gap-3 <?= $slideIn ? 'page-slide-in' : '' ?>">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success p-3 shadow-sm border-0 d-flex align-items-center rounded-3 mb-4">
                <i class="ti ti-check-circle fs-4 me-2"></i><span class="fw-semibold"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger p-3 shadow-sm border-0 d-flex align-items-center rounded-3 mb-4">
                <i class="ti ti-alert-triangle fs-4 me-2"></i><span class="fw-semibold"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('warning')): ?>
            <div class="alert alert-warning p-3 shadow-sm border-0 d-flex align-items-center rounded-3 mb-4">
                <i class="ti ti-alert-triangle fs-4 me-2"></i><span class="fw-semibold"><?= session()->getFlashdata('warning') ?></span>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info p-3 shadow-sm border-0 d-flex align-items-center rounded-3 mb-4">
                <i class="ti ti-info-circle fs-4 me-2"></i><span class="fw-semibold"><?= session()->getFlashdata('info') ?></span>
            </div>
        <?php endif; ?>

        <div class="row g-4 pb-5 skeleton-wrapper">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div class="skeleton skeleton-title w-25 mb-0" style="height: 20px;"></div>
                        </div>
                        <div class="skeleton rounded-3 w-100 mb-4" style="height: 60px;"></div>
                        <div class="d-flex flex-column align-items-center justify-content-center py-4">
                            <div class="skeleton rounded-circle mb-3" style="width: 48px; height: 48px;"></div>
                            <div class="skeleton skeleton-text w-50 mb-2" style="height: 20px;"></div>
                            <div class="skeleton skeleton-text w-75 mb-0" style="height: 14px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 pb-5 real-wrapper d-none">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-3 p-md-4">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <h5 class="guard-card-title mb-0">SCANNER</h5>
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted small fw-semibold text-uppercase">
                                    Visitor slots left:
                                    <span class="fw-bold text-dark"><?= esc($slotsAvailable ?? 0) ?></span>
                                </span>
                                <button type="button" class="btn btn-blue btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#logVisitorModal">
                                    <i class="ti ti-user-plus me-1"></i> Log a Visitor
                                </button>
                            </div>
                        </div>

                        <form id="hiddenScanForm" action="<?= base_url('guard/check-in') ?>" method="POST" data-check-url="<?= base_url('guard/check-latest-scan') ?>">
                            <?= csrf_field() ?>
                            <input type="text" id="hiddenRfidInput" name="rfid" autofocus autocomplete="off" style="position: absolute; opacity: 0; top: -1000px; left: -1000px; width: 1px; height: 1px; z-index: -9999;">

                            <div class="debug-box p-3 mb-4 d-flex justify-content-between align-items-center" id="debugBox">
                                <div class="d-flex align-items-center w-100 me-2">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fs-3 fw-normal text-capitalize d-flex align-items-center">
                                        <span class="spinner-grow spinner-grow-sm me-2 bg-success" style="width: 8px; height: 8px;"></span> Listening
                                    </span>
                                </div>
                                <span id="debugStatus" class="text-warning fw-bold small">IDLE</span>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted mb-1">
                                    Manual Entry <span class="fw-normal fst-italic">(for testing without a physical scanner)</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" id="manualRfidInput" class="form-control input-grey" placeholder="Type or paste an RFID/EPC value...">
                                    <button type="button" id="manualScanBtn" class="btn btn-outline-primary">
                                        <i class="ti ti-scan me-1"></i> Simulate Scan
                                    </button>
                                </div>
                            </div>
                        </form>

                        <?php
                        $scannedData = session()->getFlashdata('scanned_items') ?? session()->getFlashdata('scanned_item');
                        $scannedStudent = session()->getFlashdata('scanned_student');
                        $departedVisitor = session()->getFlashdata('departed_visitor');

                        $scannedItems = [];
                        if (!empty($scannedData)) {
                            if (isset($scannedData['brand_model']) || isset($scannedData['name'])) {
                                $scannedItems = [$scannedData];
                            } else {
                                $scannedItems = $scannedData;
                            }
                        }

                        // Bring missing items to the front so they're never buried in a batch
                        usort($scannedItems, function($a, $b) {
                            $aMissing = (($a['status'] ?? '') === 'missing') ? 0 : 1;
                            $bMissing = (($b['status'] ?? '') === 'missing') ? 0 : 1;
                            return $aMissing <=> $bMissing;
                        });
                        ?>

                        <?php if (!empty($scannedItems) && $scannedStudent): ?>

                            <?php if (count($scannedItems) === 1): ?>
                                <?php $item = $scannedItems[0]; ?>
                                <?php $itemStudentPic = $item['student_profile_pic'] ?? $scannedStudent['profile_pic'] ?? 'default.png'; ?>
                                <?php $isMissingSingle = (($item['status'] ?? '') === 'missing'); ?>
                                <div class="border rounded-3 shadow-sm p-3 p-md-4 mb-4 <?= $isMissingSingle ? 'bg-danger-subtle border-danger' : '' ?>">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 order-2 order-md-1 mt-4 mt-md-0">
                                            <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                                                <img src="<?= base_url('uploads/profiles/' . esc($itemStudentPic)) ?>" alt="Student" class="rounded-circle me-3 border border-2 border-light shadow-sm" style="width: 56px; height: 56px; object-fit: cover; flex-shrink: 0;">
                                                <div class="d-flex flex-column">
                                                    <span class="text-uppercase fw-bold text-primary fs-3"><?= esc(trim(($item['student_first_name'] ?? '') . ' ' . ($item['student_last_name'] ?? '')) ?: ($scannedStudent['first_name'] . ' ' . $scannedStudent['last_name'])) ?></span>
                                                    <span class="text-uppercase text-muted mt-1" style="font-size: 0.95rem;"><?= esc($item['student_number'] ?? $scannedStudent['student_number'] ?? 'NO ID') ?></span>
                                                </div>
                                            </div>
                                            <?php
                                            $isTimeIn = (isset($item['action_taken']) && $item['action_taken'] === 'TIME-IN');
                                            ?>
                                            <div class="mb-3">
                                                <?php if ($isTimeIn): ?>
                                                    <span class="badge bg-success text-white fw-bold px-3 py-2 fs-3 rounded-3 shadow-sm d-inline-flex align-items-center">
                                                <i class="ti ti-login me-2 fs-4"></i> TIME IN
                                            </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary text-white fw-bold px-3 py-2 fs-3 rounded-3 shadow-sm d-inline-flex align-items-center">
                                                <i class="ti ti-logout me-2 fs-4"></i> TIME OUT
                                            </span>
                                                <?php endif; ?>
                                            </div>
                                            <h4 class="fw-bold text-dark mb-3 text-uppercase" style="font-size: 1.75rem;"><?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?></h4>

                                            <?php
                                            $itemStatus = $item['status'] ?? 'unknown';
                                            $statusAlerts = [
                                                    'approved' => ['class' => 'success',   'icon' => 'ti-check',          'text' => 'Item is Approved'],
                                                    'pending'  => ['class' => 'warning',   'icon' => 'ti-clock',          'text' => 'Registration Still Pending Approval'],
                                                    'rejected' => ['class' => 'danger',    'icon' => 'ti-x',              'text' => 'Registration was Rejected'],
                                                    'missing'  => ['class' => 'danger',    'icon' => 'ti-alert-triangle', 'text' => 'Reported Missing — Verify with Admin'],
                                                    'staged'   => ['class' => 'warning',   'icon' => 'ti-alert-triangle', 'text' => 'Unregistration Pending — Verify with Admin'],
                                                    'archived' => ['class' => 'secondary', 'icon' => 'ti-archive',        'text' => 'Item is Archived / Unregistered'],
                                            ];
                                            $alertInfo = $statusAlerts[$itemStatus] ?? ['class' => 'secondary', 'icon' => 'ti-help-circle', 'text' => 'Unknown Status'];
                                            ?>
                                            <div class="alert alert-<?= $alertInfo['class'] ?> py-3 px-3 mb-3 d-flex align-items-center shadow-sm">
                                                <i class="ti <?= $alertInfo['icon'] ?> me-2 fs-4"></i>
                                                <span class="fw-bold text-uppercase" style="font-size: 1.05rem;"><?= esc($alertInfo['text']) ?></span>
                                            </div>

                                            <p class="text-muted fw-semibold mb-2" style="font-size: 1.1rem;">TYPE: <span class="fw-normal text-dark"><?= esc($item['category'] ?? 'N/A') ?></span></p>
                                            <p class="text-muted fw-semibold mb-0" style="font-size: 1.1rem;">SN: <span class="font-monospace fw-normal text-dark"><?= esc($item['serial_number'] ?? 'N/A') ?></span></p>
                                        </div>
                                        <div class="col-md-6 order-1 order-md-2">
                                            <div class="image-placeholder-box p-3 h-100 bg-white border" style="min-height: 200px; border-radius: 12px;">
                                                <?php if (!empty($item['photo'])): ?>
                                                    <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>" alt="Item" class="img-fluid rounded shadow-sm" style="max-height: 100%; object-fit: contain;">
                                                <?php else: ?>
                                                    <i class="ti ti-device-laptop text-muted opacity-25 d-flex justify-content-center align-items-center h-100" style="font-size: 5rem;"></i>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php else: ?>
                                <h5 class="fw-bold text-muted mb-3 d-flex align-items-center">
                                    <i class="ti ti-devices me-2"></i> SCANNED ITEMS (<?= count($scannedItems) ?>)
                                </h5>

                                <div class="d-flex flex-column gap-3 pe-2" style="max-height: 520px; overflow-y: auto; overflow-x: hidden;">
                                    <?php foreach ($scannedItems as $item): ?>
                                        <?php $isMissingCard = (($item['status'] ?? '') === 'missing'); ?>
                                        <div class="row align-items-center p-3 border rounded-3 <?= $isMissingCard ? 'bg-danger-subtle border-danger' : 'bg-light' ?> shadow-sm mx-0">
                                            <div class="col-md-7 order-2 order-md-1 mt-3 mt-md-0">
                                                <?php
                                                // MULTIPLE ITEM LOGIC
                                                $isTimeIn = (isset($item['action_taken']) && $item['action_taken'] === 'TIME-IN');
                                                $itemStudentPic = $item['student_profile_pic'] ?? 'default.png';
                                                ?>
                                                <div class="d-flex align-items-center pb-2 mb-2 border-bottom">
                                                    <img src="<?= base_url('uploads/profiles/' . esc($itemStudentPic)) ?>" alt="Student" class="rounded-circle me-2 border border-2 border-light shadow-sm" style="width: 44px; height: 44px; object-fit: cover; flex-shrink: 0;">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-uppercase fw-bold text-primary" style="font-size: 1.05rem;"><?= esc(trim(($item['student_first_name'] ?? '') . ' ' . ($item['student_last_name'] ?? '')) ?: 'Unknown Student') ?></span>
                                                        <span class="text-uppercase text-muted" style="font-size: 0.85rem;"><?= esc($item['student_number'] ?? 'NO ID') ?></span>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <?php if ($isTimeIn): ?>
                                                        <span class="badge bg-success text-white fw-bold px-3 py-2 fs-4 rounded-2 shadow-sm">
                                                            <i class="ti ti-login me-1"></i> TIME IN
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary text-white fw-bold px-3 py-2 fs-4 rounded-2 shadow-sm">
                                                            <i class="ti ti-logout me-1"></i> TIME OUT
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-2 text-uppercase" style="font-size: 1.3rem;"><?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?></h6>

                                                <?php
                                                $itemStatus = $item['status'] ?? 'unknown';
                                                $statusAlerts = [
                                                        'approved' => ['class' => 'success',   'icon' => 'ti-check',          'text' => 'Approved'],
                                                        'pending'  => ['class' => 'warning',   'icon' => 'ti-clock',          'text' => 'Pending Approval'],
                                                        'rejected' => ['class' => 'danger',    'icon' => 'ti-x',              'text' => 'Rejected'],
                                                        'missing'  => ['class' => 'danger',    'icon' => 'ti-alert-triangle', 'text' => 'Reported Missing'],
                                                        'staged'   => ['class' => 'warning',   'icon' => 'ti-alert-triangle', 'text' => 'Unregistration Pending'],
                                                        'archived' => ['class' => 'secondary', 'icon' => 'ti-archive',        'text' => 'Archived'],
                                                ];
                                                $alertInfo = $statusAlerts[$itemStatus] ?? ['class' => 'secondary', 'icon' => 'ti-help-circle', 'text' => 'Unknown'];
                                                ?>
                                                <div class="alert alert-<?= $alertInfo['class'] ?> py-2 px-3 mb-2 d-flex align-items-center shadow-sm" style="font-size: 0.95rem;">
                                                    <i class="ti <?= $alertInfo['icon'] ?> me-2"></i>
                                                    <span class="fw-bold text-uppercase"><?= esc($alertInfo['text']) ?></span>
                                                </div>

                                                <p class="text-muted fw-semibold mb-1" style="font-size: 1rem;">SN: <span class="font-monospace fw-normal text-dark"><?= esc($item['serial_number'] ?? 'N/A') ?></span></p>
                                                <p class="text-muted fw-semibold mb-0" style="font-size: 1rem;">TYPE: <span class="fw-normal text-dark"><?= esc($item['category'] ?? 'N/A') ?></span></p>
                                            </div>
                                            <div class="col-md-5 order-1 order-md-2">
                                                <div class="image-placeholder-box p-2 h-100 bg-white border" style="min-height: 130px; border-radius: 8px;">
                                                    <?php if (!empty($item['photo'])): ?>
                                                        <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>" alt="Item" class="img-fluid rounded" style="max-height: 130px; width: 100%; object-fit: contain;">
                                                    <?php else: ?>
                                                        <i class="ti ti-device-laptop text-muted opacity-25 d-flex justify-content-center align-items-center h-100" style="font-size: 2.5rem;"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php $noItemStudents = session()->getFlashdata('no_item_students') ?? []; ?>
                            <?php foreach ($noItemStudents as $entry): ?>
                                <?php $nStudent = $entry['student']; $nPassAction = $entry['pass_action']; ?>
                                <div class="border rounded-3 shadow-sm p-3 p-md-4 mb-4">
                                    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= base_url('uploads/profiles/' . esc($nStudent['profile_pic'] ?? 'default.png')) ?>"
                                                 alt="Student" class="rounded-circle me-3 border border-2 border-light shadow-sm"
                                                 style="width: 48px; height: 48px; object-fit: cover; flex-shrink: 0;">
                                            <div class="d-flex flex-column">
                                                <span class="text-uppercase fw-bold text-primary">
                                                    <?= esc(trim(($nStudent['first_name'] ?? '') . ' ' . ($nStudent['last_name'] ?? ''))) ?>
                                                </span>
                                                <span class="text-uppercase text-muted small mt-1">
                                                    <?= esc($nStudent['student_number'] ?? 'NO ID') ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php if ($nPassAction): ?>
                                            <span class="badge <?= $nPassAction === 'TIME-IN' ? 'bg-success' : 'bg-secondary' ?> fs-6 px-3 py-2">
                                                <?= $nPassAction === 'TIME-IN' ? 'TIME IN' : 'TIME OUT' ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="alert alert-secondary py-2 px-3 mb-0 d-flex align-items-center shadow-sm">
                                        <i class="ti ti-info-circle me-2"></i>
                                        <span class="fw-bold text-uppercase small">No items marked as bringing for this tag</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        <?php elseif ($scannedStudent): ?>
                            <?php $passAction = session()->getFlashdata('pass_action'); ?>
                            <div class="border rounded-3 shadow-sm p-3 p-md-4 mb-4">
                                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= base_url('uploads/profiles/' . esc($scannedStudent['profile_pic'] ?? 'default.png')) ?>"
                                             alt="Student" class="rounded-circle me-3 border border-2 border-light shadow-sm"
                                             style="width: 48px; height: 48px; object-fit: cover; flex-shrink: 0;">
                                        <div class="d-flex flex-column">
                                            <span class="text-uppercase fw-bold text-primary">
                                                <?= esc(trim(($scannedStudent['first_name'] ?? '') . ' ' . ($scannedStudent['last_name'] ?? ''))) ?>
                                            </span>
                                            <span class="text-uppercase text-muted small mt-1">
                                                <?= esc($scannedStudent['student_number'] ?? 'NO ID') ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php if ($passAction): ?>
                                        <span class="badge <?= $passAction === 'TIME-IN' ? 'bg-success' : 'bg-secondary' ?> fs-6 px-3 py-2">
                                            <?= $passAction === 'TIME-IN' ? 'TIME IN' : 'TIME OUT' ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="alert alert-secondary py-2 px-3 mb-0 d-flex align-items-center shadow-sm">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <span class="fw-bold text-uppercase small">No items marked as bringing for this tag</span>
                                </div>
                            </div>

                        <?php elseif ($departedVisitor): ?>

                            <div class="row align-items-center mb-4">
                                <div class="col-md-6 order-2 order-md-1 mt-4 mt-md-0">
                                    <div class="mb-3">
                                        <span class="badge bg-secondary text-white fw-bold px-3 py-2 fs-4 rounded-3 shadow-sm d-inline-flex align-items-center">
                                            <i class="ti ti-logout me-2 fs-5"></i> VISITOR DEPARTED
                                        </span>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-4 fs-4 text-uppercase"><?= esc($departedVisitor['name'] ?? 'Visitor') ?></h4>
                                </div>
                                <div class="col-md-6 order-1 order-md-2">
                                    <div class="image-placeholder-box p-3 h-100 bg-white border" style="min-height: 200px; border-radius: 12px;">
                                        <?php if (!empty($departedVisitor['photo'])): ?>
                                            <img src="<?= base_url('uploads/visitor_ids/' . esc($departedVisitor['photo'])) ?>" alt="Visitor ID" class="img-fluid rounded shadow-sm" style="max-height: 100%; object-fit: contain;">
                                        <?php else: ?>
                                            <i class="ti ti-id text-muted opacity-25 d-flex justify-content-center align-items-center h-100" style="font-size: 5rem;"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="d-flex flex-column align-items-center justify-content-center h-75 text-muted opacity-50 py-5">
                                <i class="ti ti-scan fs-1 mb-3" style="font-size: 2.5rem !important; color: #c7cad4;"></i>
                                <h3 class="fw-bold text-center text-dark">Ready for next scan</h3>
                                <p class="text-center">Scan an RFID sticker to display details.</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LOG A VISITOR MODAL -->
    <div class="modal fade" id="logVisitorModal" tabindex="-1" aria-labelledby="logVisitorModalLabel" aria-hidden="true"<?= $autoOpenVisitorModal ? ' data-auto-open="1"' : '' ?>>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="guard-card-title mb-0" id="logVisitorModalLabel">LOG A VISITOR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-4">
                    <form id="visitorLogForm" action="<?= base_url('guard/log-visitor') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="rfid" value="<?= esc($visitorRfid) ?>">

                        <div class="row">
                            <div class="col-12 col-xl-5 text-center d-flex flex-column mt-2 mt-xl-0">
                                <label class="form-label small fw-bold text-muted mb-1 text-start d-none d-xl-block">ID Photo:</label>

                                <div class="image-placeholder-box mb-3 flex-grow-1" id="cameraBox">
                                    <i class="ti ti-id fs-1 text-muted opacity-50" id="cameraIcon" style="font-size: 5rem !important;"></i>
                                    <video id="webcamVideo" autoplay playsinline style="display:none; width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"></video>
                                    <img id="photoPreview" style="display:none; width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 2;" />
                                    <canvas id="photoCanvas" style="display:none;"></canvas>

                                    <button type="button" id="switchCameraBtn" class="camera-switch-overlay" style="display:none;" title="Switch Camera">
                                        <i class="ti ti-refresh"></i>
                                    </button>
                                </div>

                                <div class="d-flex flex-column gap-2 mt-auto">
                                    <button type="button" id="startCameraBtn" class="btn btn-blue w-100 py-2">
                                        <i class="ti ti-camera me-1"></i> START CAMERA
                                    </button>

                                    <button type="button" id="takePhotoBtn" class="btn btn-success w-100 py-2 shadow-sm" style="display:none;">
                                        SNAP PHOTO
                                    </button>

                                    <button type="button" id="retakePhotoBtn" class="btn btn-warning w-100 py-2" style="display:none;">
                                        <i class="ti ti-reload me-1"></i> RETAKE
                                    </button>
                                </div>
                                <input type="hidden" name="webcam_photo" id="webcamPhotoInput">
                            </div>

                            <div class="col-12 col-xl-7">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted mb-1">Visitor Name:</label>
                                    <input type="text" class="form-control input-grey" name="visitor_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted mb-1">Purpose:</label>
                                    <input type="text" class="form-control input-grey" name="purpose" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted mb-1">Items: <span class="fw-normal fst-italic">(Optional)</span></label>
                                    <input type="text" class="form-control input-grey" name="items" placeholder="">
                                </div>
                                <button type="submit" class="btn btn-blue w-100 py-2">LOG VISITOR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->endSection() ?>