<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Student/layout/htmx' : 'Student/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>My Profile | Student Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .cropper-container { max-height: 400px; }
    </style>

    <div id="profile-container" class="page-transition-container pt-5 mt-4 page-slide-in">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">

                <div class="d-flex align-items-center mb-4">
                    <a href="javascript:void(0);"
                       hx-get="<?= base_url('student/dashboard') ?>"
                       hx-target="#app-content"
                       hx-select="#app-content"
                       hx-push-url="true"
                       hx-swap="outerHTML swap:0ms"
                       hx-indicator="#page-transition-loader"
                       onclick="document.getElementById('profile-container').classList.add('page-slide-out');"
                       class="btn btn-sm btn-light border shadow-sm rounded-circle me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="ti ti-arrow-left fs-5 text-muted"></i>
                    </a>
                    <h4 class="fw-semibold mb-0">My Profile Settings</h4>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success shadow-sm border-0 mb-4 d-flex align-items-center rounded-3">
                        <i class="ti ti-check-circle fs-4 me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger shadow-sm border-0 mb-4 d-flex align-items-center rounded-3">
                        <i class="ti ti-alert-triangle fs-4 me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5 skeleton-wrapper">
                        <div class="row mb-4">
                            <div class="col-md-4 text-center mb-4 mb-md-0 d-flex flex-column align-items-center">
                                <div class="skeleton rounded-circle mb-3 border border-3 border-light" style="width: 150px; height: 150px; flex-shrink: 0;"></div>
                                <div class="skeleton skeleton-text w-50 mb-2"></div>
                                <div class="skeleton rounded-3 w-75" style="height: 30px;"></div>
                            </div>
                            <div class="col-md-8">
                                <div class="skeleton skeleton-title w-50 mb-4" style="height: 24px;"></div>
                                <div class="row">
                                    <div class="col-md-6 mb-4"><div class="skeleton skeleton-text w-50 mb-2"></div><div class="skeleton rounded-3 w-100" style="height: 40px;"></div></div>
                                    <div class="col-md-6 mb-4"><div class="skeleton skeleton-text w-50 mb-2"></div><div class="skeleton rounded-3 w-100" style="height: 40px;"></div></div>
                                    <div class="col-md-12 mb-4"><div class="skeleton skeleton-text w-25 mb-2"></div><div class="skeleton rounded-3 w-100" style="height: 40px;"></div></div>
                                    <div class="col-md-6 mb-4"><div class="skeleton skeleton-text w-50 mb-2"></div><div class="skeleton rounded-3 w-100" style="height: 40px;"></div></div>
                                    <div class="col-md-6 mb-4"><div class="skeleton skeleton-text w-50 mb-2"></div><div class="skeleton rounded-3 w-100" style="height: 40px;"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5 real-wrapper d-none">
                        <form action="<?= base_url('student/profile/update') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="row mb-4">
                                <div class="col-md-4 text-center mb-4 mb-md-0">
                                    <?php
                                    $pic = session()->get('profile_pic') ?? 'default.png';
                                    ?>
                                    <img src="<?= base_url('uploads/profiles/' . $pic) ?>" alt="Profile Picture" id="profilePicPreview" class="rounded-circle img-fluid border border-3 border-primary shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">

                                    <div>
                                        <label for="profile_pic" class="form-label fw-bold small text-muted">Change Picture</label>
                                        <input class="form-control form-control-sm" type="file" id="profile_pic" name="profile_pic" accept="image/png, image/jpeg, image/jpg">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <h5 class="fw-bold text-primary mb-3">Personal Details</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">First Name</label>
                                            <input type="text" class="form-control bg-light" name="first_name" value="<?= esc($student['first_name'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">Last Name</label>
                                            <input type="text" class="form-control bg-light" name="last_name" value="<?= esc($student['last_name'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label fw-bold text-dark">Email Address</label>
                                            <input type="email" class="form-control bg-light" name="email" value="<?= esc($student['email'] ?? '') ?>" required readonly>
                                            <small class="text-muted">Email cannot be changed.</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">Department</label>
                                            <select class="form-select bg-light" name="department" required>
                                                <option value="CAAD" <?= ($student['department'] ?? '') == 'CAAD' ? 'selected' : '' ?>>CAAD</option>
                                                <option value="BASD" <?= ($student['department'] ?? '') == 'BASD' ? 'selected' : '' ?>>BASD</option>
                                                <option value="EAAD" <?= ($student['department'] ?? '') == 'EAAD' ? 'selected' : '' ?>>EAAD</option>
                                                <option value="MAAD" <?= ($student['department'] ?? '') == 'MAAD' ? 'selected' : '' ?>>MAAD</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">Year Level</label>
                                            <select class="form-select bg-light" name="year_level" required>
                                                <option value="1st Year" <?= ($student['year_level'] ?? '') == '1st Year' ? 'selected' : '' ?>>1st Year</option>
                                                <option value="2nd Year" <?= ($student['year_level'] ?? '') == '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                                                <option value="3rd Year" <?= ($student['year_level'] ?? '') == '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                                                <option value="4th Year" <?= ($student['year_level'] ?? '') == '4th Year' ? 'selected' : '' ?>>4th Year</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 text-muted">

                            <h5 class="fw-bold text-primary mb-3">Change Password</h5>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Current Password</label>
                                <input type="password" class="form-control form-control-lg bg-light" name="current_password" placeholder="••••••••">
                                <div class="form-text mt-2"><i class="ti ti-info-circle"></i> Required only if you want to change your password. Leave blank to keep it unchanged.</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-dark">New Password</label>
                                    <input type="password" class="form-control form-control-lg bg-light" name="new_password" placeholder="••••••••">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-dark">Repeat New Password</label>
                                    <input type="password" class="form-control form-control-lg bg-light" name="confirm_password" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 gap-md-3 mt-4">
                                <a href="javascript:void(0);"
                                   hx-get="<?= base_url('student/dashboard') ?>"
                                   hx-target="#app-content"
                                   hx-select="#app-content"
                                   hx-push-url="true"
                                   hx-swap="outerHTML swap:0ms"
                                   hx-indicator="#page-transition-loader"
                                   onclick="document.getElementById('profile-container').classList.add('page-slide-out');"
                                   class="btn btn-light fw-bold text-muted px-3 px-md-4 py-2 rounded-3 border">Cancel
                                </a>
                                <button type="submit" class="btn btn-primary fw-bold px-4 px-md-5 py-2 shadow-sm rounded-3">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-primary" id="cropModalLabel"><i class="ti ti-crop me-2"></i>Crop Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center bg-light">
                    <div style="max-height: 400px; width: 100%; overflow: hidden;">
                        <img id="cropperImage" src="" style="max-width: 100%; display: block;">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light text-muted fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary fw-bold px-4" id="btnCrop">Crop & Apply</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script id="faceApiLib" hx-preserve="true" src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <script id="faceModelLoader" hx-preserve="true">
        // 0. Face Detection Setup
        // This script tag is marked hx-preserve so it loads the face-detection model
        // only ONCE per browser session, instead of every time this page is opened via htmx.
        // Model files must be placed at public/assets/models/ (see notes below the code).
        window.faceModelsReady = window.faceModelsReady || (async function loadFaceModels() {
            const MODEL_URL = '<?= base_url('assets/models') ?>';
            try {
                await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
                return true;
            } catch (err) {
                console.error('Face detection models failed to load:', err);
                return false;
            }
        })();

        window.imageHasFace = window.imageHasFace || async function imageHasFace(dataUrl) {
            const modelsLoaded = await window.faceModelsReady;
            if (!modelsLoaded) {
                // Fail-open: if the models can't load (e.g. offline), don't block the user.
                return true;
            }

            const img = new Image();
            img.src = dataUrl;
            await img.decode();

            const detection = await faceapi.detectSingleFace(img, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.4 }));
            return !!detection;
        };
    </script>

    <script>
        // 1. Skeleton Loaders
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }
        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // 2. The Cropper.js Logic
        function initProfileCropper() {
            const fileInput = document.getElementById('profile_pic');
            const cropperImage = document.getElementById('cropperImage');
            const previewImage = document.getElementById('profilePicPreview');
            const cropModalEl = document.getElementById('cropModal');
            const btnCrop = document.getElementById('btnCrop');

            if (!fileInput || fileInput.hasAttribute('data-cropper-init')) return;
            fileInput.setAttribute('data-cropper-init', 'true');

            let cropper = null;
            let bootstrapModal = new bootstrap.Modal(cropModalEl);

            fileInput.addEventListener('change', function (e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = async function (event) {
                        const dataUrl = event.target.result;

                        const hasFace = await imageHasFace(dataUrl);
                        if (!hasFace) {
                            alert('No face detected in this photo. Please upload a clear, front-facing picture of yourself.');
                            fileInput.value = '';
                            return;
                        }

                        cropperImage.src = dataUrl;
                        bootstrapModal.show();
                    };
                    reader.readAsDataURL(files[0]);
                }
            });

            cropModalEl.addEventListener('shown.bs.modal', function () {
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    autoCropArea: 1,
                    responsive: true,
                });
            });

            cropModalEl.addEventListener('hidden.bs.modal', function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                if (previewImage.getAttribute('data-updated') !== 'true') {
                    fileInput.value = '';
                }
                previewImage.removeAttribute('data-updated');
            });

            btnCrop.addEventListener('click', async function () {
                if (!cropper) return;

                const canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500,
                });

                const croppedDataUrl = canvas.toDataURL('image/jpeg');

                const hasFace = await imageHasFace(croppedDataUrl);
                if (!hasFace) {
                    alert('No face detected in the cropped area. Please adjust the crop so your face is fully visible.');
                    return;
                }

                previewImage.src = croppedDataUrl;
                previewImage.setAttribute('data-updated', 'true');

                canvas.toBlob(function (blob) {
                    const file = new File([blob], "cropped_profile.jpg", { type: "image/jpeg", lastModified: new Date().getTime() });
                    const container = new DataTransfer();
                    container.items.add(file);
                    fileInput.files = container.files;

                    bootstrapModal.hide();
                }, 'image/jpeg', 0.9);
            });
        }

        document.addEventListener("DOMContentLoaded", initProfileCropper);
        document.body.addEventListener('htmx:afterSettle', initProfileCropper);
    </script>
<?= $this->endSection() ?>