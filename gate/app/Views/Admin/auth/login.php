<?= $this->extend('Admin/layout/auth') ?>

<?= $this->section('title') ?>Admin Login | Gatepass System<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <a href="#" class="text-nowrap logo-img text-center d-block pb-3 w-100 text-decoration-none">
        <h2 class="fw-bold text-primary mb-0">GATE</h2>
    </a>

    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary mb-1">ADMIN PORTAL</h3>
        <p class="text-muted fw-medium">Enter your credentials</p>
    </div>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger p-2 text-center rounded-3 shadow-sm">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

    <form action="<?= base_url('admin/login/auth') ?>" method="post" class="w-100">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="username" class="form-label fw-semibold">Username</label>
            <input type="text" class="form-control form-control-lg rounded-3" id="username" name="username" required autofocus>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input type="password" class="form-control form-control-lg rounded-3" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-0 rounded-3 fw-bold shadow-sm">Sign In</button>

    </form>

<?= $this->endSection() ?>