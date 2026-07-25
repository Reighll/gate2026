function initializeTermsModal() {
    const termsModalEl = document.getElementById('termsModal');
    if (!termsModalEl) return;

    if (termsModalEl.classList.contains('show')) return;

    const termsModal = new bootstrap.Modal(termsModalEl);
    termsModal.show();

    const acceptBtn = document.getElementById('btnAcceptTerms');
    if (acceptBtn) {
        acceptBtn.onclick = function () {
            const rememberChecked = document.getElementById('termsNeverShowAgain').checked;

            if (rememberChecked) {
                fetch(window.dashboardConfig.acceptTermsUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        [window.dashboardConfig.csrfHeader]: window.dashboardConfig.csrfHash
                    }
                }).catch(() => {});
            }

            termsModal.hide();
        };
    }

    termsModalEl.addEventListener('hidden.bs.modal', function () {
        startOnboardingTour();
    }, { once: true });
}

if (window.dashboardConfig && window.dashboardConfig.showTermsModal) {
    document.addEventListener('DOMContentLoaded', initializeTermsModal);
    document.body.addEventListener('htmx:afterSettle', initializeTermsModal);
}

function hideMySkeletons() {
    setTimeout(() => {
        document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));

        if (!window.dashboardConfig.showTermsModal) {
            startOnboardingTour();
        }
    }, 600);
}

function startOnboardingTour() {
    if (typeof introJs === 'undefined') return;

    const tourStorageKey = window.dashboardConfig.tourStorageKey;

    if (!localStorage.getItem(tourStorageKey)) {

        const isMobile = window.innerWidth < 992;

        let tourSteps = [
            {
                title: 'Welcome to GATE!',
                intro: 'Let us take a quick tour to show you around your new Student Dashboard.'
            },
            {
                element: document.querySelector('#tour-digital-id'),
                title: 'Your TUPT ID',
                intro: 'This is your official TUPT ID card. Security personnel may ask to see this when verifying your identity.',
                position: 'bottom'
            },
            {
                element: document.querySelector('#tour-campus-status'),
                title: 'Campus Status',
                intro: 'This updates automatically when you tap your registered items at the scanner, letting you know your items were inside.',
                position: 'top'
            }
        ];

        const navTarget = isMobile
            ? document.querySelector('.mobile-bottom-nav')
            : document.querySelector('.left-sidebar');

        if (navTarget) {
            tourSteps.push({
                element: navTarget,
                title: 'Exploring Other Pages',
                intro: 'Use this menu to navigate to your Registered Items list, view your Entry History, and check for any Violation alerts.',
                position: isMobile ? 'top' : 'right'
            });
        }

        tourSteps.push({
            title: 'Let\'s register your first item!',
            intro: 'Next, we\'ll take you to Item Registration to continue the tour.'
        });

        introJs().setOptions({
            showProgress: false,
            showStepNumbers: false,
            showBullets: true,
            exitOnOverlayClick: false,
            keyboardNavigation: true,
            nextLabel: 'Next',
            prevLabel: 'Back',
            doneLabel: 'Next Page <i class="ti ti-rocket"></i>',
            steps: tourSteps
        }).onbeforechange(function (targetElement) {
            if (typeof window.gateTourHidePointer === 'function') window.gateTourHidePointer();
            if (typeof window.gateTourApplyMobileDock === 'function') {
                window.gateTourApplyMobileDock(targetElement);
            }
        }).onafterchange(function (targetElement) {
            if (typeof window.gateTourPositionPointer === 'function') {
                window.gateTourPositionPointer(targetElement);
            }
        }).oncomplete(function () {
            document.body.classList.remove('gate-tour-dock-top');
            if (typeof window.gateTourHidePointer === 'function') window.gateTourHidePointer();

            localStorage.setItem(tourStorageKey, 'true');

            localStorage.setItem('gate_tour_reg_pending', 'true');

            gateTourNavigate(window.dashboardConfig.itemRegistrationUrl);
        }).onexit(function () {
            document.body.classList.remove('gate-tour-dock-top');
            if (typeof window.gateTourHidePointer === 'function') window.gateTourHidePointer();
            localStorage.setItem(tourStorageKey, 'true');
        }).start();
    }
}

document.addEventListener("DOMContentLoaded", hideMySkeletons);
document.body.addEventListener('htmx:afterSettle', hideMySkeletons);