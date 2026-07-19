<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';

$referrer = service('request')->getHeaderLine('HX-Current-URL');
$slideIn = (strpos($referrer, 'item-registration') !== false || strpos($referrer, 'profile') !== false);
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Dashboard | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?php if (!empty($showTermsModal)): ?>
    <div class="modal fade" id="termsModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="ti ti-shield-lock me-2"></i> Data Privacy Notice</h5>
                </div>
                <div class="modal-body px-4 pb-2">
                    <p class="text-muted mb-3">
                        Before using the GATE system, please review how your personal data is collected and used.
                    </p>
                    <div class="bg-light rounded-3 p-3 mb-3" style="max-height: 280px; overflow-y: auto; font-size: 0.9rem;">
                        <p><strong>Data We Collect:</strong> Your name, student number, department, year level, email address, profile photo, and equipment/item details you register (brand, model, serial number, and photos).</p>
                        <p><strong>Purpose:</strong> This information is used solely to verify your identity at the campus gate, process equipment registration requests, and maintain accurate entry/exit logs for campus security.</p>
                        <p><strong>Access:</strong> Your data is accessible only to authorized GATE administrators and security personnel for the purposes described above.</p>
                        <p><strong>Retention:</strong> Your data is retained for as long as you remain an active student, and in accordance with the university's records retention policy.</p>
                        <p class="mb-0">By clicking "I Accept" below, you acknowledge that you have read and understood this notice, and you consent to the collection and processing of your personal data as described, in accordance with the Data Privacy Act of 2012 (RA 10173).</p>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="termsNeverShowAgain" checked>
                        <label class="form-check-label small text-muted" for="termsNeverShowAgain">
                            Don't show this again
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-2">
                    <button type="button" id="btnAcceptTerms" class="btn btn-primary fw-bold px-4 rounded-3 shadow-sm">
                        I Accept
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <div id="dashboard-container" class="page-transition-container pt-5 mt-4 <?= $slideIn ? 'page-slide-in' : '' ?>">
        <div class="row mt-2">
            <div class="col-12">

                <h4 class="fw-semibold mb-3 d-none d-md-block">Student Dashboard</h4>

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show p-3 rounded-3 shadow-sm mb-4 d-flex align-items-center" role="alert">
                        <i class="ti ti-circle-check fs-5 me-2"></i>
                        <div><?= session()->getFlashdata('success') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show p-3 rounded-3 shadow-sm mb-4 d-flex align-items-center" role="alert">
                        <i class="ti ti-alert-circle fs-5 me-2"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="skeleton-wrapper">
                    <div class="digital-id-card mb-4 rounded-4 overflow-hidden border border-light">
                        <div class="skeleton" style="height: 160px; border-radius: 15px 15px 0 0;"></div>
                        <div class="digital-id-body pb-4 bg-white rounded-bottom shadow-sm text-center position-relative">
                            <div class="skeleton rounded-circle border border-4 border-white shadow-sm mx-auto" style="width: 120px; height: 120px; margin-top: -60px; position: relative; z-index: 2;"></div>
                            <div class="pt-3 d-flex flex-column align-items-center">
                                <div class="skeleton skeleton-title w-50 mb-2"></div>
                                <div class="skeleton skeleton-text w-25 mb-3"></div>
                                <div class="row bg-light rounded-3 p-3 mx-2 mx-md-4 w-auto">
                                    <div class="col-6"><div class="skeleton skeleton-text w-50 mb-1" style="height: 10px;"></div><div class="skeleton skeleton-text w-75 mb-0" style="height: 14px;"></div></div>
                                    <div class="col-6"><div class="skeleton skeleton-text w-50 mb-1" style="height: 10px;"></div><div class="skeleton rounded-pill w-50" style="height: 20px;"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div class="w-25">
                                <div class="skeleton skeleton-title w-75 mb-1" style="height: 18px;"></div>
                                <div class="skeleton skeleton-text w-50 mb-0"></div>
                            </div>
                            <div class="skeleton skeleton-badge rounded-pill" style="width: 140px; height: 35px;"></div>
                        </div>
                    </div>
                </div>

                <div class="real-wrapper d-none">
                    <div class="digital-id-card mb-4">
                        <div class="digital-id-header position-relative" style="background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%); color: white; padding: 30px 20px; text-align: center; border-radius: 15px 15px 0 0;">
                            <h5 class="text-white mb-0 opacity-75 fs-5">TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES</h5>
                            <div class="mt-4 mb-n5 position-relative" style="z-index: 2;">
                                <?php $pic = session()->get('profile_pic') ?? 'default.png'; ?>
                                <img src="<?= base_url('uploads/profiles/' . $pic) ?>" alt="Profile" class="rounded-circle border border-4 border-white shadow-sm bg-white" width="120" height="120" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="digital-id-body pt-5 pb-4 bg-white rounded-bottom shadow-sm text-center" style="border-radius: 0 0 15px 15px;">
                            <div class="pt-3">
                                <h3 class="fw-bold text-dark mb-1 text-uppercase"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></h3>
                                <p class="text-primary fw-semibold fs-5 mb-3"><?= esc($student['student_number']) ?></p>
                                <div class="row text-start bg-light rounded-3 p-3 mx-2 mx-md-4">
                                    <div class="col-6 mb-2">
                                        <small class="text-muted d-block">Department</small>
                                        <span class="fw-semibold"><?= esc($student['department']) ?></span>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted d-block">Status</small>
                                        <span class="badge bg-success rounded-3 fw-semibold px-3">Enrolled</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold">Campus Status</h6>
                                <small class="text-muted">Your current location</small>
                            </div>
                            <span class="badge <?= esc($badgeClass ?? 'bg-light-primary text-primary') ?> fs-3 rounded-pill px-3 py-2">
                                <i class="ti ti-building me-1"></i> <?= esc($campusStatus ?? 'Outside Campus') ?>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        <?php if (!empty($showTermsModal)): ?>
        document.addEventListener('DOMContentLoaded', function () {
            const termsModalEl = document.getElementById('termsModal');
            if (!termsModalEl) return;

            const termsModal = new bootstrap.Modal(termsModalEl);
            termsModal.show();

            document.getElementById('btnAcceptTerms').addEventListener('click', function () {
                const rememberChecked = document.getElementById('termsNeverShowAgain').checked;

                if (rememberChecked) {
                    fetch("<?= base_url('student/accept-terms') ?>", {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                        }
                    }).catch(() => {});
                }

                termsModal.hide();
            });
        });
        <?php endif; ?>

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