<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?></title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/favicon.png') ?>" />

    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>" />
</head>
<body class="auth-bg position-relative">

<a href="<?= base_url('/') ?>" class="btn btn-light shadow-sm rounded-pill position-absolute top-0 start-0 m-3 m-md-4 d-flex align-items-center gap-2 px-3 py-2 text-decoration-none text-dark fw-semibold" style="z-index: 999; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px);">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
    <span class="d-none d-sm-block">Home</span>
</a>

<?= $this->renderSection('content') ?>

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>

<script src="<?= base_url('assets/js/student/student-auth.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>