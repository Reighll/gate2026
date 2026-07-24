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

    <!-- Intro.js — loaded once, globally, so the onboarding tour doesn't
         depend on per-page <script>/<link> tags surviving HTMX swaps. -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css">
    <style>
        :root {
            --tour-bg: #ffffff;
            --tour-border: none;
            --tour-title: #1e4db7;
            --tour-text: #546269;
            --tour-btn-top-border: 1px solid #ecf0f2;
            --tour-prev-color: #777e89;
            --tour-prev-hover: #11142d;
            --tour-next-bg: #1e4db7;
            --tour-next-color: #ffffff;
            --tour-next-hover: #183e92;
            --tour-bullet: #ced4da;
            --tour-bullet-active: #1e4db7;
            --tour-arrow: #ffffff;
            --tour-shadow: 0 16px 32px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
        }
        html[data-bs-theme="dark"] {
            --tour-bg: #223640;
            --tour-border: 1px solid #4f5467;
            --tour-title: #8bb4fa;
            --tour-text: #f1f9ff;
            --tour-btn-top-border: 1px solid #4f5467;
            --tour-prev-color: #a1aab2;
            --tour-prev-hover: #ffffff;
            --tour-next-bg: #8bb4fa;
            --tour-next-color: #11142d;
            --tour-next-hover: #a5c7ff;
            --tour-bullet: #4f5467;
            --tour-bullet-active: #8bb4fa;
            --tour-arrow: #223640;
            --tour-shadow: 0 16px 32px rgba(0, 0, 0, 0.4);
        }
        .introjs-tooltip {
            position: absolute !important;
            background: var(--tour-bg) !important;
            border: var(--tour-border) !important;
            border-radius: 16px !important;
            box-shadow: var(--tour-shadow) !important;
            padding: 24px !important;
            width: 340px !important;
            max-width: calc(100vw - 40px) !important;
            opacity: 1 !important;
            visibility: visible !important;
            z-index: 9999999 !important;
            box-sizing: border-box !important;
        }
        @media (max-width: 576px) {
            .introjs-tooltip { width: 90vw !important; padding: 18px !important; border-radius: 14px !important; }
            .introjs-tooltiptitle { font-size: 1.15rem !important; }
            .introjs-tooltiptext { font-size: 0.9rem !important; }
            .introjs-button { padding: 6px 16px !important; font-size: 0.85rem !important; }
        }
        .introjs-overlay {
            background-color: rgba(10, 12, 30, 0.97) !important;
            backdrop-filter: blur(3px) !important;
            -webkit-backdrop-filter: blur(3px) !important;
            z-index: 9999990 !important;
        }
        .introjs-helperLayer {
            background: rgba(0, 0, 0, 0.15) !important;
            border-radius: 12px !important;
            box-shadow: 0 0 0 0 transparent !important;
            border: 2px solid rgba(255, 255, 255, 0.6) !important;
            z-index: 9999995 !important;
        }
        .introjs-tooltipheader { padding: 0 !important; margin-bottom: 12px !important; }
        .introjs-tooltiptitle {
            font-family: "DM Sans", sans-serif !important;
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: var(--tour-title) !important;
            margin: 0 !important;
            line-height: 1.3 !important;
        }
        .introjs-tooltiptext {
            font-family: "DM Sans", sans-serif !important;
            font-size: 0.95rem !important;
            color: var(--tour-text) !important;
            line-height: 1.6 !important;
            padding: 0 0 20px 0 !important;
            margin: 0 !important;
        }
        .introjs-tooltipbuttons {
            border-top: var(--tour-btn-top-border) !important;
            padding-top: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }
        .introjs-button {
            font-family: "DM Sans", sans-serif !important;
            border-radius: 50px !important;
            padding: 8px 20px !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            text-shadow: none !important;
            background-image: none !important;
            text-decoration: none !important;
        }
        .introjs-prevbutton, .introjs-skipbutton {
            background: transparent !important;
            color: var(--tour-prev-color) !important;
            border: none !important;
            padding-left: 0 !important;
            box-shadow: none !important;
        }
        .introjs-prevbutton:hover, .introjs-skipbutton:hover { color: var(--tour-prev-hover) !important; }
        .introjs-nextbutton, .introjs-donebutton {
            background: var(--tour-next-bg) !important;
            color: var(--tour-next-color) !important;
            border: none !important;
            box-shadow: 0 4px 10px rgba(0,0,0, 0.15) !important;
        }
        .introjs-nextbutton:hover, .introjs-donebutton:hover {
            background: var(--tour-next-hover) !important;
            transform: translateY(-2px) !important;
        }
        .introjs-disabled { opacity: 0.4 !important; cursor: not-allowed !important; pointer-events: none !important; }
        .gate-tour-sample-badge {
            font-size: 0.6rem !important;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .introjs-bullets { display: flex !important; align-items: center !important; padding: 0 !important; margin: 0 !important; }
        .introjs-bullets ul li a {
            background: var(--tour-bullet) !important;
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            margin: 0 4px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .introjs-bullets ul li a.active { background: var(--tour-bullet-active) !important; width: 24px !important; border-radius: 10px !important; }
        .introjs-arrow.top { border-bottom-color: var(--tour-arrow) !important; }
        .introjs-arrow.bottom { border-top-color: var(--tour-arrow) !important; }
        .introjs-arrow.left { border-right-color: var(--tour-arrow) !important; }
        .introjs-arrow.right { border-left-color: var(--tour-arrow) !important; }
        @media (min-width: 992px) {
            .custom-register-tooltip { margin-top: -35px !important; }
            .custom-register-tooltip .introjs-arrow.left { top: 15px !important; margin-top: 0 !important; }
        }
        @media (max-width: 991px) {
            .custom-register-tooltip {
                left: auto !important;
                right: 15px !important;
                width: calc(100vw - 30px) !important;
                max-width: 350px !important;
            }
            .custom-register-tooltip .introjs-arrow { left: auto !important; right: 10px !important; margin-left: 0 !important; }
        }
    </style>

</head>
<body>
<div id="initial-loader"><div class="ring"></div></div>
<div class="fixed-top-banner">
    <h1 class="banner-title">
        <span class="full">Guest and Technology Entry</span>
        <span class="short">Tech Entry</span>
    </h1>
    <?= $this->include('Admin/partials/navbar') ?>
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

            <!--
                FIX: per-page scripts now render INSIDE #app-content.
                Previously this was rendered near the end of <body>, outside
                #app-content. Since HTMX navigation uses hx-select="#app-content"
                to pluck only that element out of the response, anything
                outside it (including this whole scripts section) was silently
                dropped on every HTMX-driven page swap — only the very first,
                full-page load ever actually ran per-page <script> blocks.
            -->
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
<script>
    function updateNavProfileVisibility() {
        const navProfileItem = document.getElementById('navProfileItem');
        if (!navProfileItem) return;
        const onProfilePage = window.location.pathname.includes('/profile');
        navProfileItem.classList.toggle('d-none', onProfilePage);
        navProfileItem.classList.toggle('d-flex', !onProfilePage);
    }
    let isInitialAppLoad = true;

    function hideMySkeletons() {
        if (isInitialAppLoad) {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
                isInitialAppLoad = false;
            }, 600);
        } else {
            document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
        }
    }

    document.addEventListener('DOMContentLoaded', updateNavProfileVisibility);

    window.addEventListener('load', hideMySkeletons);

    document.body.addEventListener('htmx:afterSwap', function(evt) {
        hideMySkeletons();
    });

    // ================================================================
    // GATE ONBOARDING TOUR — centralized dispatcher.
    //
    // This intentionally does NOT live in each page's own 'scripts'
    // section. Per-page <script> blocks are not guaranteed to
    // re-execute reliably when their markup arrives via an HTMX swap
    // (a well-known browser quirk: <script> elements inserted through
    // innerHTML-style parsing are marked inert unless explicitly
    // recreated — htmx's own swap handling for hx-select'd fragments
    // doesn't always trigger that recreation). That's why the tour
    // previously only "showed up" after a hard refresh.
    //
    // Instead, all catch/handoff logic lives here, driven by the
    // htmx:afterSettle listener below — which is bound exactly once
    // at initial page load and simply re-runs on every swap, the same
    // way nav-highlighting and skeleton-hiding already reliably do.
    // ================================================================
    function gateTourNavigate(url) {
        const link = document.createElement('a');
        link.setAttribute('href', 'javascript:void(0);');
        link.setAttribute('hx-get', url);
        link.setAttribute('hx-target', '#app-content');
        link.setAttribute('hx-select', '#app-content');
        link.setAttribute('hx-push-url', 'true');
        link.setAttribute('hx-swap', 'outerHTML swap:300ms');
        link.setAttribute('hx-indicator', '#page-transition-loader');
        document.body.appendChild(link);
        htmx.process(link);
        link.click();
    }

    // Temporarily swaps an empty-state block for a labelled "SAMPLE" example
    // during the tour, so the tour can actually show what the real thing
    // looks like even for a brand-new account with no data yet. Cleaned up
    // automatically once the segment finishes or is cancelled.
    function gateTourInjectSample(wrapperId, emptyStateId, sampleId, sampleHtml) {
        const wrapper = document.getElementById(wrapperId);
        if (!wrapper) return;
        const emptyState = document.getElementById(emptyStateId);
        if (emptyState) emptyState.classList.add('d-none');
        if (!document.getElementById(sampleId)) {
            wrapper.insertAdjacentHTML('afterbegin', sampleHtml);
        }
    }

    function gateTourRemoveSample(sampleId, emptyStateId) {
        const sample = document.getElementById(sampleId);
        if (sample) sample.remove();
        const emptyState = document.getElementById(emptyStateId);
        if (emptyState) emptyState.classList.remove('d-none');
    }

    function runGateTourStep(flagKey, nextFlagKey, nextUrl, steps, doneLabel, onDone) {
        if (typeof introJs === 'undefined') return;
        if (localStorage.getItem(flagKey) !== 'true') return;
        localStorage.removeItem(flagKey);

        introJs().setOptions({
            showProgress: false,
            showStepNumbers: false,
            showBullets: true,
            exitOnOverlayClick: false,
            keyboardNavigation: true,
            nextLabel: 'Next',
            prevLabel: 'Back',
            doneLabel: doneLabel || 'Next Page 🚀',
            steps: steps
        }).oncomplete(function () {
            if (typeof onDone === 'function') onDone();
            if (nextFlagKey && nextUrl) {
                localStorage.setItem(nextFlagKey, 'true');
                gateTourNavigate(nextUrl);
            }
        }).onexit(function () {
            // Cancelled — chain simply stops here, but still clean up any
            // sample placeholder that was injected for this segment.
            if (typeof onDone === 'function') onDone();
        }).start();
    }

    function checkGateTourHandoff() {
        const path = window.location.pathname;

        if (path.includes('item-registration')) {
            runGateTourStep('gate_tour_reg_pending', 'gate_tour_items_pending', '<?= base_url('student/registered-items') ?>', [
                {
                    title: 'Register Your Equipment',
                    intro: 'This is where you\'ll register any personal devices you plan to bring into the campus.'
                },
                {
                    element: document.querySelector('#categorySelect'),
                    title: 'Item Category',
                    intro: 'Start by choosing what kind of item this is — a laptop, phone, or other equipment.',
                    position: 'bottom'
                },
                {
                    element: document.querySelector('input[name="serial_number"]'),
                    title: 'Serial Number',
                    intro: 'This is used to verify your item at the gate, so make sure it matches your device exactly.',
                    position: 'top'
                },
                {
                    element: document.querySelector('input[name="photo"]'),
                    title: 'Item Photo',
                    intro: 'Upload a clear photo — this helps security staff visually confirm the item.',
                    position: 'top'
                },
                {
                    title: 'On to your item list!',
                    intro: 'Next, we\'ll take you to your Registered Items page to continue the tour.'
                }
            ]);
        } else if (path.includes('registered-items')) {
            if (localStorage.getItem('gate_tour_items_pending') === 'true') {
                const sampleItemHtml = `
                    <div class="row px-2 px-md-0" id="gateTourSampleItem">
                        <div class="col-6 col-md-6 col-xl-4 mb-3 mb-md-4 px-2">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                                <span class="badge bg-primary gate-tour-sample-badge position-absolute top-0 start-0 m-2 text-uppercase" style="z-index:2;">Sample</span>
                                <div class="bg-light d-flex align-items-center justify-content-center card-img-top" style="height: 150px;">
                                    <i class="ti ti-device-laptop text-muted opacity-50" style="font-size: 3rem;"></i>
                                </div>
                                <div class="card-body d-flex flex-column p-3 p-md-4">
                                    <div class="mb-2"><span class="badge bg-success px-2 py-1 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">approved</span></div>
                                    <h6 class="fw-bold mb-1 text-dark text-truncate" style="font-size: 1rem;">Acer Predator Helios 300</h6>
                                    <div class="mb-2"><span class="fw-bolder text-dark" style="font-size: 0.85rem;">SN-EXAMPLE-0001</span></div>
                                    <div class="d-flex flex-column flex-md-row text-muted mb-2 gap-1 gap-md-2 fw-medium" style="font-size: 0.8rem;">
                                        <div class="d-flex align-items-center text-truncate"><i class="ti ti-category me-1 fs-5"></i><span class="text-truncate">Personal Computing Device</span></div>
                                        <div class="d-flex align-items-center text-truncate"><i class="ti ti-nfc me-1 fs-5 text-primary"></i><span class="text-truncate">04:AB:22:FF</span></div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="btn btn-primary w-100 fw-bold py-2 rounded-3" style="pointer-events: none; font-size: 0.85rem;">
                                            <i class="ti ti-building fs-5 me-1"></i> Inside <span class="d-none d-md-inline">Campus</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                gateTourInjectSample('registeredItemsRealWrapper', 'itemsEmptyStateCard', 'gateTourSampleItem', sampleItemHtml);
            }

            runGateTourStep('gate_tour_items_pending', 'gate_tour_remove_pending', '<?= base_url('student/remove-item') ?>', [
                { title: 'Your Equipment Hub', intro: 'Welcome to the Registered Items page! This is where you can manage all the devices you bring into the GATE system.' },
                { element: document.querySelector('#gateTourSampleItem .card') || document.querySelector('.real-wrapper'), title: 'Item Status', intro: 'You can click on any item card here to view its full details, update its photo, or check its specific RFID status. (This one\'s just an example — yours will show up here once registered.)', position: 'top' },
                { title: 'Removing an item', intro: "Next, we'll take you to the Remove Item page in case you ever need to unregister something." }
            ], null, function () {
                gateTourRemoveSample('gateTourSampleItem', 'itemsEmptyStateCard');
            });
        } else if (path.includes('remove-item')) {
            if (localStorage.getItem('gate_tour_remove_pending') === 'true') {
                const sampleRemoveHtml = `
                    <div class="border rounded p-3 d-flex justify-content-between align-items-center mb-3 border-warning bg-light position-relative" id="gateTourSampleRemoveItem">
                        <span class="badge bg-primary gate-tour-sample-badge position-absolute top-0 end-0 m-2 text-uppercase">Sample</span>
                        <div>
                            <h6 class="fw-bold mb-0">Acer Predator Helios 300</h6>
                            <small class="text-muted">SN: SAMPLE-0001</small>
                        </div>
                        <button type="button" class="btn btn-outline-warning btn-sm fw-bold" disabled>Unregister</button>
                    </div>`;
                gateTourInjectSample('removeItemRealWrapper', 'removeItemEmptyState', 'gateTourSampleRemoveItem', sampleRemoveHtml);
            }

            runGateTourStep('gate_tour_remove_pending', 'gate_tour_report_pending', '<?= base_url('student/report-item') ?>', [
                { title: 'Unregistering an Item', intro: 'If you sell, lose, or stop using a device, this is where you request to have it removed from your gate pass.' },
                { element: document.querySelector('#gateTourSampleRemoveItem') || document.querySelector('.real-wrapper'), title: 'Request Removal', intro: 'Tap "Unregister" on any item card, and an admin will review your request. (This one\'s just an example.)', position: 'top' },
                { title: 'Reporting a lost item', intro: "Next, we'll take you to the Report Item page — useful if something goes missing on campus." }
            ], null, function () {
                gateTourRemoveSample('gateTourSampleRemoveItem', 'removeItemEmptyState');
            });
        } else if (path.includes('report-item')) {
            runGateTourStep('gate_tour_report_pending', 'gate_tour_history_pending', '<?= base_url('student/history') ?>', [
                { title: 'Report a Missing Item', intro: 'If your equipment goes missing on campus, report it here right away so security can be alerted.' },
                { element: document.querySelector('#item_id'), title: 'Select the Item', intro: 'Choose which of your registered items is missing.', position: 'bottom' },
                { element: document.querySelector('#location'), title: 'Last Known Location', intro: 'Tell us where you last had it — this helps guards narrow down where to look.', position: 'top' },
                { title: 'One last stop!', intro: "Next, we'll take you to your Scan History page to finish the tour." }
            ]);
        } else if (path.includes('history')) {
            runGateTourStep('gate_tour_history_pending', null, null, [
                { title: 'Your Scan History', intro: 'Every time your items are tapped at the gate, it shows up here — a full log of your campus entries and exits.' },
                { title: "That's the tour!", intro: 'You now know your way around GATE. You can revisit any of these pages anytime from the menu.' }
            ], "You're all set! 🎉");
        }
    }

    document.body.addEventListener('htmx:afterSettle', function(evt) {
        const currentPath = window.location.pathname;
        const bottomNav = document.querySelector('.mobile-bottom-nav');
        const mobileFab = document.querySelector('.mobile-fab');
        const shouldHideNav = currentPath.includes('item-registration') || currentPath.includes('profile');

        updateNavProfileVisibility();

        if (bottomNav) {
            bottomNav.classList.toggle('d-none', shouldHideNav);
            bottomNav.classList.toggle('d-flex', !shouldHideNav);
        }
        if (mobileFab) {
            mobileFab.classList.toggle('d-none', shouldHideNav);
            mobileFab.classList.toggle('d-flex', !shouldHideNav);
        }
        const activePath = window.location.pathname;
        const allNavLinks = document.querySelectorAll('.mobile-bottom-item, .sidebar-link');
        allNavLinks.forEach(link => {
            link.classList.remove('active');
            const linkPath = link.getAttribute('href');
            if (linkPath && linkPath !== "javascript:void(0)" && activePath.includes(new URL(link.href).pathname)) {
                link.classList.add('active');
            }
        });
        const preloader = document.querySelector('.preloader');
        if (preloader) {
            preloader.style.display = 'none';
        }
        window.dispatchEvent(new Event('load'));
        if (typeof jQuery !== 'undefined') {
            $(window).trigger('load');
        }

        checkGateTourHandoff();
    });
</script>

<div id="swipe-stage">
    <div id="swipe-outgoing"></div>
    <div id="swipe-incoming"></div>
</div>

<script>
    /**
     * Inline Swipe Gesture Logic
     * Features: iOS-Style Swipe, True Boundaries, Nav Sync, Forced Skeletons, Smart Abort, and Strict Animation Locks.
     */
    (function () {
        const SWIPE_MIN_DISTANCE = 60;
        const EDGE_GUARD = 24;
        const IGNORE_SELECTORS = '.modal, .table-responsive, .cropper-container, input[type="range"], [data-no-swipe]';

        let startX = 0, startY = 0, currentX = 0;
        let dragging = false;
        let isHorizontal = null;
        let stageWidth = 0;
        let direction = null;
        let targetLink = null;
        let prefetchedHTML = null;
        let prefetchXHR = null;

        // Timer Tracking Variables for Strict Cleanup
        let settleFallbackTimer = null;
        let snapTimer = null;
        let clickTimer = null;
        let opacityTimer = null;

        let currentGhostRect = null;
        let currentGhostStyle = null;
        let originalActiveLink = null;

        // UI State Lock
        let isNavigating = false;

        const stage = document.getElementById('swipe-stage');
        const outgoing = document.getElementById('swipe-outgoing');
        const incoming = document.getElementById('swipe-incoming');

        function getNavItems() {
            const nav = document.querySelector('.mobile-bottom-nav');
            if (!nav || nav.classList.contains('d-none')) return null;
            const style = window.getComputedStyle(nav);
            if (style.display === 'none' || style.visibility === 'hidden') return null;
            return Array.from(nav.querySelectorAll('.mobile-bottom-item'));
        }

        function getCurrentIndex(items) {
            return items.findIndex(item => item.classList.contains('active'));
        }

        function clearLayerState() {
            [outgoing, incoming].forEach(function (el) {
                el.classList.remove('swipe-top', 'swipe-back');
                el.style.transform = '';
                el.style.removeProperty('--dim-opacity');
            });
        }

        function resetStage() {
            // STRICT CLEANUP: Kill all pending background timers to prevent race conditions
            if (settleFallbackTimer) { clearTimeout(settleFallbackTimer); settleFallbackTimer = null; }
            if (snapTimer) { clearTimeout(snapTimer); snapTimer = null; }
            if (clickTimer) { clearTimeout(clickTimer); clickTimer = null; }
            if (opacityTimer) { clearTimeout(opacityTimer); opacityTimer = null; }

            document.removeEventListener('htmx:afterSettle', onRealSwapSettled);

            stage.classList.remove('active', 'snapping');
            clearLayerState();
            outgoing.innerHTML = '';
            incoming.innerHTML = '';
            incoming.style.display = '';
            direction = null;
            targetLink = null;
            prefetchedHTML = null;
            currentX = 0;

            // Unlock the UI so the user can swipe again
            isNavigating = false;

            if (prefetchXHR) { try { prefetchXHR.abort(); } catch (e) {} prefetchXHR = null; }
        }

        function startPrefetch(url) {
            prefetchedHTML = null;
            const xhr = new XMLHttpRequest();
            prefetchXHR = xhr;
            xhr.open('GET', url, true);
            xhr.setRequestHeader('HX-Request', 'true');
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    prefetchedHTML = xhr.responseText;
                    if (incoming.dataset.waiting === '1') renderIncoming();
                }
            };
            xhr.send();
        }

        function buildGhost(htmlContent) {
            if (!currentGhostRect || !currentGhostStyle) return htmlContent;

            return `
            <div style="
                position: absolute;
                top: ${currentGhostRect.top}px;
                left: ${currentGhostRect.left}px;
                width: ${currentGhostRect.width}px;
                padding-top: ${currentGhostStyle.paddingTop};
                padding-right: ${currentGhostStyle.paddingRight};
                padding-bottom: ${currentGhostStyle.paddingBottom};
                padding-left: ${currentGhostStyle.paddingLeft};
                box-sizing: border-box;
            ">
                ${htmlContent}
            </div>
        `;
        }

        function renderIncoming() {
            incoming.dataset.waiting = '0';
            if (prefetchedHTML) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(prefetchedHTML, 'text/html');
                const el = doc.getElementById('app-content');

                if (el) {
                    el.querySelectorAll('.skeleton-wrapper').forEach(s => s.classList.remove('d-none'));
                    el.querySelectorAll('.real-wrapper').forEach(r => r.classList.add('d-none'));

                    const rawHTML = el.innerHTML;
                    incoming.innerHTML = buildGhost(rawHTML);
                } else {
                    incoming.innerHTML = buildGhost(prefetchedHTML);
                }
            }
        }

        function onRealSwapSettled() {
            document.removeEventListener('htmx:afterSettle', onRealSwapSettled);
            resetStage();
        }

        function assignLayerRoles(dir) {
            clearLayerState();
            if (dir === 'next') {
                incoming.classList.add('swipe-top');
                outgoing.classList.add('swipe-back');
            } else if (dir === 'prev') {
                outgoing.classList.add('swipe-top');
                incoming.classList.add('swipe-back');
            }
        }

        // --- TOUCH EVENT LISTENERS ---

        document.addEventListener('touchstart', function (e) {
            if (window.innerWidth >= 992) return;

            // Block new touches if the previous animation or HTMX request is still resolving
            if (isNavigating) return;

            if (e.target.closest(IGNORE_SELECTORS)) return;
            if (!e.target.closest('#app-content')) return;

            const items = getNavItems();
            if (!items || items.length === 0) return;
            const currentIndex = getCurrentIndex(items);
            if (currentIndex === -1) return;

            const appContent = document.getElementById('app-content');
            if (!appContent) return;

            const touch = e.touches[0];
            if (touch.clientX < EDGE_GUARD || touch.clientX > window.innerWidth - EDGE_GUARD) return;

            startX = touch.clientX;
            startY = touch.clientY;
            currentX = 0;
            isHorizontal = null;
            dragging = true;

            originalActiveLink = items[currentIndex];

            currentGhostRect = appContent.getBoundingClientRect();
            currentGhostStyle = window.getComputedStyle(appContent);

            let realBgColor = currentGhostStyle.backgroundColor;
            if (realBgColor === 'rgba(0, 0, 0, 0)' || realBgColor === 'transparent') {
                realBgColor = window.getComputedStyle(document.body).backgroundColor;
            }
            outgoing.style.backgroundColor = realBgColor;
            incoming.style.backgroundColor = realBgColor;

            stageWidth = window.innerWidth;
            stage.style.top = '0px';
            stage.style.left = '0px';
            stage.style.width = '100%';
            stage.style.height = '100dvh';

            outgoing.innerHTML = buildGhost(appContent.innerHTML);
            incoming.dataset.waiting = '1';

            const nextItem = currentIndex < items.length - 1 ? items[currentIndex + 1] : null;
            const prevItem = currentIndex > 0 ? items[currentIndex - 1] : null;

            if (nextItem) startPrefetch(nextItem.getAttribute('href'));

            this._nextItem = nextItem;
            this._prevItem = prevItem;
        }, { passive: true });

        document.addEventListener('touchmove', function (e) {
            if (!dragging) return;

            const touch = e.touches[0];
            const deltaX = touch.clientX - startX;
            const deltaY = touch.clientY - startY;

            if (isHorizontal === null) {
                if (Math.abs(deltaX) < 8 && Math.abs(deltaY) < 8) return;
                isHorizontal = Math.abs(deltaX) > Math.abs(deltaY);

                if (isHorizontal) {
                    const wantDir = deltaX < 0 ? 'next' : 'prev';
                    const intended = wantDir === 'next' ? this._nextItem : this._prevItem;

                    if (!intended) {
                        dragging = false;
                        resetStage();
                        return;
                    }

                    stage.classList.add('active');
                    stage.classList.remove('snapping');
                }
            }

            if (!isHorizontal) return;

            if (e.cancelable) {
                e.preventDefault();
            } else {
                dragging = false;
                resetStage();
                return;
            }

            const wantDirection = deltaX < 0 ? 'next' : 'prev';
            const intendedLink = wantDirection === 'next' ? this._nextItem : this._prevItem;

            if (direction !== wantDirection) {
                direction = wantDirection;
                incoming.innerHTML = '';
                prefetchedHTML = null;
                if (prefetchXHR) { try { prefetchXHR.abort(); } catch (err) {} }

                targetLink = intendedLink;
                if (targetLink) {
                    incoming.style.display = '';
                    startPrefetch(targetLink.getAttribute('href'));
                    assignLayerRoles(direction);
                    if (prefetchedHTML) renderIncoming();
                    else incoming.dataset.waiting = '1';

                    const navItems = getNavItems();
                    if (navItems) {
                        navItems.forEach(item => item.classList.remove('active'));
                        targetLink.classList.add('active');
                    }
                } else {
                    clearLayerState();
                    incoming.style.display = 'none';
                }
            }

            if (!targetLink) {
                currentX = 0;
                outgoing.style.transform = `translateX(0px)`;
                incoming.style.transform = `translateX(0px)`;
                return;
            }

            currentX = deltaX;

            if (direction === 'next') {
                incoming.style.transform = `translateX(${stageWidth + deltaX}px)`;
                outgoing.style.transform = `translateX(${deltaX}px)`;
                outgoing.style.setProperty('--dim-opacity', '0');
            } else {
                outgoing.style.transform = `translateX(${deltaX}px)`;
                incoming.style.transform = `translateX(${-stageWidth + deltaX}px)`;
                incoming.style.setProperty('--dim-opacity', '0');
            }
        }, { passive: false });

        document.addEventListener('touchend', function () {
            if (!dragging) return;
            dragging = false;

            if (!isHorizontal) { resetStage(); return; }

            // Lock the UI the millisecond the finger lifts, regardless of success or cancel
            isNavigating = true;

            const deltaX = currentX;
            const clearedThreshold = Math.abs(deltaX) >= SWIPE_MIN_DISTANCE;

            if (clearedThreshold && targetLink) {
                stage.classList.add('snapping');

                if (direction === 'next') {
                    incoming.style.transform = 'translateX(0px)';
                    outgoing.style.transform = `translateX(${-stageWidth}px)`;
                } else {
                    outgoing.style.transform = `translateX(${stageWidth}px)`;
                    incoming.style.transform = 'translateX(0px)';
                }

                const linkToClick = targetLink;
                const container = document.querySelector('#app-content .page-transition-container');
                if (container) container.style.opacity = '0';

                clickTimer = setTimeout(function () {
                    linkToClick.click();

                    // Reduced from 1200ms to 800ms for snappier recovery
                    settleFallbackTimer = setTimeout(resetStage, 800);
                    document.addEventListener('htmx:afterSettle', onRealSwapSettled);

                    opacityTimer = setTimeout(function () {
                        if (container) container.style.opacity = '';
                    }, 350);
                }, 320);
            } else {
                stage.classList.add('snapping');

                const navItems = getNavItems();
                if (navItems && originalActiveLink) {
                    navItems.forEach(item => item.classList.remove('active'));
                    originalActiveLink.classList.add('active');
                }

                if (direction === 'next' && targetLink) {
                    incoming.style.transform = `translateX(${stageWidth}px)`;
                    outgoing.style.transform = 'translateX(0px)';
                } else if (direction === 'prev' && targetLink) {
                    outgoing.style.transform = 'translateX(0px)';
                    incoming.style.transform = `translateX(${-stageWidth}px)`;
                } else {
                    outgoing.style.transform = 'translateX(0px)';
                }

                snapTimer = setTimeout(resetStage, 340);
            }
        }, { passive: true });

        document.addEventListener('touchcancel', function () {
            dragging = false;
            const navItems = getNavItems();
            if (navItems && originalActiveLink) {
                navItems.forEach(item => item.classList.remove('active'));
                originalActiveLink.classList.add('active');
            }
            resetStage();
        }, { passive: true });
    })();
</script>
</body>
</html>