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

document.body.addEventListener('htmx:afterSwap', function (evt) {
    hideMySkeletons();
});

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

function gateTourApplyMobileDock(targetElement) {
    if (window.innerWidth > 991.98) return;

    if (!targetElement || targetElement === document.body) {
        document.body.classList.remove('gate-tour-dock-top');
        return;
    }

    const rect = targetElement.getBoundingClientRect();
    const targetMidY = rect.top + rect.height / 2;
    const inLowerHalf = targetMidY > window.innerHeight * 0.55;

    document.body.classList.toggle('gate-tour-dock-top', inLowerHalf);
}
window.gateTourApplyMobileDock = gateTourApplyMobileDock;

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

    if (window.innerWidth > 991.98 || !targetElement) {
        pointer.style.display = 'none';
        return;
    }

    function rectsOverlap(a, b) {
        return !(a.right < b.left || a.left > b.right || a.bottom < b.top || a.top > b.bottom);
    }

    function place() {
        const rect = targetElement.getBoundingClientRect();
        const coversViewport = rect.width >= window.innerWidth * 0.95 && rect.height >= window.innerHeight * 0.95;
        if (coversViewport || (rect.width === 0 && rect.height === 0)) {
            pointer.style.display = 'none';
            return;
        }

        const tooltipEl = document.querySelector('.introjs-tooltip');
        const tooltipRect = tooltipEl ? tooltipEl.getBoundingClientRect() : null;

        const dockedTop = document.body.classList.contains('gate-tour-dock-top');
        const rawX = rect.left + rect.width / 2;
        const clampedX = Math.min(Math.max(rawX, 20), window.innerWidth - 20);

        let pointerTop, arrowClass;

        if (dockedTop) {
            arrowClass = 'gate-tour-pointer--down';
            pointerTop = Math.max(8, rect.top - 34);
        } else {
            arrowClass = 'gate-tour-pointer--up';
            pointerTop = Math.min(window.innerHeight - 34, rect.bottom + 10);
        }

        if (tooltipRect) {
            const pointerRect = { left: clampedX - 14, right: clampedX + 14, top: pointerTop, bottom: pointerTop + 28 };
            if (rectsOverlap(pointerRect, tooltipRect)) {
                pointer.style.display = 'none';
                return;
            }
        }

        pointer.style.left = clampedX + 'px';
        pointer.style.bottom = 'auto';
        pointer.style.top = pointerTop + 'px';
        pointer.className = 'gate-tour-pointer ' + arrowClass;
        pointer.style.display = 'flex';
    }

    place();
    setTimeout(place, 150);
    setTimeout(place, 400);
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
        helperElementPadding: window.innerWidth <= 991.98 ? 4 : 10,
        nextLabel: 'Next',
        prevLabel: 'Back',
        doneLabel: doneLabel || 'Next Page &nbsp;<i class="ti ti-rocket"></i>',
        steps: steps
    }).oncomplete(function () {
        if (typeof onDone === 'function') onDone();
        if (nextFlagKey && nextUrl) {
            localStorage.setItem(nextFlagKey, 'true');
            gateTourNavigate(nextUrl);
        }
    }).onexit(function () {
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
                intro: 'Start by choosing what kind of item this is — a laptop, a personal computing device, or other equipment.',
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
    } else if (path.includes('items/registered')) {
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
            { element: document.querySelector('#gateTourSampleItem') || document.querySelector('.real-wrapper'), title: 'Item Status', intro: 'You can click on any item card here to view its full details, update its photo, or check its specific item status. (This one\'s just an example — yours will show up here once registered.)', position: 'top' },
            { title: 'Removing an item', intro: "Next, we'll take you to the Remove Item page in case you ever need to unregister something." }
        ], null, function () {
            gateTourRemoveSample('gateTourSampleItem', 'itemsEmptyStateCard');
        });
    } else if (path.includes('items/remove')) {
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
            { element: document.querySelector('#gateTourSampleRemoveItem') || document.querySelector('.real-wrapper'), title: 'Request Removal', intro: 'Tap "Unregister" on any item card, provide a reason for removing the item, and an admin will review your request. (This one\'s just an example.)', position: 'top' },
            { title: 'Reporting a lost item', intro: "Next, we'll take you to the Report Item page — useful if something goes missing on campus." }
        ], null, function () {
            gateTourRemoveSample('gateTourSampleRemoveItem', 'removeItemEmptyState');
        });
    } else if (path.includes('items/report')) {
        runGateTourStep('gate_tour_report_pending', 'gate_tour_history_pending', window.gateTourRoutes.history, [
            { title: 'Report a Missing Item', intro: 'If your equipment goes missing on campus, report it here right away so security can be alerted.' },
            { element: document.querySelector('#item_id'), title: 'Select the Item', intro: 'Choose which of your registered items is missing.', position: 'bottom' },
            { element: document.querySelector('#location'), title: 'Last Known Location', intro: 'Tell us where you last had it, also use the notes for specific key details — this helps guards narrow down where to look.', position: 'top' },
            { title: 'One last stop!', intro: "Next, we'll take you to your Scan History page to finish the tour." }
        ]);
    } else if (path.includes('items/history')) {
        runGateTourStep('gate_tour_history_pending', null, null, [
            { title: 'Your Scan History', intro: 'Every time your items are tapped at the gate, it shows up here — a full log of your campus entries and exits.' },
            { title: "That's the tour!", intro: 'You now know your way around GATE. You can revisit any of these pages anytime from the menu.' }
        ], 'You\'re all set! &nbsp;<i class="ti ti-check"></i>');
    }
}

document.body.addEventListener('htmx:afterSettle', function (evt) {
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