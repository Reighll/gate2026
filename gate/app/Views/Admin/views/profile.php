<?= $this->extend('Admin/layout/main') ?>

<?= $this->section('content') ?>

        <div class="d-flex align-items-center pt-5">
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                <i class="ti ti-arrow-left fs-5 text-muted"></i>
            </a>
            <h4 class="fw-bolder mb-0" style="color: #2a3547;">My Profile Settings</h4>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 p-md-5">

                <form action="<?= base_url('admin/profile/update') ?>" method="post">
                    <!-- Expanded the form to take up the full width (col-12) -->
                    <div class="row">
                        <div class="col-12">

                            <h6 class="fw-bold mb-3" style="color: #5d87ff; font-size: 1.1rem;">Personal Details</h6>

                            <div class="row mb-3">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label class="form-label fw-semibold text-dark">First Name</label>
                                    <input type="text" class="form-control form-control-lg bg-light shadow-none" style="border: 1px solid #eef2f6; border-radius: 8px;" name="first_name" value="<?= esc($admin['first_name']) ?>" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold text-dark">Last Name</label>
                                    <input type="text" class="form-control form-control-lg bg-light shadow-none" style="border: 1px solid #eef2f6; border-radius: 8px;" name="last_name" value="<?= esc($admin['last_name']) ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">Email Address</label>
                                <input type="email" class="form-control form-control-lg bg-light shadow-none" style="border: 1px solid #eef2f6; border-radius: 8px;" name="email" value="<?= esc($admin['email']) ?>">
                            </div>

                            <hr class="my-4" style="border-color: #eef2f6; opacity: 1;">

                            <h6 class="fw-bold mb-3" style="color: #5d87ff; font-size: 1.1rem;">Change Password</h6>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">New Password</label>
                                <input type="password" class="form-control form-control-lg bg-light shadow-none" style="border: 1px solid #eef2f6; border-radius: 8px;" name="password" placeholder="••••••••">
                                <div class="form-text mt-2 text-muted">
                                    <i class="ti ti-info-circle me-1"></i> Required only if you want to change your password. Leave blank to keep it unchanged.
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-5">
                                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-light fw-bold px-4 py-2" style="border-radius: 8px; color: #5a6a85;">Cancel</a>
                                <button type="submit" class="btn btn-primary fw-bold px-4 py-2 shadow-sm" style="border-radius: 8px; background-color: #2a3547; border: none;">Save Changes</button>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
<?= $this->endSection() ?>