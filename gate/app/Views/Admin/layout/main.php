<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $this->renderSection('title') ?? 'Admin Portal | GATE System' ?></title>

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

    <link rel="stylesheet" href="<?= base_url('assets/css/admin/admin.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/admin-layout.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/custom-styles.css') ?>" />
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
<div id="initial-loader"><div class="ring"></div></div>
<div class="fixed-top-banner">
    <?= $this->include('Admin/partials/navbar') ?>
</div>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed">

    <?= $this->include('Admin/partials/sidebar') ?>

    <div class="body-wrapper">
        <div id="page-transition-loader" class="htmx-indicator" style="position: fixed; inset: 0; z-index: 1300; display: flex; align-items: center; justify-content: center; pointer-events: none;">
            <div class="loader-backdrop"></div>
            <div class="loader-ring"></div>
        </div>

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
<script src="<?= base_url('assets/js/admin/admin-items.js') ?>"></script>
<script src="<?= base_url('assets/js/admin/admin-visitors.js') ?>"></script>
<script src="<?= base_url('assets/js/mobile.js') ?>"></script>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<script src="<?= base_url('assets/js/initial-loader.js') ?>"></script>
<script src="<?= base_url('assets/js/swipe-gesture.js') ?>"></script>

<?= $this->renderSection('scripts') ?>

<?= $this->include('Admin/modals/delete_confirm') ?>

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
        const mobileFab = document.querySelector('.mobile-fab'); // Safe check, even if Admin doesn't have it

        // Admin only needs to hide the bar on Profile
        const shouldHideNav = currentPath.includes('profile');

        updateNavProfileVisibility();

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
        } hideMySkeletons();

        // --- 4. RE-INITIALIZE JAVASCRIPT ---
        window.dispatchEvent(new Event('load'));
        if (typeof jQuery !== 'undefined') {
            $(window).trigger('load');
        }
    });
</script>

<div id="swipe-stage">
    <div id="swipe-outgoing"></div>
    <div id="swipe-incoming"></div>
</div>

