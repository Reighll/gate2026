<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Item Registration | Student Portal<?= $this->endSection() ?>
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

    <div id="registration-container" class="page-transition-container pt-5 mt-4">

        <div class="reg-sheet-backdrop" onclick="document.getElementById('regSheetCloseBtn') && document.getElementById('regSheetCloseBtn').click();"></div>

        <div class="reg-sheet-panel">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-semibold mb-0">Item Registration</h4>
                <a id="regSheetCloseBtn" href="javascript:void(0);"
                   hx-get="<?= base_url('student/dashboard') ?>"
                   hx-target="#app-content"
                   hx-select="#app-content"
                   hx-push-url="true"
                   hx-swap="outerHTML swap:300ms"
                   hx-indicator="#page-transition-loader"
                   onclick="document.getElementById('registration-container').classList.add('reg-sheet-closing');"
                   class="btn btn-sm btn-light border shadow-sm rounded-circle d-flex d-lg-none align-items-center justify-content-center"
                   style="width: 35px; height: 35px;">
                    <i class="ti ti-x fs-5 text-muted"></i>
                </a>
            </div>

            <div id="itemRegFormFragment">
                <div id="alertContainer">
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-dismissible fade show shadow-sm rounded-3 mb-4 d-flex align-items-center bg-danger-subtle text-danger border border-danger-subtle" role="alert" style="padding: 1rem 1.25rem;">
                            <i class="ti ti-alert-circle fs-5 me-2"></i>
                            <span class="fw-medium" style="opacity: 0.9;"><?= session()->getFlashdata('error') ?></span>
                            <button type="button" class="btn-close m-0 ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="ti ti-info-circle fs-5 me-2"></i>
                            Register your personal equipment to bring inside the campus.
                        </div>

                        <div class="skeleton-wrapper mt-4">
                            <div class="mb-4">
                                <div class="skeleton skeleton-text w-25 mb-2"></div>
                                <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                            </div>
                            <div class="mb-4">
                                <div class="skeleton skeleton-text w-25 mb-2"></div>
                                <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                            </div>
                            <div class="mb-4">
                                <div class="skeleton skeleton-text w-50 mb-2"></div>
                                <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                                <div class="skeleton skeleton-text w-75 mt-2 mb-0" style="height: 10px;"></div>
                            </div>
                            <div class="mb-4">
                                <div class="skeleton skeleton-text w-25 mb-2"></div>
                                <div class="skeleton rounded-3 w-100" style="height: 42px;"></div>
                            </div>
                            <div class="skeleton rounded-3 w-100 mt-2" style="height: 45px;"></div>
                        </div>

                        <form id="registrationForm" class="real-wrapper d-none" action="<?= base_url('student/items/store') ?>" method="post" enctype="multipart/form-data" onsubmit="window.submitRegistration(event, this)">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label class="form-label">Item Category</label>
                                <select class="form-select" name="category" id="categorySelect" required onchange="window.toggleOtherCategory(this)">
                                    <option value="" disabled <?= empty(old('category')) ? 'selected' : '' ?>>Select a category...</option>
                                    <option value="Personal Computing Device" <?= old('category') == 'Personal Computing Device' ? 'selected' : '' ?>>Personal Computing Device</option>
                                    <option value="Others" <?= old('category') == 'Others' ? 'selected' : '' ?>>Others</option>
                                </select>

                                <div id="otherCategoryWrapper" class="mt-2 <?= old('category') == 'Others' ? '' : 'd-none' ?>">
                                    <input type="text" class="form-control" name="category_other" id="categoryOtherInput"
                                           value="<?= old('category_other') ?>" placeholder="Please specify the category"
                                            <?= old('category') == 'Others' ? 'required' : '' ?>>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Brand & Model</label>
                                <input type="text" class="form-control" name="brand_model" value="<?= old('brand_model') ?>" placeholder="e.g., Acer Predator Helios 300" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Serial Number / Unique Identifier</label>
                                <input type="text" class="form-control" name="serial_number" value="<?= old('serial_number') ?>" placeholder="Required for verification" required>
                                <div class="form-text">Found on the bottom of laptops or back of devices.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Item Photo</label>
                                <input class="form-control" type="file" name="photo" accept="image/*" required>
                                <div class="form-text text-muted">Max file size: 50MB. Clear photo of the item.</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fs-5 fw-bold shadow-sm">Submit Registration</button>
                        </form>
                    </div>
                </div>
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

                // Check for the incoming handoff flag after the real form is visible
                checkRegTourHandoff();
            }, 600);
        }
        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // Catches the handoff from Dashboard, then hands off to Registered Items
        function checkRegTourHandoff() {
            if (typeof introJs === 'undefined') return;

            if (localStorage.getItem('gate_tour_reg_pending') === 'true') {
                localStorage.removeItem('gate_tour_reg_pending');

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
                            title: 'Register Your Equipment',
                            intro: 'This is where you\'ll register any personal devices you plan to bring into the campus.'
                        },
                        {
                            element: document.querySelector('#categorySelect'),
                            title: 'Item Category',
                            intro: 'Start by choosing what kind of item this is — a laptop, phone, or other equipment.',
                            position: 'bottom'
                        },
                        {
                            element: document.querySelector('input[name="serial_number"]'),
                            title: 'Serial Number',
                            intro: 'This is used to verify your item at the gate, so make sure it matches your device exactly.',
                            position: 'top'
                        },
                        {
                            element: document.querySelector('input[name="photo"]'),
                            title: 'Item Photo',
                            intro: 'Upload a clear photo — this helps security staff visually confirm the item.',
                            position: 'top'
                        },
                        {
                            title: 'On to your item list!',
                            intro: 'Next, we\'ll take you to your Registered Items page to continue the tour.'
                        }
                    ]
                }).oncomplete(function() {
                    localStorage.setItem('gate_tour_items_pending', 'true');
                    gateTourNavigate('<?= base_url('student/registered-items') ?>');
                }).onexit(function() {
                    // Cancelled — chain simply stops here, nothing further to clean up.
                }).start();
            }
        }

        window.showInlineAlert = function(message, type = 'danger') {
            const alertContainer = document.getElementById('alertContainer');
            if (alertContainer) {
                const icon = type === 'success' ? 'ti-check-circle' : 'ti-alert-circle';
                const bgClass = type === 'success' ? 'bg-success-subtle text-success border-success-subtle' : 'bg-danger-subtle text-danger border-danger-subtle';

                alertContainer.innerHTML = `
                    <div class="alert alert-dismissible fade show shadow-sm rounded-3 mb-4 d-flex align-items-center ${bgClass} border" role="alert" style="padding: 1rem 1.25rem;">
                        <i class="ti ${icon} fs-5 me-2"></i>
                        <span class="fw-medium" style="opacity: 0.9;">${message}</span>
                        <button type="button" class="btn-close m-0 ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                alertContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        window.toggleOtherCategory = function(select) {
            const wrapper = document.getElementById('otherCategoryWrapper');
            const input = document.getElementById('categoryOtherInput');
            const isOthers = select.value === 'Others';

            wrapper.classList.toggle('d-none', !isOthers);
            input.required = isOthers;
            if (!isOthers) input.value = '';
        };

        window.submitRegistration = async function(e, form) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
            submitBtn.classList.add('disabled');
            submitBtn.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (data.status === 'error') {
                    window.showInlineAlert(data.message, 'danger');
                    submitBtn.innerHTML = originalText;
                    submitBtn.classList.remove('disabled');
                    submitBtn.disabled = false;
                } else {
                    // Close the mobile modal, if that's where this was submitted from
                    const regModalEl = document.getElementById('itemRegistrationModal');
                    if (regModalEl) {
                        const regModalInstance = bootstrap.Modal.getInstance(regModalEl);
                        if (regModalInstance) regModalInstance.hide();
                    }

                    // Close the standalone-page sheet, if that's where this was submitted from
                    const container = document.getElementById('registration-container');
                    if (container) {
                        container.classList.add('reg-sheet-closing');
                    }

                    const successLink = document.createElement('a');
                    successLink.setAttribute('hx-get', '<?= base_url('student/dashboard') ?>?nocache=' + Date.now());
                    successLink.setAttribute('hx-push-url', '<?= base_url('student/dashboard') ?>');
                    successLink.setAttribute('hx-target', '#app-content');
                    successLink.setAttribute('hx-select', '#app-content');
                    successLink.setAttribute('hx-swap', 'outerHTML swap:300ms');

                    const injectAlert = function(event) {
                        if (event.detail.target.id === 'app-content') {
                            const appContent = event.detail.target;
                            const targetWrapper = appContent.querySelector('div') || appContent;

                            const alertHtml = `
                                <div class="alert alert-dismissible fade show shadow-sm rounded-3 mt-3 mt-lg-0 mx-3 mx-lg-0 mb-4 d-flex align-items-center bg-success-subtle text-success border border-success-subtle" role="alert" style="padding: 1rem 1.25rem;">
                                    <i class="ti ti-check-circle fs-5 me-2"></i>
                                    <span class="fw-medium" style="opacity: 0.9;">Item registered successfully! Awaiting admin verification.</span>
                                    <button type="button" class="btn-close m-0 ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;

                            targetWrapper.insertAdjacentHTML('afterbegin', alertHtml);
                            window.scrollTo({ top: 0, behavior: 'smooth' });

                            document.body.removeEventListener('htmx:afterSettle', injectAlert);
                        }
                    };

                    document.body.addEventListener('htmx:afterSettle', injectAlert);

                    document.body.appendChild(successLink);
                    htmx.process(successLink);
                    successLink.click();
                }
            } catch (err) {
                console.error("Submission failed:", err);
                window.showInlineAlert("A network error occurred. Please try again.", "danger");
                submitBtn.innerHTML = originalText;
                submitBtn.classList.remove('disabled');
                submitBtn.disabled = false;
            }
        };
    </script>
<?= $this->endSection() ?>