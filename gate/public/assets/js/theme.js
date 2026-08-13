document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const htmlElement = document.documentElement;

    const currentTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-bs-theme', currentTheme);
    updateIcon(currentTheme);

    toggleBtn.addEventListener('click', () => {
        const theme = htmlElement.getAttribute('data-bs-theme');
        const newTheme = theme === 'dark' ? 'light' : 'dark';

        const applyTheme = () => {
            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
        };

        // Fallback for browsers without View Transitions support (older Firefox/Safari)
        if (!document.startViewTransition) {
            applyTheme();
            return;
        }

        // Origin point: center of the toggle button
        const rect = toggleBtn.getBoundingClientRect();
        const x = rect.left + rect.width / 2;
        const y = rect.top + rect.height / 2;

        // Radius that exactly reaches the furthest screen corner —
        // no padding multiplier, since animating extra unseen pixels
        // past the corner only adds paint cost for no visual benefit.
        const endRadius = Math.hypot(
            Math.max(x, window.innerWidth - x),
            Math.max(y, window.innerHeight - y)
        );

        htmlElement.style.setProperty('--theme-x', `${x}px`);
        htmlElement.style.setProperty('--theme-y', `${y}px`);
        htmlElement.style.setProperty('--theme-r', `${endRadius}px`);

        const transition = document.startViewTransition(applyTheme);

        // The mobile bottom nav / FAB use `transition: all` for their own
        // normal animations (active-pill expand, tap feedback). That also
        // catches the background-color/color flip the dark-mode stylesheet
        // applies on toggle, so without this they'd run their own ~0.4s
        // transition *at the same time* as the view transition reveal above
        // — two competing animations fighting for the same frame budget,
        // which is what was causing the extra lag on mobile specifically
        // (that nav only exists there). Freeze them for the duration.
        htmlElement.classList.add('theme-switching');
        transition.finished.finally(() => {
            htmlElement.classList.remove('theme-switching');
        });
    });

    function updateIcon(theme) {
        themeIcon.className = theme === 'dark' ? 'ti ti-sun' : 'ti ti-moon';
    }
});