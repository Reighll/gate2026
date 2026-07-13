<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Guard Portal | Gatepass' ?></title>

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
    <link rel="stylesheet" href="<?= base_url('assets/css/icons/tabler-icons/tabler-icons.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/auth-background.css?v=12') ?>" />
    <link rel="manifest" href="<?= base_url('guard/manifest.json') ?>">

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

<?= $this->renderSection('scripts') ?>
</body>
</html>