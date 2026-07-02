<?= $this->extend('Guard/layout/main') ?>
<?= $this->section('title') ?>My Profile | Guard Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .cropper-container { max-height: 400px; }
    </style>

    <!-- FIXED: Added mt-5 and pt-4 to forcefully push the content down below the fixed navbar -->
    <div class="row mt-5 pt-4">
        <div class="col-12 col-lg-8 mx-auto">

            <div class="d-flex align-items-center mb-4">
                <a href="<?= base_url('guard/dashboard') ?>" class="btn btn-sm btn-light border shadow-sm rounded-circle me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                    <i class="ti ti-arrow-left fs-5 text-muted"></i>
                </a>
                <h4 class="fw-semibold mb-0">Guard Profile Settings</h4>
            </div>

            <!-- Alerts -->
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
                <div class="card-body p-4 p-md-5">
                    <form action="<?= base_url('guard/profile/update') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row mb-4">
                            <!-- Profile Picture Section -->
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <?php
                                $pic = !empty($guard['profile_pic']) ? $guard['profile_pic'] : 'default.png';
                                ?>
                                <img src="<?= base_url('uploads/profiles/' . $pic) ?>" alt="Profile Picture" id="profilePicPreview" class="rounded-circle img-fluid border border-3 border-primary shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">

                                <div>
                                    <label for="profile_pic" class="form-label fw-bold small text-muted">Change Picture</label>
                                    <input class="form-control form-control-sm" type="file" id="profile_pic" name="profile_pic" accept="image/png, image/jpeg, image/jpg">
                                </div>
                            </div>

                            <!-- Personal Details Section -->
                            <div class="col-md-8">
                                <h5 class="fw-bold text-primary mb-3">Guard Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-dark">First Name</label>
                                        <input type="text" class="form-control bg-light" name="first_name" value="<?= esc($guard['first_name'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-dark">Last Name</label>
                                        <input type="text" class="form-control bg-light" name="last_name" value="<?= esc($guard['last_name'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold text-dark">System Username</label>
                                        <input type="text" class="form-control bg-light font-monospace fw-bold" name="username" value="<?= esc($guard['username'] ?? '') ?>" required readonly>
                                        <small class="text-muted"><i class="ti ti-info-circle"></i> Usernames are assigned by the Administrator.</small>
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

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= base_url('guard/dashboard') ?>" class="btn btn-light btn-lg fw-bold text-muted px-3 px-md-4 rounded-3 border d-flex align-items-center justify-content-center text-nowrap">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg fw-bold px-3 px-md-5 shadow-sm rounded-3 d-flex align-items-center justify-content-center text-nowrap">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Cropper Modal -->
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

    <!-- Scripts Section for Cropper Logic -->
<?= $this->section('scripts') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="<?= base_url('assets/js/guard/guard-profile.js') ?>"></script>
<?= $this->endSection() ?>