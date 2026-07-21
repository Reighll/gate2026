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