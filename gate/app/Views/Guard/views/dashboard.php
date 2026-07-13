<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Guard/layout/htmx' : 'Guard/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Scanner | Guard Portal<?= $this->endSection() ?>
<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/guard/guard-dashboard.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div id="dashboard-container" class="page-transition-container mt-5 pt-5 d-flex flex-column gap-3 page-slide-in">
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
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info p-3 shadow-sm border-0 d-flex align-items-center rounded-3 mb-4">
                <i class="ti ti-info-circle fs-4 me-2"></i><span class="fw-semibold"><?= session()->getFlashdata('info') ?></span>
            </div>
        <?php endif; ?>

        <div class="row g-4 pb-5 skeleton-wrapper">
            <div class="col-6 col-lg-5">
                <div class="card shadow-sm border-0 rounded-4 mb-4 h-auto">
                    <div class="card-body p-3 p-md-4">
                        <div class="skeleton skeleton-title w-50 mb-4" style="height: 18px;"></div>
                        <div class="row">
                            <div class="col-12 col-xl-5 text-center d-flex flex-column mt-2 mt-xl-0">
                                <div class="skeleton skeleton-text w-50 mb-2 text-start d-none d-xl-block"></div>
                                <div class="skeleton rounded-3 w-100 mb-3" style="height: 140px;"></div>
                                <div class="skeleton rounded-2 w-100" style="height: 40px;"></div>
                            </div>
                            <div class="col-12 col-xl-7 mt-3 mt-xl-0">
                                <div class="mb-3"><div class="skeleton skeleton-text w-50 mb-2"></div><div class="skeleton rounded-2 w-100" style="height: 38px;"></div></div>
                                <div class="mb-3"><div class="skeleton skeleton-text w-25 mb-2"></div><div class="skeleton rounded-2 w-100" style="height: 38px;"></div></div>
                                <div class="mb-4"><div class="skeleton skeleton-text w-50 mb-2"></div><div class="skeleton rounded-2 w-100" style="height: 38px;"></div></div>
                                <div class="skeleton rounded-2 w-100" style="height: 40px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-3 p-md-4 text-center text-xl-start">
                        <div class="skeleton skeleton-title w-75 mb-3" style="height: 18px;"></div>
                        <div class="skeleton w-25 mt-2 mb-0" style="height: 60px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-7">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <<div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div class="skeleton skeleton-title w-25 mb-0" style="height: 20px;"></div>
                        </div>
                        <div class="skeleton rounded-3 w-100 mb-4 d-flex align-items-center justify-content-between p-3" style="height: 60px; background: #1c2333;">
                            <div class="skeleton skeleton-badge rounded-pill" style="width: 90px; height: 24px;"></div>
                            <div class="skeleton rounded-2" style="width: 40px; height: 16px;"></div>
                        </div>
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
            <div class="col-6 col-lg-5">
                <div class="card shadow-sm border-0 rounded-4 mb-4 h-auto">
                    <div class="card-body p-3 p-md-4">
                        <h5 class="guard-card-title">LOG A VISITOR</h5>

                        <form id="visitorLogForm" action="<?= base_url('guard/log-visitor') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="rfid" value="<?= esc(session()->getFlashdata('visitor_rfid') ?? '') ?>">

                            <div class="row">
                                <div class="col-12 col-xl-5 text-center d-flex flex-column mt-2 mt-xl-0">
                                    <label class="form-label small fw-bold text-muted mb-1 text-start d-none d-xl-block">ID Photo:</label>

                                    <div class="image-placeholder-box mb-3 flex-grow-1" id="cameraBox">
                                        <i class="ti ti-id fs-1 text-muted opacity-50" id="cameraIcon" style="font-size: 5rem !important;"></i>
                                        <video id="webcamVideo" autoplay playsinline style="display:none; width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"></video>
                                        <img id="photoPreview" style="display:none; width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 2;" />
                                        <canvas id="photoCanvas" style="display:none;"></canvas>
                                    </div>

                                    <div class="d-flex flex-column gap-2 mt-auto">
                                        <button type="button" id="startCameraBtn" class="btn btn-blue w-100 py-2">
                                            <i class="ti ti-camera me-1"></i> START CAMERA
                                        </button>

                                        <!-- THE FIX: Grouped the Snap button and the new Switch Camera button -->
                                        <div class="d-flex gap-2 w-100">
                                            <button type="button" id="takePhotoBtn" class="btn btn-success flex-grow-1 py-2 shadow-sm" style="display:none;">
                                                SNAP PHOTO
                                            </button>
                                            <button type="button" id="switchCameraBtn" class="btn btn-outline-secondary py-2 shadow-sm px-3" style="display:none;" title="Switch Camera">
                                                <i class="ti ti-refresh"></i>
                                            </button>
                                        </div>

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
                                    <button type="submit" class="btn btn-blue w-100 py-2 mb-3 mb-xl-0">LOG VISITOR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-3 p-md-4 text-center text-xl-start">
                        <h5 class="guard-card-title mb-0 border-0">VISITOR SLOTS REMAINING:</h5>
                        <h1 class="display-3 fw-light text-muted mt-2 mb-0" style="font-family: 'Courier New', Courier, monospace; letter-spacing: -2px;">
                            <?= esc($slotsAvailable ?? 0) ?>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-7">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-3 p-md-4">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <h5 class="guard-card-title mb-0">SCANNER</h5>
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
                        </form>

                        <?php
                        $scannedData = session()->getFlashdata('scanned_items') ?? session()->getFlashdata('scanned_item');
                        $scannedStudent = session()->getFlashdata('scanned_student');

                        $scannedItems = [];
                        if (!empty($scannedData)) {
                            if (isset($scannedData['brand_model']) || isset($scannedData['name'])) {
                                $scannedItems = [$scannedData];
                            } else {
                                $scannedItems = $scannedData;
                            }
                        }
                        ?>

                        <?php if (!empty($scannedItems) && $scannedStudent): ?>

                            <div class="info-block py-3 px-4 mb-2 shadow-sm text-uppercase fw-bold bg-primary-subtle text-primary border border-primary-subtle rounded-3 d-flex align-items-center">
                                <i class="ti ti-user me-2 fs-4"></i> <?= esc($scannedStudent['first_name'] . ' ' . $scannedStudent['last_name']) ?>
                            </div>
                            <div class="info-block py-2 px-4 mb-4 shadow-sm text-uppercase font-monospace bg-light rounded-3 text-muted">
                                <?= esc($scannedStudent['student_number'] ?? 'NO ID') ?>
                            </div>

                            <?php if (count($scannedItems) === 1): ?>
                                <?php $item = $scannedItems[0]; ?>
                                <div class="row align-items-center mb-4">
                                    <div class="col-md-6 order-2 order-md-1 mt-4 mt-md-0">
                                        <?php
                                        // SINGLE ITEM LOGIC
                                        $isTimeIn = (isset($item['action_taken']) && $item['action_taken'] === 'TIME-IN');
                                        ?>
                                        <div class="mb-3">
                                            <?php if ($isTimeIn): ?>
                                                <span class="badge bg-success text-white fw-bold px-3 py-2 fs-4 rounded-3 shadow-sm d-inline-flex align-items-center">
                                                    <i class="ti ti-login me-2 fs-5"></i> TIME IN
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary text-white fw-bold px-3 py-2 fs-4 rounded-3 shadow-sm d-inline-flex align-items-center">
                                                    <i class="ti ti-logout me-2 fs-5"></i> TIME OUT
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h4 class="fw-bold text-dark mb-4 fs-4 text-uppercase"><?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?></h4>
                                        <p class="text-muted fw-semibold mb-2">TYPE: <span class="fw-normal text-dark"><?= esc($item['category'] ?? 'N/A') ?></span></p>
                                        <p class="text-muted fw-semibold mb-2">SN: <span class="font-monospace fw-normal text-dark"><?= esc($item['serial_number'] ?? 'N/A') ?></span></p>
                                        <p class="text-muted fw-semibold mb-0">STATUS: <span class="fw-normal text-dark"><?= esc($item['status'] ?? 'UNKNOWN') ?></span></p>
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

                            <?php else: ?>
                                <h6 class="fw-bold text-muted mb-3 d-flex align-items-center">
                                    <i class="ti ti-devices me-2"></i> SCANNED ITEMS (<?= count($scannedItems) ?>)
                                </h6>

                                <div class="d-flex flex-column gap-3 pe-2" style="max-height: 380px; overflow-y: auto; overflow-x: hidden;">
                                    <?php foreach ($scannedItems as $item): ?>
                                        <div class="row align-items-center p-3 border rounded-3 bg-light shadow-sm mx-0">
                                            <div class="col-md-7 order-2 order-md-1 mt-3 mt-md-0">
                                                <?php
                                                // MULTIPLE ITEM LOGIC
                                                $isTimeIn = (isset($item['action_taken']) && $item['action_taken'] === 'TIME-IN');
                                                ?>
                                                <div class="mb-2">
                                                    <?php if ($isTimeIn): ?>
                                                        <span class="badge bg-success text-white fw-bold px-2 py-1 fs-2 rounded-2 shadow-sm">
                                                            <i class="ti ti-login me-1"></i> TIME IN
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary text-white fw-bold px-2 py-1 fs-2 rounded-2 shadow-sm">
                                                            <i class="ti ti-logout me-1"></i> TIME OUT
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-2 text-uppercase"><?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?></h6>
                                                <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem;">SN: <span class="font-monospace fw-normal text-dark"><?= esc($item['serial_number'] ?? 'N/A') ?></span></p>
                                                <p class="text-muted fw-semibold mb-0" style="font-size: 0.8rem;">TYPE: <span class="fw-normal text-dark"><?= esc($item['category'] ?? 'N/A') ?></span></p>
                                            </div>
                                            <div class="col-md-5 order-1 order-md-2">
                                                <div class="image-placeholder-box p-2 h-100 bg-white border" style="min-height: 100px; border-radius: 8px;">
                                                    <?php if (!empty($item['photo'])): ?>
                                                        <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>" alt="Item" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: contain;">
                                                    <?php else: ?>
                                                        <i class="ti ti-device-laptop text-muted opacity-25 d-flex justify-content-center align-items-center h-100" style="font-size: 2.5rem;"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        // SKELETON LOADER (with HTMX support)
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }

        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // ==========================================
        // 1. WEBCAM CAPTURE LOGIC (Front/Rear Toggle Added)
        // ==========================================
        const startCameraBtn = document.getElementById('startCameraBtn');
        const takePhotoBtn = document.getElementById('takePhotoBtn');
        const retakePhotoBtn = document.getElementById('retakePhotoBtn');
        const switchCameraBtn = document.getElementById('switchCameraBtn'); // New switch button
        const webcamVideo = document.getElementById('webcamVideo');
        const photoPreview = document.getElementById('photoPreview');
        const photoCanvas = document.getElementById('photoCanvas');
        const cameraIcon = document.getElementById('cameraIcon');
        const webcamPhotoInput = document.getElementById('webcamPhotoInput');
        const manualPhotoInput = document.getElementById('manualPhotoInput');

        let videoStream = null;
        let currentFacingMode = 'user'; // Defaults to front camera. Use 'environment' to default to rear.

        async function initCamera(facingMode) {
            // 1. If a camera is already running, turn it off before switching
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }

            try {
                // 2. Request the specific camera (front or back)
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: facingMode }
                });

                webcamVideo.srcObject = videoStream;
                webcamVideo.style.display = 'block';
                cameraIcon.style.display = 'none';
                photoPreview.style.display = 'none';

                // 3. Update UI buttons
                startCameraBtn.style.display = 'none';
                takePhotoBtn.style.display = 'block';
                switchCameraBtn.style.display = 'block'; // Show switch button
                retakePhotoBtn.style.display = 'none';
            } catch (err) {
                alert("Camera access denied or not available. Please check your browser permissions.");
                console.error(err);
            }
        }

        startCameraBtn?.addEventListener('click', () => {
            initCamera(currentFacingMode);
        });

        switchCameraBtn?.addEventListener('click', () => {
            // Toggle the mode and re-initialize the camera
            currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
            initCamera(currentFacingMode);
        });

        takePhotoBtn?.addEventListener('click', () => {
            const context = photoCanvas.getContext('2d');
            photoCanvas.width = webcamVideo.videoWidth;
            photoCanvas.height = webcamVideo.videoHeight;
            context.drawImage(webcamVideo, 0, 0, photoCanvas.width, photoCanvas.height);

            const imageData = photoCanvas.toDataURL('image/png');
            webcamPhotoInput.value = imageData;
            photoPreview.src = imageData;

            webcamVideo.style.display = 'none';
            photoPreview.style.display = 'block';

            takePhotoBtn.style.display = 'none';
            switchCameraBtn.style.display = 'none'; // Hide switch button when viewing photo
            retakePhotoBtn.style.display = 'block';
        });

        retakePhotoBtn?.addEventListener('click', () => {
            webcamPhotoInput.value = '';
            webcamVideo.style.display = 'block';
            photoPreview.style.display = 'none';

            takePhotoBtn.style.display = 'block';
            switchCameraBtn.style.display = 'block'; // Show switch button again
            retakePhotoBtn.style.display = 'none';
        });

        manualPhotoInput?.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    photoPreview.src = event.target.result;
                    if (videoStream) {
                        videoStream.getTracks().forEach(track => track.stop());
                    }
                    photoPreview.style.display = 'block';
                    cameraIcon.style.display = 'none';
                    webcamVideo.style.display = 'none';

                    startCameraBtn.style.display = 'none';
                    takePhotoBtn.style.display = 'none';
                    switchCameraBtn.style.display = 'none';
                    retakePhotoBtn.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // ==========================================
        // 2. IoT BRIDGE (ESP32 Polling)
        // ==========================================
        const rfidInput = document.getElementById('hiddenRfidInput');
        const hiddenForm = document.getElementById('hiddenScanForm');
        const debugOutput = document.getElementById('debugOutput');
        const debugStatus = document.getElementById('debugStatus');

        if (rfidInput && hiddenForm) {
            const checkUrl = hiddenForm.getAttribute('data-check-url');
            setInterval(() => {
                fetch(checkUrl, {
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            if (debugOutput && debugStatus) {
                                debugOutput.textContent = `Scanned EPC: ${data.epc}`;
                                debugStatus.textContent = "PROCESSING...";
                                debugStatus.className = "text-info fw-bold small";
                            }
                            rfidInput.value = data.epc;
                            hiddenForm.submit();
                        }
                    })
                    .catch(err => {});
            }, 1000);
        }

        // ==========================================
        // 3. PASSWORD TOGGLE
        // ==========================================
        document.getElementById('togglePassword')?.addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        });
        // ==========================================
        // 4. MANDATORY PHOTO VALIDATION
        // ==========================================
        document.getElementById('visitorLogForm')?.addEventListener('submit', function(e) {
            const webcamInput = document.getElementById('webcamPhotoInput').value;
            const manualInput = document.getElementById('manualPhotoInput').files.length;

            if (!webcamInput && manualInput === 0) {
                e.preventDefault(); // Stop the form from submitting
                alert("SECURITY ALERT: An ID Photo is mandatory. Please snap a picture or upload an image before logging this visitor.");
            }
        });
    </script>
<?= $this->endSection() ?>