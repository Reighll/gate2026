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

        // Radius big enough to reach the furthest screen corner
        const endRadius = Math.hypot(
            Math.max(x, window.innerWidth - x),
            Math.max(y, window.innerHeight - y)
        );

        htmlElement.style.setProperty('--theme-x', `${x}px`);
        htmlElement.style.setProperty('--theme-y', `${y}px`);
        htmlElement.style.setProperty('--theme-r', `${endRadius}px`);

        document.startViewTransition(applyTheme);
    });

    function updateIcon(theme) {
        themeIcon.className = theme === 'dark' ? 'ti ti-sun' : 'ti ti-moon';
    }
});