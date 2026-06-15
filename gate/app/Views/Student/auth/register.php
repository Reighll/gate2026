<?= $this->extend('Student/layout/auth') ?>

<?= $this->section('title') ?>Student Registration | Gatepass<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="page-wrapper min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card auth-transition w-100 shadow-lg border-0 rounded-4" style="max-width: 600px; margin: 15px;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary mb-1">GATE</h3>
                    <p class="text-muted fw-medium">Create your Gate Account</p>
                </div>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger p-3 text-center shadow-sm rounded-3 fw-bold">
                        <i class="ti ti-alert-triangle me-1"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('student/register/save') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label fw-semibold">First Name</label>
                            <input type="text" class="form-control form-control-lg bg-light rounded-3" id="first_name" name="first_name" required autofocus>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label fw-semibold">Last Name</label>
                            <input type="text" class="form-control form-control-lg bg-light rounded-3" id="last_name" name="last_name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="student_number" class="form-label fw-semibold">Student Number</label>
                        <input type="text" class="form-control form-control-lg bg-light rounded-3 font-monospace" id="student_number" name="student_number" placeholder="TUPT-XX-XXXX" required>
                    </div>

                    <div class="mb-3">
                        <label for="email_prefix" class="form-label fw-semibold">Email Address</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                            <input type="text" class="form-control bg-light border-end-0 text-end" id="email_prefix" placeholder="juan.delacruz" required autocomplete="off">
                            <span class="input-group-text bg-primary-subtle text-primary border-primary border-opacity-25 fw-bold" style="font-size: 0.85rem;">@tup.edu.ph</span>
                        </div>
                        <input type="hidden" name="email" id="full_email">
                        <div class="form-text mt-1"><i class="ti ti-info-circle"></i> Enter your username only.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                            <input type="password" class="form-control bg-light border-end-0" id="password" name="password" required>
                            <button class="btn btn-light border border-start-0 bg-light btn-toggle-pass px-3" type="button">
                                <i class="ti ti-eye fs-5 text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="department" class="form-label fw-semibold">Department</label>
                            <select class="form-select form-select-lg bg-light rounded-3" id="department" name="department" required>
                                <option value="" disabled selected>Select Dept</option>
                                <option value="BASD">BASD</option>
                                <option value="CAAD">CAAD</option>
                                <option value="EAAD">EAAD</option>
                                <option value="MAAD">MAAD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="year_level" class="form-label fw-semibold">Year Level</label>
                            <select class="form-select form-select-lg bg-light rounded-3" id="year_level" name="year_level" required>
                                <option value="" disabled selected>Select Year</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-4 rounded-3 fw-bold shadow-sm">Sign Up</button>

                    <div class="text-center">
                        <span class="text-muted">Already have an Account?</span>
                        <a class="text-primary fw-bold ms-1 text-decoration-none" href="<?= base_url('student/login') ?>">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>