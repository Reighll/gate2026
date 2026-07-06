<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>Item Registration | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div id="registration-container" class="pt-5 mt-4 page-slide-in">
        <div class="d-flex align-items-center mb-3">
            <a href="javascript:void(0);"
               hx-get="<?= base_url('student/dashboard') ?>"
               hx-target="#app-content"
               hx-select="#app-content"
               hx-push-url="true"
               hx-swap="outerHTML swap:300ms"
               onclick="document.getElementById('registration-container').classList.add('page-slide-out');"
               class="btn btn-sm btn-light border shadow-sm rounded-circle me-3 d-flex d-lg-none align-items-center justify-content-center"
               style="width: 35px; height: 35px;">
                <i class="ti ti-arrow-left fs-5 text-muted"></i>
            </a>
            <h4 class="fw-semibold mb-0">Item Registration</h4>
        </div>

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
                        <select class="form-select" name="category" required>
                            <option value="" disabled <?= empty(old('category')) ? 'selected' : '' ?>>Select a category...</option>
                            <option value="Personal Computing & Mobile" <?= old('category') == 'Personal Computing & Mobile' ? 'selected' : '' ?>>Personal Computing & Mobile</option>
                            <option value="Photography & Videography" <?= old('category') == 'Photography & Videography' ? 'selected' : '' ?>>Photography & Videography</option>
                            <option value="Audio & Music Equipment" <?= old('category') == 'Audio & Music Equipment' ? 'selected' : '' ?>>Audio & Music Equipment</option>
                            <option value="Technical & Engineering Gear" <?= old('category') == 'Technical & Engineering Gear' ? 'selected' : '' ?>>Technical & Engineering Gear</option>
                            <option value="Art & Design Supplies" <?= old('category') == 'Art & Design Supplies' ? 'selected' : '' ?>>Art & Design Supplies</option>
                            <option value="Sporting & Fitness Equipment" <?= old('category') == 'Sporting & Fitness Equipment' ? 'selected' : '' ?>>Sporting & Fitness Equipment</option>
                            <option value="Large Portable Storage" <?= old('category') == 'Large Portable Storage' ? 'selected' : '' ?>>Large Portable Storage</option>
                            <option value="Bulky/Household Items" <?= old('category') == 'Bulky/Household Items' ? 'selected' : '' ?>>Bulky/Household Items</option>
                            <option value="Personal Mobility Devices" <?= old('category') == 'Personal Mobility Devices' ? 'selected' : '' ?>>Personal Mobility Devices</option>
                            <option value="Administrative/Office Use" <?= old('category') == 'Administrative/Office Use' ? 'selected' : '' ?>>Administrative/Office Use</option>
                        </select>
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

    <script>
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }
        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // Alert Injector
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
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        };

        // Form Processor
        // Form Processor
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
                    // Inject error and stop!
                    window.showInlineAlert(data.message, 'danger');
                    submitBtn.innerHTML = originalText;
                    submitBtn.classList.remove('disabled');
                    submitBtn.disabled = false;
                } else {
                    // SUCCESS: Setup the slide to Dashboard
                    const successLink = document.createElement('a');
                    successLink.setAttribute('hx-get', '<?= base_url('student/dashboard') ?>?nocache=' + Date.now());
                    successLink.setAttribute('hx-push-url', '<?= base_url('student/dashboard') ?>');
                    successLink.setAttribute('hx-target', '#app-content');
                    successLink.setAttribute('hx-select', '#app-content');
                    successLink.setAttribute('hx-swap', 'outerHTML swap:300ms');

                    // --- THE MOBILE-PROOF INJECTOR ---
                    const injectAlert = function(event) {
                        if (event.detail.target.id === 'app-content') {

                            // 1. Grab the very first container securely (ignoring CSS classes)
                            const appContent = event.detail.target;
                            const targetWrapper = appContent.querySelector('div') || appContent;

                            // 2. Added mobile-friendly margins (mt-3 mx-3) so it doesn't get stuck under mobile navbars!
                            const alertHtml = `
                                <div class="alert alert-dismissible fade show shadow-sm rounded-3 mt-3 mt-lg-0 mx-3 mx-lg-0 mb-4 d-flex align-items-center bg-success-subtle text-success border border-success-subtle" role="alert" style="padding: 1rem 1.25rem;">
                                    <i class="ti ti-check-circle fs-5 me-2"></i>
                                    <span class="fw-medium" style="opacity: 0.9;">Item registered successfully! Awaiting admin verification.</span>
                                    <button type="button" class="btn-close m-0 ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;

                            targetWrapper.insertAdjacentHTML('afterbegin', alertHtml);

                            // 3. Force mobile devices to scroll to the absolute top to see the alert!
                            window.scrollTo({ top: 0, behavior: 'smooth' });

                            document.body.removeEventListener('htmx:afterSettle', injectAlert);
                        }
                    };

                    document.body.addEventListener('htmx:afterSettle', injectAlert);

                    document.getElementById('registration-container').classList.add('page-slide-out');
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