<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Remove Item | Student Portal<?= $this->endSection() ?>
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
        <h4 class="fw-semibold mb-3">Item Unregistration</h4>

        <div id="alertContainer"></div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <p class="text-muted mb-4">Select an item to request unregistration (e.g., if lost, sold, or no longer used). An admin must approve this request.</p>

                <?php
                $approvedItems = array_filter($items, function($item) {
                    return $item['status'] === 'approved';
                });
                ?>

                <?php if (!empty($approvedItems)): ?>
                    <div class="skeleton-wrapper">
                        <?php for($i=0; $i<count($approvedItems); $i++): ?>
                            <div class="border rounded p-3 d-flex justify-content-between align-items-center mb-3 bg-light">
                                <div class="w-75">
                                    <div class="skeleton skeleton-title w-50 mb-1" style="height: 18px;"></div>
                                    <div class="skeleton skeleton-text w-25 mb-0" style="height: 12px;"></div>
                                </div>
                                <div class="skeleton rounded-2" style="width: 90px; height: 30px;"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

                <div class="real-wrapper <?= empty($approvedItems) ? '' : 'd-none' ?>">
                    <?php if (empty($approvedItems)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="ti ti-device-laptop d-block mb-3 text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                            No active items available to unregister.
                        </div>
                    <?php else: ?>
                        <div id="approvedItemsContainer">
                            <?php foreach ($approvedItems as $item): ?>

                                <div class="border rounded p-3 d-flex justify-content-between align-items-center mb-3 border-warning bg-light item-card" id="itemCard<?= $item['id'] ?>">
                                    <div>
                                        <h6 class="fw-bold mb-0"><?= esc($item['brand_model']) ?></h6>
                                        <small class="text-muted">SN: <?= esc($item['serial_number']) ?></small>
                                    </div>
                                    <button type="button"
                                            class="btn btn-outline-warning btn-sm fw-bold"
                                            data-bs-toggle="modal"
                                            data-bs-target="#unregisterModal<?= $item['id'] ?>">
                                        Unregister
                                    </button>
                                </div>

                                <div class="modal fade" id="unregisterModal<?= $item['id'] ?>" tabindex="-1" aria-labelledby="unregisterModalLabel<?= $item['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mx-auto px-3 px-sm-0">
                                        <div class="modal-content border-0 shadow-lg rounded-4">

                                            <form action="<?= base_url('student/items/request-unregister/' . $item['id']) ?>" method="POST">
                                                <?= csrf_field() ?>

                                                <div class="modal-header bg-warning-subtle border-0 rounded-top-4 sticky-top z-3 pt-3 pb-3 w-100 d-flex justify-content-between align-items-center">

                                                    <h5 class="modal-title fw-bold text-warning-emphasis mb-0 text-truncate pe-2" id="unregisterModalLabel<?= $item['id'] ?>">
                                                        <i class="ti ti-alert-triangle me-1"></i> Confirm Unregistration
                                                    </h5>

                                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                                        <!-- NEW: Cancel Button moved to the header! -->
                                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                    </div>

                                                </div>

                                                <div class="modal-body p-4 text-center">
                                                    <h5 class="fw-bold mb-2">Are you sure?</h5>
                                                    <p class="text-muted mb-0">You are about to request unregistration for:</p>

                                                    <div class="my-3 d-flex justify-content-center">
                                                        <?php if(!empty($item['photo'])): ?>
                                                            <img src="<?= base_url('uploads/items/' . esc($item['photo'])) ?>"
                                                                 class="img-fluid rounded-3 shadow-sm border border-light"
                                                                 alt="Item image"
                                                                 style="max-height: 140px; width: auto; object-fit: contain;">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="height: 120px; width: 100%; max-width: 200px;">
                                                                <i class="ti ti-photo fs-2 text-muted opacity-50"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <p class="fw-bold fs-5 text-dark mb-3"><?= esc($item['brand_model']) ?></p>

                                                    <div class="alert alert-light border shadow-sm small text-start mb-0 text-dark">
                                                        <i class="ti ti-info-circle text-primary me-1"></i>
                                                        This item will be flagged for Admin review. You will not be able to use it as a valid gate pass until this is resolved.
                                                    </div>

                                                    <div class="text-start mb-4">
                                                        <label class="form-label fw-bold text-muted small">Reason for Unregistration <span class="text-danger">*</span></label>
                                                        <textarea class="form-control bg-light" name="reason" rows="2" placeholder="e.g., Sold the item, broken, no longer using it..." required></textarea>
                                                    </div>

                                                </div>

                                                <div class="modal-footer border-0 bg-light rounded-bottom-4 d-flex justify-content-center pb-4 modal-actions-mobile">
                                                    <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">
                                                        Yes, Request Unregister
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>

                        <div class="text-center text-muted py-4 d-none" id="noItemsMessage">
                            <i class="ti ti-device-laptop fs-1 d-block mb-2"></i>
                            No active items available to unregister.
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Custom circular close button — only shown on mobile/tablet, see media query below */
        .mobile-sheet-close {
            display: none;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            min-width: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            border: none;
            color: #6c4a03;
            font-size: 1.05rem;
            padding: 0;
        }
        .mobile-sheet-close:active { background: rgba(255, 255, 255, 0.9); }

        /* ===================================================================
           MOBILE / TABLET ONLY — Bottom sheet modal styling
           Matches breakpoints up to Bootstrap's lg (992px), i.e. phones + tablets.
           Desktop (>=992px) keeps the original centered dialog untouched.
           =================================================================== */
        @media (max-width: 991.98px) {
            .modal .modal-dialog {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                top: auto !important;
                margin: 0;
                width: 100%;
                max-width: 100%;
                padding: 0 !important;
            }
            .modal .modal-dialog.modal-dialog-centered {
                display: block !important;
                align-items: initial !important;
                min-height: 0 !important;
            }
            .modal.fade .modal-dialog {
                transform: translateY(100%);
                transition: transform 0.32s cubic-bezier(0.32, 0.72, 0, 1);
            }
            .modal.show .modal-dialog {
                transform: translateY(0);
            }

            .modal-content {
                border-radius: 24px 24px 0 0 !important;
                max-height: 92vh;
                overflow-y: auto;
            }
            .modal-content::before {
                content: '';
                display: block;
                width: 40px;
                height: 4px;
                border-radius: 999px;
                background: #dbe0e6;
                margin: 10px auto 0;
            }
            .modal-header {
                border-radius: 0 !important;
                padding-top: 6px;
            }

            .mobile-sheet-close {
                display: inline-flex;
            }
            .modal-header .btn-close {
                display: none;
            }
            .modal-title {
                font-size: 1.05rem;
            }

            .modal-actions-mobile {
                flex-direction: column-reverse !important;
                align-items: stretch !important;
            }
            .modal-actions-mobile .btn {
                width: 100%;
            }
            .modal-actions-mobile .btn-light {
                background: transparent !important;
                border: none !important;
                color: #6c757d;
            }
            .modal-actions-mobile .btn-warning {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
        }
    </style>

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

                checkRemoveTourHandoff();
            }, 600);
        }

        window.addEventListener("load", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // Catches the handoff from Registered Items, then hands off to Report Item
        function checkRemoveTourHandoff() {
            if (typeof introJs === 'undefined') return;

            if (localStorage.getItem('gate_tour_remove_pending') === 'true') {
                localStorage.removeItem('gate_tour_remove_pending');

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
                            title: 'Unregistering an Item',
                            intro: 'If you sell, lose, or stop using a device, this is where you request to have it removed from your gate pass.'
                        },
                        {
                            element: document.querySelector('.real-wrapper'),
                            title: 'Request Removal',
                            intro: 'Tap "Unregister" on any item card, and an admin will review your request.',
                            position: 'top'
                        },
                        {
                            title: 'Reporting a lost item',
                            intro: 'Next, we\'ll take you to the Report Item page — useful if something goes missing on campus.'
                        }
                    ]
                }).oncomplete(function() {
                    localStorage.setItem('gate_tour_report_pending', 'true');
                    gateTourNavigate('<?= base_url('student/report-item') ?>');
                }).onexit(function() {
                    // Cancelled — chain simply stops here.
                }).start();
            }
        }

        // --- NEW: Inline Alert System ---
        function showInlineAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            if (alertContainer) {
                // Pick the right icon based on success/error
                const icon = type === 'success' ? 'ti-check-circle' : 'ti-alert-triangle';

                // Build the HTML matching your dashboard's style
                const alertHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4 d-flex align-items-center" role="alert">
                        <i class="ti ${icon} fs-5 me-2 text-${type}"></i>
                        <span class="text-${type}-emphasis fw-medium">${message}</span>
                        <button type="button" class="btn-close m-0 ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Inject it above the card
                alertContainer.innerHTML = alertHTML;

                // Optional: Auto-hide the alert after 4 seconds so it doesn't sit there forever
                setTimeout(() => {
                    const alertNode = alertContainer.querySelector('.alert');
                    if (alertNode) {
                        const bsAlert = new bootstrap.Alert(alertNode);
                        bsAlert.close();
                    }
                }, 4000);
            }
        }

        // --- Background Processor ---
        window.processUnregister = async function(btn, url, itemId) {
            const originalText = btn.innerHTML;

            // Show loading state
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            btn.classList.add('disabled');
            btn.disabled = true;

            try {
                // Send the background request
                await fetch(url, {
                    method: 'GET',
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });

                // Close the modal cleanly
                const modalEl = document.getElementById('unregisterModal' + itemId);
                if (modalEl) {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalInstance.hide();
                }

                // Clean up grey backdrops
                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';

                    // TRIGGER THE INLINE ALERT HERE!
                    showInlineAlert("Item unregistration requested successfully!");
                }, 300);

                // Animate the item away
                const itemCard = document.getElementById('itemCard' + itemId);
                if (itemCard) {
                    itemCard.style.transition = 'all 0.4s ease';
                    itemCard.style.opacity = '0';
                    itemCard.style.transform = 'scale(0.9)';

                    setTimeout(() => {
                        itemCard.remove();

                        // Show empty state if needed
                        const remainingItems = document.querySelectorAll('.item-card');
                        if(remainingItems.length === 0) {
                            const emptyMsg = document.getElementById('noItemsMessage');
                            if(emptyMsg) emptyMsg.classList.remove('d-none');
                        }
                    }, 400);
                }

            } catch (error) {
                console.error("Error unregistering item:", error);
                btn.innerHTML = originalText;
                btn.classList.remove('disabled');
                btn.disabled = false;

                // Show a red inline alert if something breaks
                showInlineAlert("Something went wrong. Please try again.", "danger");
            }
        };
    </script>

<?= $this->endSection() ?>