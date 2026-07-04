<?= $this->extend('Student/layout/auth') ?>

<?= $this->section('title') ?>Student Portal | Gatepass<?= $this->endSection() ?>

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
        /* Override the hardcoded Home Button */
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
            transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .form-slide-in {
            transform: translateX(0);
            opacity: 1;
            position: relative !important;
            visibility: visible !important;
            z-index: 10 !important;
        }

        .form-slide-out-left {
            transform: translateX(-150%);
            opacity: 0;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            visibility: hidden !important;
            pointer-events: none;
            z-index: 1 !important;
        }

        .form-slide-out-right {
            transform: translateX(150%);
            opacity: 0;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            visibility: hidden !important;
            pointer-events: none;
            z-index: 1 !important;
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

<?php
$isRegister = (isset($show_register) && $show_register) || old('first_name') ? true : false;

$loginClass = $isRegister ? 'form-slide-out-left' : 'form-slide-in';
$registerClass = $isRegister ? 'form-slide-in' : 'form-slide-out-right';
?>

    <div id="login-view" class="auth-form-view <?= $loginClass ?>">

        <a href="#" class="text-nowrap logo-img text-center d-block pb-3 w-100 text-decoration-none">
            <h2 class="fw-bold text-primary mb-0">GATE</h2>
        </a>

        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary mb-1">STUDENT PORTAL</h3>
            <p class="text-muted fw-medium">Enter your Student Number</p>
        </div>

        <?php if (session()->getFlashdata('error') && !$isRegister) : ?>
            <div class="alert alert-danger p-2 text-center rounded-3 shadow-sm"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success p-2 text-center rounded-3 shadow-sm"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('student/login/auth') ?>" method="post" class="w-100">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Student Number</label>
                <input type="tel" class="form-control form-control-lg font-monospace bg-light rounded-3"
                       id="student_number" name="student_number"
                       value="<?= old('student_number') ?>"
                       placeholder="TUPT-XX-XXXX" required <?= !$isRegister ? 'autofocus' : '' ?>>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                    <input type="password" class="form-control bg-light border-end-0 login-pass" id="login_password" name="password" required>
                    <button class="btn btn-light border border-start-0 bg-light px-3 btn-toggle-pass" type="button">
                        <i class="ti ti-eye fs-5 text-muted"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-4 rounded-3 fw-bold shadow-sm">Sign In</button>

            <div class="text-center">
                <span class="text-muted">New student?</span>
                <a href="#" id="btn-to-register" data-target-url="<?= base_url('student/register') ?>" class="text-primary fw-bold ms-1 text-decoration-none p-2">Create Account</a>
            </div>
        </form>
    </div>


    <div id="register-view" class="auth-form-view <?= $registerClass ?>">

        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary mb-1">GATE</h3>
            <p class="text-muted fw-medium">Create your Gate Account</p>
        </div>

        <?php if (session()->getFlashdata('error') && $isRegister) : ?>
            <div class="alert alert-danger p-3 text-center shadow-sm rounded-3 fw-bold">
                <i class="ti ti-alert-triangle me-1"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('student/register/save') ?>" method="POST" class="w-100">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label fw-semibold">First Name</label>
                    <input type="text" class="form-control form-control-lg bg-light rounded-3" id="first_name" name="first_name" value="<?= old('first_name') ?>" required <?= $isRegister ? 'autofocus' : '' ?>>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label fw-semibold">Last Name</label>
                    <input type="text" class="form-control form-control-lg bg-light rounded-3" id="last_name" name="last_name" value="<?= old('last_name') ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="reg_student_number" class="form-label fw-semibold">Student Number</label>
                <input type="text" class="form-control form-control-lg bg-light rounded-3 font-monospace" id="reg_student_number" name="student_number" value="<?= old('student_number') ?>" placeholder="TUPT-XX-XXXX" required>
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
                <label for="reg_password" class="form-label fw-semibold">Password</label>
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                    <input type="password" class="form-control bg-light border-end-0" id="reg_password" name="password" required>
                    <button class="btn btn-light border border-start-0 bg-light px-3 btn-toggle-pass" type="button">
                        <i class="ti ti-eye fs-5 text-muted"></i>
                    </button>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label for="department" class="form-label fw-semibold">Department</label>
                    <select class="form-select form-select-lg bg-light rounded-3" id="department" name="department" required>
                        <option value="" disabled <?= empty(old('department')) ? 'selected' : '' ?>>Select Dept</option>
                        <option value="BASD" <?= old('department') == 'BASD' ? 'selected' : '' ?>>BASD</option>
                        <option value="CAAD" <?= old('department') == 'CAAD' ? 'selected' : '' ?>>CAAD</option>
                        <option value="EAAD" <?= old('department') == 'EAAD' ? 'selected' : '' ?>>EAAD</option>
                        <option value="MAAD" <?= old('department') == 'MAAD' ? 'selected' : '' ?>>MAAD</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="year_level" class="form-label fw-semibold">Year Level</label>
                    <select class="form-select form-select-lg bg-light rounded-3" id="year_level" name="year_level" required>
                        <option value="" disabled <?= empty(old('year_level')) ? 'selected' : '' ?>>Select Year</option>
                        <option value="1st Year" <?= old('year_level') == '1st Year' ? 'selected' : '' ?>>1st Year</option>
                        <option value="2nd Year" <?= old('year_level') == '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                        <option value="3rd Year" <?= old('year_level') == '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                        <option value="4th Year" <?= old('year_level') == '4th Year' ? 'selected' : '' ?>>4th Year</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-4 rounded-3 fw-bold shadow-sm">Sign Up</button>

            <div class="text-center">
                <span class="text-muted">Already have an Account?</span>
                <a href="#" id="btn-to-login" data-target-url="<?= base_url('student/login') ?>" class="text-primary fw-bold ms-1 text-decoration-none">Sign In</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Sliding & Height Logic
            const btnToRegister = document.getElementById('btn-to-register');
            const btnToLogin = document.getElementById('btn-to-login');
            const loginView = document.getElementById('login-view');
            const registerView = document.getElementById('register-view');
            const cardWrapper = document.querySelector('.auth-card-wrapper');

            // Enable smooth height transitions
            if(cardWrapper) {
                cardWrapper.style.transition = 'height 0.6s cubic-bezier(0.25, 1, 0.5, 1)';
            }

            function triggerSlide(target, url) {
                if(!cardWrapper) return;

                const startHeight = cardWrapper.offsetHeight;
                cardWrapper.style.height = startHeight + 'px';

                if (target === 'register') {
                    loginView.className = 'auth-form-view form-slide-out-left';
                    registerView.className = 'auth-form-view form-slide-in';
                } else {
                    registerView.className = 'auth-form-view form-slide-out-right';
                    loginView.className = 'auth-form-view form-slide-in';
                }

                cardWrapper.style.height = 'auto';
                const endHeight = cardWrapper.offsetHeight;

                cardWrapper.style.height = startHeight + 'px';
                cardWrapper.offsetHeight;
                cardWrapper.style.height = endHeight + 'px';

                setTimeout(() => { cardWrapper.style.height = 'auto'; }, 600);
                if (url) window.history.pushState({}, '', url);
            }

            if (btnToRegister) {
                btnToRegister.addEventListener('click', e => {
                    e.preventDefault();
                    triggerSlide('register', btnToRegister.getAttribute('data-target-url'));
                });
            }

            if (btnToLogin) {
                btnToLogin.addEventListener('click', e => {
                    e.preventDefault();
                    triggerSlide('login', btnToLogin.getAttribute('data-target-url'));
                });
            }
        });
    </script>

<?= $this->endSection() ?>