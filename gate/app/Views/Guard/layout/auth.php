<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Guard Portal | Gatepass' ?></title>

    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/favicon.png') ?>" />

    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/icons/tabler-icons/tabler-icons.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/auth-background.css?v=12') ?>" />
</head>
<body class="auth-bg position-relative">

<a href="<?= base_url('/') ?>" class="btn btn-light shadow-sm rounded-pill position-absolute top-0 start-0 m-3 m-md-4 d-flex align-items-center gap-2 px-3 py-2 text-decoration-none text-dark fw-semibold" style="z-index: 999; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px);">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
    <span class="d-none d-sm-block">Home</span>
</a>

<!-- THE NEW SPLIT CARD WRAPPER -->
<div class="page-wrapper min-vh-100 d-flex align-items-center justify-content-center p-3 p-md-4">
    <div class="auth-card-wrapper shadow-lg">

        <!-- Left Side: Dark Gradient Logo & System Name -->
        <div class="auth-brand-panel flex-column text-center">
            <img src="<?= base_url('assets/images/logos/favicon.png') ?>" alt="Gatepass Logo" class="mb-4">
            <h6 class="text-white fw-bold mb-0" style="letter-spacing: 2px; opacity: 0.85;">GUEST AND TECHNOLOGY ENTRY</h6>
        </div>

        <!-- Right Side: Guard Login Form -->
        <div class="auth-form-panel">
            <?= $this->renderSection('content') ?>
        </div>

    </div>
</div>

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>