<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Scan History | Student Portal<?= $this->endSection() ?>
<?= $this->section('styles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css">

    <style>
        /* =========================================
           1. COLOR VARIABLES (THEME MANAGEMENT)
           ========================================= */

        /* Light Mode (Default) Colors */
        :root {
            --tour-bg: #ffffff;
            --tour-border: none;
            --tour-title: #1e4db7;
            --tour-text: #546269;
            --tour-btn-top-border: 1px solid #ecf0f2;
            --tour-prev-color: #777e89;
            --tour-prev-hover: #11142d;
            --tour-next-bg: #1e4db7;
            --tour-next-color: #ffffff;
            --tour-next-hover: #183e92;
            --tour-bullet: #ced4da;
            --tour-bullet-active: #1e4db7;
            --tour-arrow: #ffffff;
            --tour-shadow: 0 16px 32px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
        }

        /* Dark Mode Colors */
        html[data-bs-theme="dark"] {
            --tour-bg: #223640;
            --tour-border: 1px solid #4f5467;
            --tour-title: #8bb4fa;
            --tour-text: #f1f9ff;
            --tour-btn-top-border: 1px solid #4f5467;
            --tour-prev-color: #a1aab2;
            --tour-prev-hover: #ffffff;
            --tour-next-bg: #8bb4fa;
            --tour-next-color: #11142d;
            --tour-next-hover: #a5c7ff;
            --tour-bullet: #4f5467;
            --tour-bullet-active: #8bb4fa;
            --tour-arrow: #223640;
            --tour-shadow: 0 16px 32px rgba(0, 0, 0, 0.4);
        }

        /* =========================================
           2. STRUCTURAL & COMPONENT STYLES
           ========================================= */

        /* Force structural integrity against Bootstrap resets */
        .introjs-tooltip {
            position: absolute !important;
            background: var(--tour-bg) !important;
            border: var(--tour-border) !important;
            border-radius: 16px !important;
            box-shadow: var(--tour-shadow) !important;
            padding: 24px !important;
            width: 340px !important;
            max-width: calc(100vw - 40px) !important;
            opacity: 1 !important;
            visibility: visible !important;
            z-index: 9999999 !important;
            box-sizing: border-box !important;
        }

        /* --- MOBILE ADAPTIVE SCALING --- */
        @media (max-width: 576px) {
            .introjs-tooltip {
                width: 90vw !important;
                padding: 18px !important;
                border-radius: 14px !important;
            }
            .introjs-tooltiptitle {
                font-size: 1.15rem !important;
            }
            .introjs-tooltiptext {
                font-size: 0.9rem !important;
            }
            .introjs-button {
                padding: 6px 16px !important;
                font-size: 0.85rem !important;
            }
        }

        /* The dark screen overlay */
        .introjs-overlay {
            background-color: rgba(17, 20, 45, 0.95) !important; /* Increased opacity for a darker background */
            z-index: 9999990 !important;
        }

        /* The transparent cutout target */
        .introjs-helperLayer {
            background: rgba(0, 0, 0, 0.15) !important; /* Adds a subtle dim effect over the highlighted element */
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
            color: var(--tour-title) !important; /* Using variable */
            margin: 0 !important;
            line-height: 1.3 !important;
        }

        .introjs-tooltiptext {
            font-family: "DM Sans", sans-serif !important;
            font-size: 0.95rem !important;
            color: var(--tour-text) !important; /* Using variable */
            line-height: 1.6 !important;
            padding: 0 0 20px 0 !important;
            margin: 0 !important;
        }

        /* Action Buttons Area */
        .introjs-tooltipbuttons {
            border-top: var(--tour-btn-top-border) !important; /* Using variable */
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
            color: var(--tour-prev-color) !important; /* Using variable */
            border: none !important;
            padding-left: 0 !important;
            box-shadow: none !important;
        }
        .introjs-prevbutton:hover, .introjs-skipbutton:hover {
            color: var(--tour-prev-hover) !important; /* Using variable */
        }

        /* Primary Next/Done Buttons */
        .introjs-nextbutton, .introjs-donebutton {
            background: var(--tour-next-bg) !important; /* Using variable */
            color: var(--tour-next-color) !important; /* Using variable */
            border: none !important;
            box-shadow: 0 4px 10px rgba(0,0,0, 0.15) !important; /* Unified shadow for simplicity */
        }
        .introjs-nextbutton:hover, .introjs-donebutton:hover {
            background: var(--tour-next-hover) !important; /* Using variable */
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
            background: var(--tour-bullet) !important; /* Using variable */
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            margin: 0 4px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .introjs-bullets ul li a.active {
            background: var(--tour-bullet-active) !important; /* Using variable */
            width: 24px !important;
            border-radius: 10px !important;
        }

        /* Alignment Arrows */
        .introjs-arrow.top { border-bottom-color: var(--tour-arrow) !important; }
        .introjs-arrow.bottom { border-top-color: var(--tour-arrow) !important; }
        .introjs-arrow.left { border-right-color: var(--tour-arrow) !important; }
        .introjs-arrow.right { border-left-color: var(--tour-arrow) !important; }

        /* --- TOOLTIP BOX & ARROW ALIGNMENT FIXES --- */

        /* Desktop: Shift the entire box upward */
        @media (min-width: 992px) {
            .custom-register-tooltip {
                margin-top: -35px !important;
            }
            .custom-register-tooltip .introjs-arrow.left {
                top: 15px !important;
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
            .custom-register-tooltip .introjs-arrow {
                left: auto !important;
                right: 10px !important;
                margin-left: 0 !important;
            }
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="page-transition-container pt-5 mt-4">
        <div class="card border-0 shadow-sm w-100">
            <div class="card-body p-4">
                <h4 class="card-title fw-semibold mb-4">Gatepass Scan History</h4>

                <?php if (!empty($logs)): ?>
                    <div class="skeleton-wrapper">
                        <div class="d-block d-md-none">
                            <?php for($i=0; $i<min(count($logs), 4); $i++): ?>
                                <div class="px-0 py-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="w-50">
                                            <div class="skeleton skeleton-title w-75 mb-1" style="height: 18px;"></div>
                                            <div class="skeleton skeleton-text w-50 mb-0"></div>
                                        </div>
                                        <div class="skeleton skeleton-badge rounded-pill" style="width: 80px; height: 26px;"></div>
                                    </div>
                                    <div class="skeleton rounded-3 w-100 mt-2" style="height: 60px;"></div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <!-- Desktop Skeleton -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table align-middle text-nowrap mb-0">
                                <thead class="border-bottom border-2">
                                <tr>
                                    <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                    <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                    <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                    <th><div class="skeleton skeleton-text w-50 mb-0 ms-auto"></div></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php for($i=0; $i<min(count($logs), 5); $i++): ?>
                                    <tr>
                                        <td class="py-3"><div class="skeleton skeleton-text w-75 mb-1"></div><div class="skeleton skeleton-text w-50 mb-0"></div></td>
                                        <td class="py-3"><div class="skeleton skeleton-title w-100 mb-0" style="height: 20px;"></div></td>
                                        <td class="py-3"><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                        <td class="py-3 text-end"><div class="skeleton skeleton-badge rounded-pill ms-auto" style="width: 100px; height: 32px;"></div></td>
                                    </tr>
                                <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="real-wrapper <?= empty($logs) ? '' : 'd-none' ?>">
                    <?php if (empty($logs)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="ti ti-history d-block mb-2 opacity-50" style="font-size: 3rem;"></i>
                            No scan history found for your devices yet.
                        </div>
                    <?php else: ?>

                        <div class="d-block d-md-none">
                            <div class="list-group list-group-flush">
                                <?php foreach($logs as $log): ?>
                                    <div class="list-group-item px-0 py-3 border-bottom">

                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="d-block fw-bold text-dark fs-4"><?= date('M d, Y', strtotime($log['created_at'])) ?></span>
                                                <span class="small text-muted fw-medium"><?= date('h:i A', strtotime($log['created_at'])) ?></span>
                                            </div>
                                            <div>
                                                <?php if ($log['action'] === 'time_in'): ?>
                                                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 fs-2 shadow-sm rounded-pill">
                                                        <i class="ti ti-login me-1"></i> Time In
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 fs-2 shadow-sm rounded-pill">
                                                        <i class="ti ti-logout me-1"></i> Time Out
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="bg-light rounded-3 p-2 mt-2 border">
                                            <h6 class="fw-semibold mb-1 text-dark fs-4 text-truncate">
                                                <?= esc($log['brand_model'] ?? $log['name'] ?? 'Unknown Item') ?>
                                            </h6>
                                            <div class="d-flex align-items-center text-muted small" style="font-size: 0.75rem;">
                                                <i class="ti ti-barcode me-1"></i> SN: <span class="fw-bold ms-1 text-dark"><?= esc($log['serial_number'] ?? 'N/A') ?></span>
                                            </div>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="table-responsive d-none d-md-block">
                            <table class="table align-middle text-nowrap mb-0">
                                <thead class="text-dark fs-4 border-bottom border-2">
                                <tr>
                                    <th class="border-0"><h6 class="fw-semibold mb-0">Date & Time</h6></th>
                                    <th class="border-0"><h6 class="fw-semibold mb-0">Item Details</h6></th>
                                    <th class="border-0"><h6 class="fw-semibold mb-0">Serial Number</h6></th>
                                    <th class="border-0 text-end"><h6 class="fw-semibold mb-0">Action</h6></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($logs as $log): ?>
                                    <tr>
                                        <td class="border-bottom-0">
                                            <span class="d-block fw-semibold text-dark"><?= date('M d, Y', strtotime($log['created_at'])) ?></span>
                                            <span class="small text-muted"><?= date('h:i A', strtotime($log['created_at'])) ?></span>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0 text-dark"><?= esc($log['brand_model'] ?? $log['name'] ?? 'Unknown Item') ?></h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <p class="mb-0 fw-normal"><?= esc($log['serial_number'] ?? 'N/A') ?></p>
                                        </td>
                                        <td class="border-bottom-0 text-end">
                                            <?php if ($log['action'] === 'time_in'): ?>
                                                <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 fs-3 shadow-sm rounded-pill">
                                                        <i class="ti ti-login me-1"></i> Time In
                                                    </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-2 fs-3 shadow-sm rounded-pill">
                                                        <i class="ti ti-logout me-1"></i> Time Out
                                                    </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

    <script>
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));

                checkHistoryTourHandoff();
            }, 600);
        }

        document.addEventListener("DOMContentLoaded", hideMySkeletons);

        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // Catches the handoff from Report Item. This is the final stop —
        // no further flag is set and no further page navigation happens.
        function checkHistoryTourHandoff() {
            if (typeof introJs === 'undefined') return;

            if (localStorage.getItem('gate_tour_history_pending') === 'true') {
                localStorage.removeItem('gate_tour_history_pending');

                introJs().setOptions({
                    showProgress: false,
                    showStepNumbers: false,
                    showBullets: true,
                    exitOnOverlayClick: false,
                    keyboardNavigation: true,
                    nextLabel: 'Next',
                    prevLabel: 'Back',
                    doneLabel: "You're all set! 🎉",
                    steps: [
                        {
                            title: 'Your Scan History',
                            intro: 'Every time your items are tapped at the gate, it shows up here — a full log of your campus entries and exits.'
                        },
                        {
                            title: "That's the tour!",
                            intro: 'You now know your way around GATE. You can revisit any of these pages anytime from the menu.'
                        }
                    ]
                }).oncomplete(function() {
                    // Final stop — nothing further to hand off.
                }).onexit(function() {
                    // Cancelled on the last step — nothing further to clean up.
                }).start();
            }
        }
    </script>
<?= $this->endSection() ?>