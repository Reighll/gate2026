<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?></title>

    <style>
        #initial-loader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6fb;
        }
        html[data-bs-theme="dark"] #initial-loader {
            background: #11142d;
        }
        #initial-loader .ring {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid rgba(93, 135, 255, 0.25);
            border-top-color: #5d87ff;
            animation: il-spin 0.9s linear infinite;
        }
        @keyframes il-spin { to { transform: rotate(360deg); } }
        body.page-ready #initial-loader { display: none; }
    </style>

    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/favicon.png') ?>" />

    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/auth-background.css?v=13') ?>" />
    <link rel="manifest" href="<?= base_url('student/manifest.json') ?>">


    <script>
        const savedTheme = localStorage.getItem('theme') || localStorage.getItem('bs-theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    </script>
    <style>

        html[data-bs-theme="dark"],
        html[data-bs-theme="dark"] body {
            background-color: #11142d !important;
            color: #f1f9ff !important;
        }
    </style>
</head>
<body class="auth-bg position-relative">
<div id="initial-loader"><div class="ring"></div></div>
<a href="<?= base_url('/') ?>" class="btn btn-light shadow-sm rounded-pill position-absolute top-0 start-0 m-3 m-md-4 d-flex align-items-center gap-2 px-3 py-2 text-decoration-none text-dark fw-semibold" style="z-index: 999; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px);">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
    <span class="d-none d-sm-block">Home</span>
</a>

<div class="page-wrapper min-vh-100 d-flex align-items-center justify-content-center p-3 p-md-4">
    <div class="auth-card-wrapper shadow-lg">

        <div class="auth-brand-panel flex-column text-center">
            <img src="<?= base_url('assets/images/logos/favicon.png') ?>" alt="Gatepass Logo" class="mb-4">
            <h6 class="text-white fw-bold mb-0" style="letter-spacing: 2px; opacity: 0.85;">GUEST AND TECHNOLOGY ENTRY</h6>
        </div>

        <div class="auth-form-panel">
            <?= $this->renderSection('content') ?>
        </div>

    </div>
</div>

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/initial-loader.js') ?>"></script>
<script src="<?= base_url('assets/js/student/student-auth.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>