<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Report Item | Student Portal<?= $this->endSection() ?>
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
        <h4 class="fw-semibold mb-3">Report Lost/Stolen Item</h4>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <div class="alert alert-warning rounded-3 border-0 shadow-sm">
                    <i class="ti ti-alert-circle me-2"></i> If your equipment goes missing inside the campus, report it immediately.
                </div>

                <div class="skeleton-wrapper mt-4">
                    <div class="mb-3">
                        <div class="skeleton skeleton-text w-25 mb-2"></div>
                        <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                    </div>
                    <div class="mb-3">
                        <div class="skeleton skeleton-text w-25 mb-2"></div>
                        <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                    </div>
                    <div class="mb-4">
                        <div class="skeleton skeleton-text w-25 mb-2"></div>
                        <div class="skeleton rounded-3 w-100" style="height: 86px;"></div>
                    </div>
                    <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                </div>

                <form class="real-wrapper d-none mt-4" action="<?= base_url('student/items/report') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="item_id">Select Missing Item</label>
                        <select class="form-select bg-light" name="item_id" id="item_id" required>
                            <option value="" disabled selected>-- Select an item to report --</option>

                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <?php if ($item['status'] === 'approved' && isset($item['in_campus']) && $item['in_campus'] == 1): ?>
                                        <option value="<?= $item['id'] ?>">
                                            <?= esc($item['brand_model'] ?? $item['name'] ?? 'Unknown Item') ?>
                                            (<?= esc($item['serial_number'] ?? 'N/A') ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No registered items available</option>
                            <?php endif; ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="location">Last Known Location</label>
                        <input type="text" class="form-control bg-light" id="location" name="location" placeholder="e.g., Library 2nd Floor" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="notes">Notes</label>
                        <textarea class="form-control bg-light"
                                  id="notes"
                                  name="notes"
                                  rows="3"
                                  placeholder="Briefly explain incident details, item colors, specific signs, or other info that might help..."
                                  required></textarea>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-2 text-dark fw-bold shadow-sm rounded-3">Submit Report to Guards</button>
                </form>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

    <script>
        function gateTourNavigate(url) {
            const link = document.createElement('a');
            link.setAttribute('href', 'javascript:void(0);');
            link.setAttribute('hx-get', url);
            link.setAttribute('hx-target', '#app-content');
            link.setAttribute('hx-select', '#app-content');
            link.setAttribute('hx-push-url', 'true');
            link.setAttribute('hx-swap', 'outerHTML swap:300ms');
            link.setAttribute('hx-indicator', '#page-transition-loader');
            document.body.appendChild(link);
            htmx.process(link);
            link.click();
        }

        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));

                checkReportTourHandoff();
            }, 600);
        }

        document.addEventListener("DOMContentLoaded", hideMySkeletons);

        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // Catches the handoff from Remove Item, then hands off to Scan History (final stop)
        function checkReportTourHandoff() {
            if (typeof introJs === 'undefined') return;

            if (localStorage.getItem('gate_tour_report_pending') === 'true') {
                localStorage.removeItem('gate_tour_report_pending');

                introJs().setOptions({
                    showProgress: false,
                    showStepNumbers: false,
                    showBullets: true,
                    exitOnOverlayClick: false,
                    keyboardNavigation: true,
                    nextLabel: 'Next',
                    prevLabel: 'Back',
                    doneLabel: 'Next Page 🚀',
                    steps: [
                        {
                            title: 'Report a Missing Item',
                            intro: 'If your equipment goes missing on campus, report it here right away so security can be alerted.'
                        },
                        {
                            element: document.querySelector('#item_id'),
                            title: 'Select the Item',
                            intro: 'Choose which of your registered items is missing.',
                            position: 'bottom'
                        },
                        {
                            element: document.querySelector('#location'),
                            title: 'Last Known Location',
                            intro: 'Tell us where you last had it — this helps guards narrow down where to look.',
                            position: 'top'
                        },
                        {
                            title: 'One last stop!',
                            intro: 'Next, we\'ll take you to your Scan History page to finish the tour.'
                        }
                    ]
                }).oncomplete(function() {
                    localStorage.setItem('gate_tour_history_pending', 'true');
                    gateTourNavigate('<?= base_url('student/history') ?>');
                }).onexit(function() {
                    // Cancelled — chain simply stops here.
                }).start();
            }
        }
    </script>
<?= $this->endSection() ?>