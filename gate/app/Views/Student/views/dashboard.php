<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';

$referrer = service('request')->getHeaderLine('HX-Current-URL');
$slideIn = (strpos($referrer, 'profile') !== false);
?>
<?= $this->extend($layout) ?>

<?= $this->section('title') ?>Dashboard | Student Portal<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css">

    <style>
        /* Force structural integrity against Bootstrap resets */
        .introjs-tooltip {
            position: absolute !important;
            background: #ffffff !important;
            border-radius: 16px !important;
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06) !important;
            padding: 24px !important;
            width: 340px !important;
            max-width: calc(100vw - 40px) !important;
            opacity: 1 !important;
            visibility: visible !important;
            border: none !important;
            z-index: 9999999 !important;
            box-sizing: border-box !important;
        }

        /* --- MOBILE ADAPTIVE SCALING --- */
        @media (max-width: 576px) {
            .introjs-tooltip {
                width: 90vw !important; /* Scales to 90% of phone screen width */
                padding: 18px !important; /* Tighter padding for mobile */
                border-radius: 14px !important;
            }
            .introjs-tooltiptitle {
                font-size: 1.15rem !important; /* Slightly smaller title */
            }
            .introjs-tooltiptext {
                font-size: 0.9rem !important; /* Slightly smaller text */
            }
            .introjs-button {
                padding: 6px 16px !important; /* Compact buttons */
                font-size: 0.85rem !important;
            }
        }

        /* The dark screen overlay */
        .introjs-overlay {
            background-color: rgba(17, 20, 45, 0.8) !important;
            z-index: 9999990 !important;
        }

        /* The transparent cutout target */
        .introjs-helperLayer {
            background: transparent !important;
            border-radius: 12px !important;
            box-shadow: 0 0 0 0 transparent !important;
            border: 2px solid rgba(255, 255, 255, 0.6) !important;
            z-index: 9999995 !important;
        }

        /* Typography & Hierarchy */
        .introjs-tooltipheader {
            padding: 0 !important;
            margin-bottom: 12px !important;
        }

        .introjs-tooltiptitle {
            font-family: "DM Sans", sans-serif !important;
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: #1e4db7 !important;
            margin: 0 !important;
            line-height: 1.3 !important;
        }

        .introjs-tooltiptext {
            font-family: "DM Sans", sans-serif !important;
            font-size: 0.95rem !important;
            color: #546269 !important;
            line-height: 1.6 !important;
            padding: 0 0 20px 0 !important;
            margin: 0 !important;
        }

        /* Action Buttons Area */
        .introjs-tooltipbuttons {
            border-top: 1px solid #ecf0f2 !important;
            padding-top: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        /* Shared Button Traits */
        .introjs-button {
            font-family: "DM Sans", sans-serif !important;
            border-radius: 50px !important;
            padding: 8px 20px !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            text-shadow: none !important;
            background-image: none !important;
            text-decoration: none !important;
        }

        /* Secondary/Back Buttons */
        .introjs-prevbutton, .introjs-skipbutton {
            background: transparent !important;
            color: #777e89 !important;
            border: none !important;
            padding-left: 0 !important;
            box-shadow: none !important;
        }
        .introjs-prevbutton:hover, .introjs-skipbutton:hover {
            color: #11142d !important;
        }

        /* Primary Next/Done Buttons */
        .introjs-nextbutton, .introjs-donebutton {
            background: #1e4db7 !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 10px rgba(30, 77, 183, 0.25) !important;
        }
        .introjs-nextbutton:hover, .introjs-donebutton:hover {
            background: #183e92 !important;
            transform: translateY(-2px) !important;
        }

        .introjs-disabled {
            opacity: 0.4 !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
        }

        /* Custom Elongated Progress Bullets */
        .introjs-bullets {
            display: flex !important;
            align-items: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .introjs-bullets ul li a {
            background: #ced4da !important;
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            margin: 0 4px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .introjs-bullets ul li a.active {
            background: #1e4db7 !important;
            width: 24px !important;
            border-radius: 10px !important;
        }

        /* Alignment Arrows */
        .introjs-arrow.top { border-bottom-color: #ffffff !important; }
        .introjs-arrow.bottom { border-top-color: #ffffff !important; }
        .introjs-arrow.left { border-right-color: #ffffff !important; }
        .introjs-arrow.right { border-left-color: #ffffff !important; }

        /* --- TOOLTIP BOX & ARROW ALIGNMENT FIXES --- */

        /* Desktop: Shift the entire box upward */
        @media (min-width: 992px) {
            .custom-register-tooltip {
                margin-top: -35px !important; /* Moves the entire box upward */
            }

            /* Move the arrow to the top end of the left side */
            .custom-register-tooltip .introjs-arrow.left {
                top: 15px !important; /* Pushes the arrow to the top edge */
                margin-top: 0 !important;
            }
        }

        /* Mobile/Tablet: Align the box to the right edge */
        @media (max-width: 991px) {
            .custom-register-tooltip {
                left: auto !important;
                right: 15px !important;
                width: calc(100vw - 30px) !important;
                max-width: 350px !important;
            }

            /* Move the arrow to the bottom right corner */
            .custom-register-tooltip .introjs-arrow.bottom {
                left: auto !important;
                right: 15px !important;    /* Pushes the arrow strictly to the right corner */
                margin-left: 0 !important;
            }
        }

        /* DARK MODE INTEGRATION */
        html[data-bs-theme="dark"] .introjs-tooltip {
            background: #223640 !important;
            border: 1px solid #4f5467 !important;
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.4) !important;
        }
        html[data-bs-theme="dark"] .introjs-tooltiptitle { color: #8bb4fa !important; }
        html[data-bs-theme="dark"] .introjs-tooltiptext { color: #f1f9ff !important; }
        html[data-bs-theme="dark"] .introjs-tooltipbuttons { border-top-color: #4f5467 !important; }
        html[data-bs-theme="dark"] .introjs-prevbutton,
        html[data-bs-theme="dark"] .introjs-skipbutton { color: #a1aab2 !important; }
        html[data-bs-theme="dark"] .introjs-prevbutton:hover,
        html[data-bs-theme="dark"] .introjs-skipbutton:hover { color: #ffffff !important; }
        html[data-bs-theme="dark"] .introjs-nextbutton,
        html[data-bs-theme="dark"] .introjs-donebutton { background: #8bb4fa !important; color: #11142d !important; }
        html[data-bs-theme="dark"] .introjs-nextbutton:hover,
        html[data-bs-theme="dark"] .introjs-donebutton:hover { background: #a5c7ff !important; }
        html[data-bs-theme="dark"] .introjs-bullets ul li a { background: #4f5467 !important; }
        html[data-bs-theme="dark"] .introjs-bullets ul li a.active { background: #8bb4fa !important; }
        html[data-bs-theme="dark"] .introjs-arrow.top { border-bottom-color: #223640 !important; }
        html[data-bs-theme="dark"] .introjs-arrow.bottom { border-top-color: #223640 !important; }
        html[data-bs-theme="dark"] .introjs-arrow.left { border-right-color: #223640 !important; }
        html[data-bs-theme="dark"] .introjs-arrow.right { border-left-color: #223640 !important; }
    </style>
<?= $this->endSection() ?>

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

                    <!-- Digital ID Element -->
                    <div id="tour-digital-id" class="digital-id-card mb-4">
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

                    <!-- Campus Status Element -->
                    <div id="tour-campus-status" class="card shadow-sm border-0">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

    <script>
        <?php if (!empty($showTermsModal)): ?>
        function initializeTermsModal() {
            const termsModalEl = document.getElementById('termsModal');
            if (!termsModalEl) return;

            if (termsModalEl.classList.contains('show')) return;

            const termsModal = new bootstrap.Modal(termsModalEl);
            termsModal.show();

            const acceptBtn = document.getElementById('btnAcceptTerms');
            if (acceptBtn) {
                acceptBtn.onclick = function () {
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
                };
            }

            termsModalEl.addEventListener('hidden.bs.modal', function () {
                startOnboardingTour();
            }, { once: true });
        }

        document.addEventListener('DOMContentLoaded', initializeTermsModal);
        document.body.addEventListener('htmx:afterSettle', initializeTermsModal);
        <?php endif; ?>

        const showTermsModalFlag = <?= !empty($showTermsModal) ? 'true' : 'false' ?>;

        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));

                if (!showTermsModalFlag) {
                    startOnboardingTour();
                }
            }, 600);
        }

        function startOnboardingTour() {
            if (typeof introJs === 'undefined') return;

            const tourStorageKey = 'hasSeenDashboardTour_<?= esc(session()->get('student_id')) ?>';

            if (!localStorage.getItem(tourStorageKey)) {

                // Determine if we are on mobile or desktop layout
                const isMobile = window.innerWidth < 992;

                // Select the correct registration element depending on the screen size
                const registerTarget = isMobile
                    ? document.querySelector('.mobile-fab')
                    : document.querySelector('.sidebar-link[href*="items"]'); // Adjust to match your sidebar link href if needed

                let tourSteps = [
                    {
                        title: '👋 Welcome to GATE!',
                        intro: 'Let us take a quick tour to show you around your new Student Dashboard.'
                    },
                    {
                        element: document.querySelector('#tour-digital-id'),
                        title: 'Your Digital ID',
                        intro: 'This is your official digital ID card. Security personnel may ask to see this when verifying your identity.',
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('#tour-campus-status'),
                        title: 'Campus Status',
                        intro: 'This updates automatically when you tap your registered items at the gate, letting you know your current campus location.',
                        position: 'top'
                    }
                ];

                // Add the registration step pointing to the correct UI element
                if (registerTarget) {
                    tourSteps.push({
                        element: registerTarget,
                        title: '➕ Register Items',
                        intro: isMobile
                            ? 'Tap this floating button to register your laptops and equipment for your gate pass.'
                            : 'Click here in the sidebar anytime to register your personal equipment.',
                        position: isMobile ? 'top' : 'right',
                        tooltipClass: 'custom-register-tooltip' // Assigning a custom class for targeted CSS fixes
                    });
                }

                // Final step
                tourSteps.push({
                    title: '🎉 You are ready!',
                    intro: 'You can now use your portal securely. Stay safe!'
                });

                introJs().setOptions({
                    showProgress: false,
                    showStepNumbers: false,
                    showBullets: true,
                    exitOnOverlayClick: false,
                    keyboardNavigation: true,
                    nextLabel: 'Next',
                    prevLabel: 'Back',
                    doneLabel: 'Get Started 🚀',
                    steps: tourSteps
                }).oncomplete(function() {
                    localStorage.setItem(tourStorageKey, 'true');
                }).onexit(function() {
                    localStorage.setItem(tourStorageKey, 'true');
                }).start();
            }
        }

        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);
    </script>
<?= $this->endSection() ?>