<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?? 'Student Portal | GATE System' ?></title>

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
    <link rel="stylesheet" href="<?= base_url('assets/css/student/student.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/student/student-layout.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/custom-styles.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/initial-loader.css') ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="manifest" href="<?= base_url('student/manifest.json') ?>">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/fonts/tabler-icons.woff2" as="font" type="font/woff2" crossorigin>
    <script src="<?= base_url('assets/js/mobile.js') ?>"></script>
    <script src="https://unpkg.com/htmx.org@1.9.11"></script>
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

    <?= $this->renderSection('styles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/student/student-tour.css') ?>">

</head>
<body>
<div id="initial-loader"><div class="ring"></div></div>
<div class="fixed-top-banner">
    <span class="banner-title">
        <span class="full">Guest and Technology Entry</span>
        <span class="short">Guest and Technology Entry</span>
    </span>
    <?= $this->include('Student/partials/navbar') ?>
</div>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed">

    <?= $this->include('Student/partials/sidebar') ?>

    <div class="body-wrapper">
        <div id="page-transition-loader" class="htmx-indicator" style="position: fixed; inset: 0; z-index: 1300; display: flex; align-items: center; justify-content: center; pointer-events: none;">
            <div class="loader-backdrop"></div>
            <div class="loader-ring"></div>
        </div>

        <div class="container-fluid" id="app-content">
            <?= $this->renderSection('content') ?>
            <?= $this->renderSection('scripts') ?>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
<script src="<?= base_url('assets/js/sidebarmenu.js') ?>"></script>
<script src="<?= base_url('assets/js/app.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/simplebar/dist/simplebar.js') ?>"></script>
<script src="<?= base_url('assets/js/student/student.js') ?>"></script>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<script src="<?= base_url('assets/js/initial-loader.js') ?>"></script>
<script src="<?= base_url('assets/js/drawer.js') ?>"></script>
<script>
    window.gateTourRoutes = {
        registeredItems: '<?= base_url('student/items/registered') ?>',
        removeItem: '<?= base_url('student/items/remove') ?>',
        reportItem: '<?= base_url('student/items/report') ?>',
        history: '<?= base_url('student/items/history') ?>'
    };
</script>
<script src="<?= base_url('assets/js/student/student-app.js') ?>"></script>

<div id="swipe-stage">
    <div id="swipe-outgoing"></div>
    <div id="swipe-incoming"></div>
</div>

<script src="<?= base_url('assets/js/student/student-swipe.js') ?>"></script>
</body>
</html>