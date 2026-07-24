<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Item Registration | Student Portal<?= $this->endSection() ?>
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
    <style>
        /* Drawer handle — mirrors the bottom-sheet handle used in registered-items modal */
        @media (max-width: 991.98px) {
            .reg-sheet-panel {
                position: relative;
            }
            .reg-sheet-panel::before {
                content: '';
                display: block;
                width: 40px;
                height: 4px;
                border-radius: 999px;
                background: #dbe0e6;
                margin: 0 auto 14px;
            }
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }
        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

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