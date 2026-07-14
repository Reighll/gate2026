<?= $this->extend('Student/layout/auth') ?>

<?= $this->section('title') ?>Reset Password | Gatepass<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <script>
        const savedTheme = localStorage.getItem('theme') || localStorage.getItem('bs-theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    </script>

    <style>
        /* Dark Mode fixes for Auth Wrappers */
        html[data-bs-theme="dark"] .auth-bg {
            background-color: #11142d !important;
        }
        html[data-bs-theme="dark"] .auth-card-wrapper,
        html[data-bs-theme="dark"] .card {
            background-color: #223640 !important;
            border: 1px solid #4f5467 !important;
        }
        html[data-bs-theme="dark"] .btn-home-back {
            background: rgba(34, 54, 64, 0.9) !important;
            border-color: #4f5467 !important;
            color: #f1f9ff !important;
        }
        html[data-bs-theme="dark"] .auth-brand-panel h6 {
            color: #f1f9ff !important;
        }

        html, body {
            overflow-x: hidden !important;
        }

        .auth-form-panel {
            position: relative !important;
            overflow: hidden !important;
            padding: 0 !important;
            display: flex !important;
            align-items: flex-start !important;
            flex-direction: row !important;
            width: 100% !important;
        }

        .auth-form-view {
            width: 100% !important;
            flex-shrink: 0 !important;
            padding: 60px 50px;
        }

        @media (max-width: 992px) {
            .auth-form-view { padding: 40px 20px 60px 20px !important; }
        }

        @media (max-width: 768px) {
            .card {
                margin-left: auto !important;
                margin-right: auto !important;
            }
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
                justify-content: center !important;
            }
        }
    </style>

    <div class="auth-form-view">

        <a href="<?= base_url('student/login') ?>" class="text-nowrap logo-img text-center d-block pb-3 w-100 text-decoration-none">
            <h2 class="fw-bold text-primary mb-0">GATE</h2>
        </a>

        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary mb-1">RESET PASSWORD</h3>
            <p class="text-muted fw-medium">Choose a new password for your account</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger p-2 text-center rounded-3 shadow-sm"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('student/reset-password/update') ?>" method="post" class="w-100">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= esc($token) ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">New Password</label>
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                    <input type="password" class="form-control bg-light border-end-0 login-pass" name="new_password" required minlength="8" autofocus>
                    <button class="btn btn-light border border-start-0 bg-light px-3 btn-toggle-pass" type="button">
                        <i class="ti ti-eye fs-5 text-muted"></i>
                    </button>
                </div>
                <div class="form-text mt-1"><i class="ti ti-info-circle"></i> Must be at least 8 characters.</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Confirm New Password</label>
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                    <input type="password" class="form-control bg-light border-end-0 login-pass" name="confirm_password" required minlength="8">
                    <button class="btn btn-light border border-start-0 bg-light px-3 btn-toggle-pass" type="button">
                        <i class="ti ti-eye fs-5 text-muted"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-4 rounded-3 fw-bold shadow-sm">Reset Password</button>

            <div class="text-center">
                <a href="<?= base_url('student/login') ?>" class="text-primary fw-bold text-decoration-none">
                    <i class="ti ti-arrow-left me-1"></i>Back to Sign In
                </a>
            </div>
        </form>
    </div>

<?= $this->endSection() ?>