<script>
    /**
     * swipe-gesture.js
     * Handles Instagram-style swipe navigation for mobile views (<992px).
     * Uses HTMX for prefetching and smooth transitions.
     */
    (function () {
        // Configuration Constants
        const SWIPE_MIN_DISTANCE = 60; // Minimum drag required to trigger navigation
        const SWIPE_MAX_VERTICAL = 60; // Max vertical drift allowed before canceling
        const RESISTANCE = 0.35;       // Drag resistance before a valid target is found
        const PARALLAX = 0.3;          // Speed multiplier for the background layer
        const EDGE_GUARD = 24;         // Pixels from screen edge to ignore (prevents native iOS back swipe conflicts)
        const IGNORE_SELECTORS = '.modal, .table-responsive, .cropper-container, input[type="range"], [data-no-swipe]';

        let startX = 0, startY = 0, currentX = 0;
        let dragging = false;
        let isHorizontal = null;
        let stageWidth = 0;
        let direction = null;
        let targetLink = null;
        let prefetchedHTML = null;
        let prefetchXHR = null;
        let settleFallbackTimer = null;

        const stage = document.getElementById('swipe-stage');
        const outgoing = document.getElementById('swipe-outgoing');
        const incoming = document.getElementById('swipe-incoming');

        // Retrieve active and available bottom navigation items
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

        // Positions the swipe layers directly under the header and prevents the "shrinking" viewport bug via 100dvh
        function positionStage() {
            const header = document.querySelector('.fixed-top-banner');
            const appHeader = document.querySelector('.app-header');

            let topOffset = header ? header.getBoundingClientRect().bottom : 0;
            if (appHeader) {
                const r = appHeader.getBoundingClientRect();
                if (r.bottom > topOffset) topOffset = r.bottom;
            }
            stage.style.top = topOffset + 'px';
            stage.style.height = `calc(100dvh - ${topOffset}px)`;
        }

        function clearLayerState() {
            [outgoing, incoming].forEach(function (el) {
                el.classList.remove('swipe-top', 'swipe-back');
                el.style.transform = '';
                el.style.removeProperty('--dim-opacity');
            });
        }

        // Cleans up the DOM and cancels any active fetch requests after a swipe finishes
        function resetStage() {
            if (settleFallbackTimer) { clearTimeout(settleFallbackTimer); settleFallbackTimer = null; }
            document.removeEventListener('htmx:afterSettle', onRealSwapSettled);

            stage.classList.remove('active', 'snapping');
            clearLayerState();
            outgoing.innerHTML = '';
            outgoing.className = '';
            incoming.innerHTML = '';
            incoming.className = '';
            direction = null;
            targetLink = null;
            prefetchedHTML = null;
            if (prefetchXHR) { try { prefetchXHR.abort(); } catch (e) {} prefetchXHR = null; }
        }

        // Background XHR request to grab the next page's HTML before the user finishes swiping
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

        function extractAppContent(html) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const el = doc.getElementById('app-content');
            return el
                ? { html: el.innerHTML, className: el.className }
                : { html: html, className: '' };
        }

        function renderIncoming() {
            incoming.dataset.waiting = '0';
            if (prefetchedHTML) {
                const extracted = extractAppContent(prefetchedHTML);
                incoming.className = extracted.className;
                incoming.innerHTML = extracted.html;
            }
        }

        function onRealSwapSettled() {
            document.removeEventListener('htmx:afterSettle', onRealSwapSettled);
            if (settleFallbackTimer) { clearTimeout(settleFallbackTimer); settleFallbackTimer = null; }
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
            if (e.target.closest(IGNORE_SELECTORS)) return;
            if (!e.target.closest('#app-content')) return;

            const items = getNavItems();
            if (!items || items.length === 0) return;
            const currentIndex = getCurrentIndex(items);
            if (currentIndex === -1) return;

            const appContent = document.getElementById('app-content');
            if (!appContent) return;

            const touch = e.touches[0];

            // Edge guard to prevent conflict with native browser swipe-back
            if (touch.clientX < EDGE_GUARD || touch.clientX > window.innerWidth - EDGE_GUARD) return;

            startX = touch.clientX;
            startY = touch.clientY;
            currentX = startX;
            isHorizontal = null;
            dragging = true;
            stageWidth = window.innerWidth;

            positionStage();
            outgoing.className = appContent.className;
            outgoing.innerHTML = appContent.innerHTML;
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

            // Determine if the user is scrolling vertically or swiping horizontally
            if (isHorizontal === null) {
                if (Math.abs(deltaX) < 8 && Math.abs(deltaY) < 8) return;
                isHorizontal = Math.abs(deltaX) > Math.abs(deltaY);
                if (isHorizontal) {
                    stage.classList.add('active');
                    stage.classList.remove('snapping');
                }
            }

            if (!isHorizontal) return;
            e.preventDefault();

            const wantDirection = deltaX < 0 ? 'next' : 'prev';

            if (direction !== wantDirection) {
                direction = wantDirection;
                incoming.innerHTML = '';
                prefetchedHTML = null;
                if (prefetchXHR) { try { prefetchXHR.abort(); } catch (err) {} }

                if (direction === 'next' && this._nextItem) {
                    targetLink = this._nextItem;
                    startPrefetch(targetLink.getAttribute('href'));
                } else if (direction === 'prev' && this._prevItem) {
                    targetLink = this._prevItem;
                    startPrefetch(targetLink.getAttribute('href'));
                } else {
                    targetLink = null;
                }

                if (targetLink) {
                    assignLayerRoles(direction);
                    if (prefetchedHTML) renderIncoming();
                    else incoming.dataset.waiting = '1';
                } else {
                    clearLayerState();
                }
            }

            // Apply resistance if swiping at the end of the navigation list
            if (!targetLink) {
                const dragX = deltaX * RESISTANCE;
                currentX = dragX;
                outgoing.style.transform = `translateX(${dragX}px)`;
                return;
            }

            const progress = Math.min(1, Math.abs(deltaX) / stageWidth);
            currentX = deltaX;

            // Apply Parallax translation
            if (direction === 'next') {
                incoming.style.transform = `translateX(${stageWidth + deltaX}px)`;
                outgoing.style.transform = `translateX(${deltaX * PARALLAX}px)`;
                outgoing.style.setProperty('--dim-opacity', (progress * 0.22).toFixed(3));
            } else {
                outgoing.style.transform = `translateX(${deltaX}px)`;
                incoming.style.transform = `translateX(${-stageWidth * PARALLAX * (1 - progress)}px)`;
                incoming.style.setProperty('--dim-opacity', ((1 - progress) * 0.22).toFixed(3));
            }
        }, { passive: false });

        document.addEventListener('touchend', function () {
            if (!dragging) return;
            dragging = false;

            if (!isHorizontal) { resetStage(); return; }

            const deltaX = currentX;
            const clearedThreshold = Math.abs(deltaX) >= SWIPE_MIN_DISTANCE;

            // If threshold met, snap to new page and trigger HTMX
            if (clearedThreshold && targetLink) {
                stage.classList.add('snapping');

                if (direction === 'next') {
                    incoming.style.transform = 'translateX(0px)';
                    outgoing.style.transform = `translateX(${-stageWidth * PARALLAX}px)`;
                    outgoing.style.setProperty('--dim-opacity', '0.22');
                } else {
                    outgoing.style.transform = `translateX(${stageWidth}px)`;
                    incoming.style.transform = 'translateX(0px)';
                    incoming.style.setProperty('--dim-opacity', '0');
                }

                const linkToClick = targetLink;
                const container = document.querySelector('#app-content .page-transition-container');
                if (container) container.style.opacity = '0';

                setTimeout(function () {
                    linkToClick.click(); // Trigger real hx-boost navigation

                    settleFallbackTimer = setTimeout(resetStage, 1200); // Safety net timer
                    document.addEventListener('htmx:afterSettle', onRealSwapSettled);

                    setTimeout(function () {
                        if (container) container.style.opacity = '';
                    }, 350);
                }, 320);
            } else {
                // Cancel swipe and snap back
                stage.classList.add('snapping');

                if (direction === 'next') {
                    incoming.style.transform = `translateX(${stageWidth}px)`;
                    outgoing.style.transform = 'translateX(0px)';
                    outgoing.style.setProperty('--dim-opacity', '0');
                } else if (direction === 'prev') {
                    outgoing.style.transform = 'translateX(0px)';
                    incoming.style.transform = `translateX(${-stageWidth * PARALLAX}px)`;
                    incoming.style.setProperty('--dim-opacity', '0.22');
                } else {
                    outgoing.style.transform = 'translateX(0px)';
                }

                setTimeout(resetStage, 340);
            }
        }, { passive: true });

        document.addEventListener('touchcancel', function () {
            dragging = false;
            resetStage();
        }, { passive: true });
    })();
</script>
</body>
</html>