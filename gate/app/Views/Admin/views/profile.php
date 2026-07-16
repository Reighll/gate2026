<?php
$layout = service('request')->hasHeader('HX-Request') ? 'Admin/layout/htmx' : 'Admin/layout/main';
?>
<?= $this->extend($layout) ?>
<?= $this->section('title') ?>My Profile | Admin Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <div id="profile-container" class="page-transition-container pt-5 mt-4 page-slide-in">        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">

                <div class="d-flex align-items-center mb-4">
                    <a href="javascript:void(0);"
                       hx-get="<?= base_url('admin/dashboard') ?>"
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
                                <div class="skeleton skeleton-text w-75 mb-2"></div>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php for($i=0; $i<5; $i++): ?><div class="skeleton rounded-circle" style="width: 45px; height: 45px; flex-shrink: 0;"></div><?php endfor; ?>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="skeleton skeleton-title w-50 mb-3" style="height: 24px;"></div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="skeleton skeleton-text w-50 mb-2"></div>
                                        <div class="skeleton rounded-3 w-100" style="height: 40px;"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="skeleton skeleton-text w-50 mb-2"></div>
                                        <div class="skeleton rounded-3 w-100" style="height: 40px;"></div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="skeleton skeleton-text w-25 mb-2"></div>
                                        <div class="skeleton rounded-3 w-100" style="height: 40px;"></div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="skeleton skeleton-text w-25 mb-2"></div>
                                        <div class="skeleton rounded-3 w-100" style="height: 40px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5 real-wrapper d-none">
                        <form action="<?= base_url('admin/profile/update') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="row mb-4">
                                <div class="col-md-4 text-center mb-4 mb-md-0" style="position: relative; z-index: 20;">
                                    <?php
                                    $pic = !empty($admin['profile_pic']) ? $admin['profile_pic'] : 'user-1.jpg';
                                    ?>
                                    <img src="<?= base_url('assets/images/profile/' . $pic) ?>" alt="Profile Picture" id="profilePicPreview" class="rounded-circle img-fluid border border-3 border-primary shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">

                                    <div>
                                        <label class="form-label fw-bold small text-muted mb-2">Choose an Avatar</label>

                                        <input type="hidden" name="profile_pic" id="selected_profile_pic" value="<?= esc($pic) ?>">

                                        <div class="d-flex justify-content-center gap-2">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php $filename = 'user-' . $i . '.jpg'; ?>
                                                <img src="<?= base_url('assets/images/profile/' . $filename) ?>"
                                                     class="avatar-option rounded-circle border border-2 <?= $pic == $filename ? 'border-primary' : 'border-white' ?> shadow-sm"
                                                     style="width: 45px; height: 45px; object-fit: cover; cursor: pointer; transition: transform 0.2s;"
                                                     data-filename="<?= $filename ?>">
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="fw-bold text-primary mb-3">Personal Details</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">First Name</label>
                                            <input type="text" class="form-control bg-light" name="first_name" value="<?= esc($admin['first_name'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-dark">Last Name</label>
                                            <input type="text" class="form-control bg-light" name="last_name" value="<?= esc($admin['last_name'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label fw-bold text-dark">Email Address</label>
                                            <input type="email" class="form-control bg-light" name="email" value="<?= esc($admin['email'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label fw-bold text-dark">Username</label>
                                            <input type="text" class="form-control bg-light text-muted" value="<?= esc($admin['username'] ?? '') ?>" readonly>
                                            <small class="text-muted">Username cannot be changed.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 text-muted">

                            <h5 class="fw-bold text-primary mb-3">Change Password</h5>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Current Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control form-control-lg bg-light pe-5" name="current_password" placeholder="••••••••">
                                    <button type="button" class="btn toggle-password-btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-muted" tabindex="-1" style="z-index: 5; background: none; border: none;">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text mt-2"><i class="ti ti-info-circle"></i> Required only if you want to change your password. Leave blank to keep it unchanged.</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-dark">New Password</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control form-control-lg bg-light pe-5" name="new_password" placeholder="••••••••">
                                        <button type="button" class="btn toggle-password-btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-muted" tabindex="-1" style="z-index: 5; background: none; border: none;">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-dark">Repeat New Password</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control form-control-lg bg-light pe-5" name="confirm_password" placeholder="••••••••">
                                        <button type="button" class="btn toggle-password-btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-muted" tabindex="-1" style="z-index: 5; background: none; border: none;">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 gap-md-3 mt-4">
                                <a href="javascript:void(0);"
                                   hx-get="<?= base_url('admin/dashboard') ?>"
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

        // Show/hide password toggle (delegated - works for any .toggle-password-btn on this page)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.toggle-password-btn');
            if (!btn) return;

            const input = btn.previousElementSibling;
            const icon = btn.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        });
    </script>
    <script src="<?= base_url('assets/js/admin/admin-profile.js') ?>"></script>
<?= $this->endSection() ?>