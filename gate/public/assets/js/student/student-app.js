/**
 * Student app shell: skeleton loading, bottom-nav/profile-tab visibility,
 * and the GATE onboarding tour dispatcher (page-to-page handoff via
 * localStorage flags + the "sample data" injection helpers).
 *
 * Requires window.gateTourRoutes to be set (see Student/layout/main.php)
 * before this file loads, since the tour hands off between pages using
 * server-rendered URLs.
 *
 * Lives in the global <script> block of the layout (not a per-page
 * 'scripts' section) because per-page script blocks aren't guaranteed to
 * re-execute reliably across htmx swaps — see the note in main.php.
 */
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

// Mobile tooltips are fixed-positioned and docked to an edge (see
// student-tour.css) instead of anchored next to the target — that's
// what fixes them going off-screen. But a tooltip that's ALWAYS
// bottom-docked can end up covering its own target if that target is
// in the lower half of the screen. This flips the dock to the top in
// that case, via a class toggled on <body> that the CSS keys off of.
function gateTourApplyMobileDock(targetElement) {
    if (window.innerWidth > 991.98) return; // desktop keeps normal anchored positioning

    if (!targetElement || targetElement === document.body) {
        // No specific target (intro/outro steps) — bottom is always safe.
        document.body.classList.remove('gate-tour-dock-top');
        return;
    }

    const rect = targetElement.getBoundingClientRect();
    const targetMidY = rect.top + rect.height / 2;
    const inLowerHalf = targetMidY > window.innerHeight * 0.55;

    document.body.classList.toggle('gate-tour-dock-top', inLowerHalf);
}
window.gateTourApplyMobileDock = gateTourApplyMobileDock;

// Dynamic pointer: since the tooltip is now docked to a screen edge
// instead of anchored beside the target, Intro.js's own triangle arrow
// (which assumed anchored positioning) is hidden in CSS. This is its
// replacement — a single reusable triangle, fixed-positioned, that's
// re-measured and re-placed on every step so it keeps pointing at
// wherever the real target actually is, both horizontally (aligned to
// the target's center) and directionally (up from a bottom-docked
// tooltip, down from a top-docked one).
let gateTourPointerEl = null;

function gateTourGetPointerEl() {
    if (!gateTourPointerEl) {
        gateTourPointerEl = document.createElement('div');
        gateTourPointerEl.className = 'gate-tour-pointer';
        document.body.appendChild(gateTourPointerEl);
    }
    return gateTourPointerEl;
}

function gateTourPositionPointer(targetElement) {
    const pointer = gateTourGetPointerEl();

    if (window.innerWidth > 991.98 || !targetElement || targetElement === document.body) {
        pointer.style.display = 'none';
        return;
    }

    const tooltipEl = document.querySelector('.introjs-tooltip');
    if (!tooltipEl) {
        pointer.style.display = 'none';
        return;
    }

    const targetRect = targetElement.getBoundingClientRect();
    const tooltipRect = tooltipEl.getBoundingClientRect();
    // Read the dock direction straight off the tooltip's actual current
    // position rather than re-checking the body class — that class was
    // set earlier in onbeforechange, using the target's position BEFORE
    // Intro.js auto-scrolled it into view, so by the time this runs
    // (onafterchange, after the scroll) it can be stale even though the
    // CSS itself already rendered correctly off the same class. Reading
    // the live tooltip position instead can't desync from what's
    // actually on screen.
    const dockedTop = tooltipRect.top < window.innerHeight / 2;

    // Aim at the target's horizontal center, clamped so the pointer
    // itself never sits closer than 20px to a screen edge.
    const rawX = targetRect.left + targetRect.width / 2;
    const clampedX = Math.min(Math.max(rawX, 20), window.innerWidth - 20);

    pointer.style.left = clampedX + 'px';
    pointer.style.display = 'block';

    if (dockedTop) {
        // Tooltip is at the top, target is below it — point down, sit
        // just under the tooltip's bottom edge.
        pointer.className = 'gate-tour-pointer gate-tour-pointer--down';
        pointer.style.top = tooltipRect.bottom + 'px';
        pointer.style.bottom = 'auto';
    } else {
        // Tooltip is at the bottom, target is above it — point up, sit
        // just above the tooltip's top edge.
        pointer.className = 'gate-tour-pointer gate-tour-pointer--up';
        pointer.style.top = (tooltipRect.top - 10) + 'px';
        pointer.style.bottom = 'auto';
    }
}
window.gateTourPositionPointer = gateTourPositionPointer;

function gateTourHidePointer() {
    if (gateTourPointerEl) gateTourPointerEl.style.display = 'none';
}
window.gateTourHidePointer = gateTourHidePointer;

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
        doneLabel: doneLabel || 'Next Page <i class="fa-regular fa-rocket"></i>',
        steps: steps
    }).onbeforechange(function (targetElement) {
        gateTourApplyMobileDock(targetElement);
    }).onafterchange(function (targetElement) {
        gateTourPositionPointer(targetElement);
    }).oncomplete(function () {
        document.body.classList.remove('gate-tour-dock-top');
        gateTourHidePointer();
        if (typeof onDone === 'function') onDone();
        if (nextFlagKey && nextUrl) {
            localStorage.setItem(nextFlagKey, 'true');
            gateTourNavigate(nextUrl);
        }
    }).onexit(function () {
        document.body.classList.remove('gate-tour-dock-top');
        gateTourHidePointer();
        // Cancelled — chain simply stops here, but still clean up any
        // sample placeholder that was injected for this segment.
        if (typeof onDone === 'function') onDone();
    }).start();
}

function checkGateTourHandoff() {
    const path = window.location.pathname;

    if (path.includes('items/registration')) {
        runGateTourStep('gate_tour_reg_pending', 'gate_tour_items_pending', window.gateTourRoutes.registeredItems, [
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

        runGateTourStep('gate_tour_items_pending', 'gate_tour_remove_pending', window.gateTourRoutes.removeItem, [
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

        runGateTourStep('gate_tour_remove_pending', 'gate_tour_report_pending', window.gateTourRoutes.reportItem, [
            { title: 'Unregistering an Item', intro: 'If you sell, lose, or stop using a device, this is where you request to have it removed from your gate pass.' },
            { element: document.querySelector('#gateTourSampleRemoveItem') || document.querySelector('.real-wrapper'), title: 'Request Removal', intro: 'Tap "Unregister" on any item card, and an admin will review your request. (This one\'s just an example.)', position: 'top' },
            { title: 'Reporting a lost item', intro: "Next, we'll take you to the Report Item page — useful if something goes missing on campus." }
        ], null, function () {
            gateTourRemoveSample('gateTourSampleRemoveItem', 'removeItemEmptyState');
        });
    } else if (path.includes('report-item')) {
        runGateTourStep('gate_tour_report_pending', 'gate_tour_history_pending', window.gateTourRoutes.history, [
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
    const shouldHideNav = currentPath.includes('items/registration') || currentPath.includes('profile');

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