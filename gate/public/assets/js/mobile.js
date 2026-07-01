document.addEventListener('DOMContentLoaded', function() {
    // Listen for any click/tap on the entire page
    document.addEventListener('click', function(event) {

        // Only run this logic on mobile and tablet screens (under 1200px)
        if (window.innerWidth < 1200) {
            const sidebar = document.querySelector('.left-sidebar');
            const hamburgerBtn = document.getElementById('headerCollapse');
            const mainWrapper = document.getElementById('main-wrapper');

            // Check if the sidebar is currently open
            const isSidebarOpen = mainWrapper.classList.contains('show-sidebar') || document.body.classList.contains('show-sidebar');

            if (isSidebarOpen) {
                // If the tap was NOT inside the sidebar AND NOT on the hamburger button itself
                if (sidebar && !sidebar.contains(event.target) && hamburgerBtn && !hamburgerBtn.contains(event.target)) {

                    // Close the sidebar
                    mainWrapper.classList.remove('show-sidebar');
                    document.body.classList.remove('show-sidebar');
                }
            }
        }
    });
});