<?= $this->extend('Student/layout/auth') ?>

<?= $this->section('title') ?>Student Login - Gatepass<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="page-wrapper min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card auth-transition w-100 shadow-lg border-0 rounded-4" style="max-width: 420px; margin: 15px;">
            <div class="card-body p-5">
                <a href="#" class="text-nowrap logo-img text-center d-block pb-3 w-100">
                    <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Gatepass Logo">
                </a>
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary mb-1">STUDENT PORTAL</h3>
                    <p class="text-muted fw-medium">Enter your Student Number</p>
                </div>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger p-2 text-center rounded-3 shadow-sm"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success p-2 text-center rounded-3 shadow-sm"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('student/login/auth') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Student Number</label>
                        <input type="tel" class="form-control form-control-lg font-monospace bg-light rounded-3"
                               id="student_number" name="student_number"
                               placeholder="TUPT-XX-XXXX" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                            <input type="password" class="form-control bg-light border-end-0" id="password" name="password" required>
                            <button class="btn btn-light border border-start-0 bg-light btn-toggle-pass px-3" type="button">
                                <i class="ti ti-eye fs-5 text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-4 rounded-3 fw-bold shadow-sm">Sign In</button>

                    <div class="text-center">
                        <span class="text-muted">New student?</span>
                        <a href="<?= base_url('student/register') ?>" class="text-primary fw-bold ms-1 text-decoration-none">Create Account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>