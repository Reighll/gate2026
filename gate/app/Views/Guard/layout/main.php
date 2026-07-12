<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?? 'Guard Portal | GATE System' ?></title>

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

    <link rel="stylesheet" href="<?= base_url('assets/css/guard/guard.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/custom-styles.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css') ?>">
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
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
</head>

<body>
<div id="initial-loader"><div class="ring"></div></div>
<div class="fixed-top-banner">
    <?= $this->include('Guard/partials/navbar') ?>
</div>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed">

    <?= $this->include('Guard/partials/sidebar') ?>

    <div class="body-wrapper">
        <div id="page-transition-loader" class="htmx-indicator" style="position: fixed; inset: 0; z-index: 1300; display: flex; align-items: center; justify-content: center; pointer-events: none;">
            <div class="loader-backdrop"></div>
            <div class="loader-ring"></div>
        </div>

        <div class="container-fluid" id="app-content">
            <?= $this->renderSection('content') ?>
        </div>

        <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4">Guard GATE System</p>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sidebarmenu.js') ?>"></script>
<script src="<?= base_url('assets/js/app.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/simplebar/dist/simplebar.js') ?>"></script>
<script src="<?= base_url('assets/js/guard/guard.js') ?>"></script>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<script src="<?= base_url('assets/js/initial-loader.js') ?>"></script>

<script>
    document.body.addEventListener('htmx:configRequest', function(evt) {
        evt.detail.headers['Cache-Control'] = 'no-cache, no-store, must-revalidate';
        evt.detail.headers['Pragma'] = 'no-cache';
        evt.detail.headers['Expires'] = '0';
    });

    function updateNavProfileVisibility() {
        const navProfileItem = document.getElementById('navProfileItem');
        if (!navProfileItem) return;
        const onProfilePage = window.location.pathname.includes('/profile');
        navProfileItem.classList.toggle('d-none', onProfilePage);
        navProfileItem.classList.toggle('d-flex', !onProfilePage);
    }

    function hideMySkeletons() {
        setTimeout(() => {
            document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
        }, 600);
    }

    document.addEventListener('DOMContentLoaded', updateNavProfileVisibility);
    document.addEventListener('DOMContentLoaded', hideMySkeletons);

    document.body.addEventListener('htmx:afterSettle', function(evt) {

        // --- 1. DYNAMIC NAVBAR VISIBILITY ---
        const currentPath = window.location.pathname;
        const bottomNav = document.querySelector('.mobile-bottom-nav');

        // Guard only needs to hide the bar on Profile
        const shouldHideNav = currentPath.includes('profile');

        updateNavProfileVisibility();

        if (bottomNav) {
            bottomNav.classList.toggle('d-none', shouldHideNav);
            bottomNav.classList.toggle('d-flex', !shouldHideNav);
        }

        // --- 2. MOVE THE BLUE ACTIVE PILL (Foolproof URL Check) ---
        const activePath = window.location.pathname;
        const allNavLinks = document.querySelectorAll('.mobile-bottom-item, .sidebar-link');

        allNavLinks.forEach(link => {
            link.classList.remove('active');
            const linkPath = link.getAttribute('href');
            if (linkPath && linkPath !== "javascript:void(0)" && activePath.includes(new URL(link.href).pathname)) {
                link.classList.add('active');
            }
        });

        // --- 3. HIDE STUCK PRELOADERS/SKELETONS ---
        const preloader = document.querySelector('.preloader');
        if (preloader) {
            preloader.style.display = 'none';
        } hideMySkeletons();

        // --- 4. RE-INITIALIZE JAVASCRIPT ---
        window.dispatchEvent(new Event('load'));
        if (typeof jQuery !== 'undefined') {
            $(window).trigger('load');
        }
    });
</script>
</body>

</html>