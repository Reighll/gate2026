<?= $this->extend('Guard/layout/auth') ?>

<?= $this->section('content') ?>

    <style>
        .guard-auth-wrapper {
            position: relative;
            z-index: 20 !important;
        }

        html, body {
            overflow-x: hidden !important;
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

    <div class="guard-auth-wrapper">
        <a href="#" class="text-nowrap logo-img text-center d-block pb-3 w-100 text-decoration-none">
            <h2 class="fw-bold text-primary mb-0">GATE</h2>
        </a>

        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary mb-1">GUARD PORTAL</h3>
            <p class="text-muted fw-medium">Enter your credentials</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger p-2 text-center rounded-3 shadow-sm"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success p-2 text-center rounded-3 shadow-sm"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('guard/login/auth') ?>" method="POST" accept-charset="UTF-8" class="w-100">

            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Username</label>
                <input type="text" class="form-control form-control-lg bg-light rounded-3" id="username" name="username" required autofocus autocomplete="off">
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>

                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                    <input type="password" class="form-control bg-light border-end-0" id="password" name="password" required>
                    <button class="btn btn-light border border-start-0 bg-light btn-toggle-pass px-3" type="button" id="togglePassword">
                        <i class="ti ti-eye fs-5 text-muted"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-2 rounded-3 fw-bold shadow-sm">Sign In</button>
        </form>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        });
    </script>
<?= $this->endSection() ?>