<?= $this->extend('Admin/layout/auth') ?>

<?= $this->section('title') ?>Admin Login | Gatepass System<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-8 col-lg-6 col-xxl-4">

                <div class="card auth-transition mb-0 border-0" style="border-radius: 20px;">
                    <div class="card-body p-4 p-sm-5">

                        <a href="#" class="text-nowrap logo-img text-center d-block pb-3 w-100">
                            <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Gatepass Logo">
                        </a>
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary mb-1">GATE - ADMIN</h3>
                        </div>

                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-danger p-2 text-center rounded-3 shadow-sm">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('admin/login/auth') ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <input type="text" class="form-control form-control-lg rounded-3" id="username" name="username" required autofocus>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control form-control-lg rounded-3" id="password" name="password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-4 rounded-3 fw-bold shadow-sm">Sign In</button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
<?= $this->endSection() ?>