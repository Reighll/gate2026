document.addEventListener('DOMContentLoaded', function () {
    const menuLinks = document.querySelectorAll('#v-pills-tab .nav-link');
    const offcanvasElement = document.getElementById('mobileMenuOffcanvas');

    // 1. CHECK MEMORY: Restore the saved tab on page reload
    const savedTab = localStorage.getItem('activeStudentTab');

    if (savedTab && savedTab.startsWith('#')) {
        const activeLink = document.querySelector(`#v-pills-tab .nav-link[data-bs-target="${savedTab}"]`) ||
            document.querySelector(`#v-pills-tab .nav-link[href="${savedTab}"]`);
        const targetPane = document.querySelector(savedTab);

        if (activeLink && targetPane) {
            // Forcibly remove 'active' and 'show' from ALL tabs and panes
            document.querySelectorAll('#v-pills-tab .nav-link').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active', 'show'));

            // Instantly inject 'active' into the saved tab and pane
            activeLink.classList.add('active');
            targetPane.classList.add('active', 'show');
        }
    }

    menuLinks.forEach(link => {
        // 2. SAVE MEMORY ON CLICK: Store the target when a tab is clicked/shown
        link.addEventListener('shown.bs.tab', function (event) {
            const target = event.target.getAttribute('data-bs-target') || event.target.getAttribute('href');
            if (target) {
                localStorage.setItem('activeStudentTab', target);
            }
        });

        // 3. AUTO-CLOSE MOBILE MENU: Keeps your existing mobile hide logic
        link.addEventListener('click', () => {
            if (window.innerWidth < 768 && offcanvasElement) {
                if (typeof bootstrap !== 'undefined') {
                    const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement) || new bootstrap.Offcanvas(offcanvasElement);
                    if (bsOffcanvas) bsOffcanvas.hide();
                }
            }
        });
    });
});