<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $this->renderSection('title') ?? 'Admin Portal | GATE System' ?></title>

    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/favicon.png') ?>" />

    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>" />

    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-layout.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/custom-styles.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/admin.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css') ?>">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/fonts/tabler-icons.woff2" as="font" type="font/woff2" crossorigin>

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

<div class="fixed-top-banner">
    <?= $this->include('Admin/partials/navbar') ?>
</div>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed">

    <?= $this->include('Admin/partials/sidebar') ?>

    <div class="body-wrapper">
        <div class="container-fluid" id="app-content">
            <?= $this->renderSection('content') ?>
        </div>

        <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4 text-muted">Student GATE System - Admin Portal</p>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sidebarmenu.js') ?>"></script>
<script src="<?= base_url('assets/js/app.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/simplebar/dist/simplebar.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/admin.js') ?>"></script>
<script src="<?= base_url('assets/js/mobile.js') ?>"></script>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>

<?= $this->renderSection('scripts') ?>

<?= $this->include('Admin/modals/delete_confirm') ?>

<script>
    document.body.addEventListener('htmx:configRequest', function(evt) {
        evt.detail.headers['Cache-Control'] = 'no-cache, no-store, must-revalidate';
        evt.detail.headers['Pragma'] = 'no-cache';
        evt.detail.headers['Expires'] = '0';
    });
    document.body.addEventListener('htmx:afterSettle', function(evt) {

        // --- 1. DYNAMIC NAVBAR VISIBILITY ---
        const currentPath = window.location.pathname;
        const bottomNav = document.querySelector('.mobile-bottom-nav');
        const mobileFab = document.querySelector('.mobile-fab'); // Safe check, even if Admin doesn't have it

        // Admin only needs to hide the bar on Profile
        const shouldHideNav = currentPath.includes('profile');

        if (bottomNav) {
            bottomNav.classList.toggle('d-none', shouldHideNav);
            bottomNav.classList.toggle('d-flex', !shouldHideNav);
        }
        if (mobileFab) {
            mobileFab.classList.toggle('d-none', shouldHideNav);
            mobileFab.classList.toggle('d-flex', !shouldHideNav);
        }

        // --- 2. MOVE THE BLUE ACTIVE PILL (Foolproof URL Check) ---
        const activePath = window.location.pathname;
        const allNavLinks = document.querySelectorAll('.mobile-bottom-item, .sidebar-link');

        allNavLinks.forEach(link => {
            link.classList.remove('active');
            const linkPath = link.getAttribute('href');
            // Check if the link exists and matches the URL
            if (linkPath && linkPath !== "javascript:void(0)" && activePath.includes(new URL(link.href).pathname)) {
                link.classList.add('active');
            }
        });

        // --- 3. HIDE STUCK PRELOADERS/SKELETONS ---
        const preloader = document.querySelector('.preloader');
        if (preloader) {
            preloader.style.display = 'none';
        }

        // --- 4. RE-INITIALIZE JAVASCRIPT ---
        window.dispatchEvent(new Event('load'));
        if (typeof jQuery !== 'undefined') {
            $(window).trigger('load');
        }
    });
</script>
</body>
</html>