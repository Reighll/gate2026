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
</head>
<body>
<div id="initial-loader"><div class="ring"></div></div>
<div class="fixed-top-banner">
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
        </div>

        <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4">Student GATE System</p>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sidebarmenu.js') ?>"></script>
<script src="<?= base_url('assets/js/app.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/simplebar/dist/simplebar.js') ?>"></script>
<script src="<?= base_url('assets/js/student/student.js') ?>"></script>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<script src="<?= base_url('assets/js/initial-loader.js') ?>"></script>
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/student/sw.js', {
                scope: '/student/'
            })
                .then(reg => console.log('Student SW registered', reg))
                .catch(err => console.error('Student SW failed', err));
        });
    }

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
    window.addEventListener('load', hideMySkeletons);
    document.body.addEventListener('htmx:afterSettle', function(evt) {

        // --- 1. DYNAMIC NAVBAR VISIBILITY ---
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

        // --- 2. MOVE THE BLUE ACTIVE PILL ---
        const activePath = window.location.pathname;

        const allNavLinks = document.querySelectorAll('.mobile-bottom-item, .sidebar-link');

        allNavLinks.forEach(link => {
            link.classList.remove('active');

            const linkPath = link.getAttribute('href');
            if (linkPath && activePath.includes(new URL(link.href).pathname)) {
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
     * Inline Swipe Gesture Logic
     * Handles Instagram-style swipe navigation for mobile views (<992px).
     * Features: True DOM cloning, top:0 alignment, and strict state-machine boundaries.
     */
    (function () {
        const SWIPE_MIN_DISTANCE = 60;
        const PARALLAX = 0.3;
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
        let settleFallbackTimer = null;

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

        function positionStage() {
            // FIX 1: By pinning the stage to top: 0, the cloned DOM's internal padding
            // will mathematically align it perfectly with the real DOM behind the headers.
            stageWidth = window.innerWidth;
            stage.style.top = '0px';
            stage.style.height = '100dvh';
        }

        function clearLayerState() {
            [outgoing, incoming].forEach(function (el) {
                el.classList.remove('swipe-top', 'swipe-back');
                el.style.transform = '';
                el.style.removeProperty('--dim-opacity');
            });
        }

        function resetStage() {
            if (settleFallbackTimer) { clearTimeout(settleFallbackTimer); settleFallbackTimer = null; }
            document.removeEventListener('htmx:afterSettle', onRealSwapSettled);

            stage.classList.remove('active', 'snapping');
            clearLayerState();
            outgoing.innerHTML = '';
            incoming.innerHTML = '';
            direction = null;
            targetLink = null;
            prefetchedHTML = null;
            currentX = 0;
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

        function extractAppContent(html) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const el = doc.getElementById('app-content');
            if (el) {
                el.removeAttribute('id');
                return el.outerHTML;
            }
            return html;
        }

        function renderIncoming() {
            incoming.dataset.waiting = '0';
            if (prefetchedHTML) {
                incoming.innerHTML = extractAppContent(prefetchedHTML);
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
            if (touch.clientX < EDGE_GUARD || touch.clientX > window.innerWidth - EDGE_GUARD) return;

            startX = touch.clientX;
            startY = touch.clientY;
            currentX = 0;
            isHorizontal = null;
            dragging = true;

            positionStage();

            outgoing.innerHTML = '';
            const clone = appContent.cloneNode(true);
            clone.removeAttribute('id');
            outgoing.appendChild(clone);

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

            // FIX 2: Check for direction changes BEFORE returning on a dead end.
            // This ensures the memory is cleanly wiped if the user wiggles toward a boundary.
            if (direction !== wantDirection) {
                direction = wantDirection;
                incoming.innerHTML = '';
                prefetchedHTML = null;
                if (prefetchXHR) { try { prefetchXHR.abort(); } catch (err) {} }

                targetLink = intendedLink;
                if (targetLink) {
                    startPrefetch(targetLink.getAttribute('href'));
                    assignLayerRoles(direction);
                    if (prefetchedHTML) renderIncoming();
                    else incoming.dataset.waiting = '1';
                } else {
                    clearLayerState();
                }
            }

            // True Hard Wall
            if (!targetLink) {
                currentX = 0;
                outgoing.style.transform = `translateX(0px)`;
                return;
            }

            const progress = Math.min(1, Math.abs(deltaX) / stageWidth);
            currentX = deltaX;

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
                    linkToClick.click();

                    settleFallbackTimer = setTimeout(resetStage, 1200);
                    document.addEventListener('htmx:afterSettle', onRealSwapSettled);

                    setTimeout(function () {
                        if (container) container.style.opacity = '';
                    }, 350);
                }, 320);
            } else {
                stage.classList.add('snapping');

                if (direction === 'next' && targetLink) {
                    incoming.style.transform = `translateX(${stageWidth}px)`;
                    outgoing.style.transform = 'translateX(0px)';
                    outgoing.style.setProperty('--dim-opacity', '0');
                } else if (direction === 'prev' && targetLink) {
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