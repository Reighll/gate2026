document.addEventListener('DOMContentLoaded', () => {
    initDrawerSwipe();
});

// Re-initialize if navigating via HTMX or if new modals are injected
document.body.addEventListener('htmx:afterSettle', () => {
    initDrawerSwipe();
});

function initDrawerSwipe() {
    // Target both the custom registration sheet AND all Bootstrap modal contents
    const panels = document.querySelectorAll('.reg-sheet-panel, .modal-content');

    panels.forEach(panel => {
        // Prevent attaching multiple event listeners to the same panel
        if (panel.dataset.swipeInitialized) return;
        panel.dataset.swipeInitialized = 'true';

        let startY = 0;
        let currentY = 0;
        let isDragging = false;

        // 1. Detect when the user touches the panel
        panel.addEventListener('touchstart', (e) => {
            // Only initiate drag if the panel is scrolled to the very top.
            // This allows users to scroll down long lists without closing the modal!
            if (panel.scrollTop <= 0) {
                startY = e.touches[0].clientY;
                isDragging = true;
                panel.style.transition = 'none';
            }
        }, { passive: true });

        // 2. Track the finger movement
        panel.addEventListener('touchmove', (e) => {
            if (!isDragging) return;

            currentY = e.touches[0].clientY;
            const deltaY = currentY - startY;

            // Only move the panel if dragging DOWNWARDS
            if (deltaY > 0) {
                if (e.cancelable) e.preventDefault();
                panel.style.transform = `translateY(${deltaY}px)`;
            }
        }, { passive: false });

        // 3. Detect when the finger is released
        panel.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            isDragging = false;

            const deltaY = currentY - startY;

            // Re-enable smooth transition for the snap back or exit
            panel.style.transition = 'transform 0.3s cubic-bezier(0.32, 0.72, 0, 1)';

            // If dragged down more than 100 pixels, trigger the close action
            if (deltaY > 100) {
                panel.style.transform = `translateY(100%)`; // Push it off screen visually

                // Figure out WHICH type of modal we are closing
                if (panel.classList.contains('modal-content')) {
                    // It is a Bootstrap Modal -> Use Bootstrap's built-in close logic
                    const modalEl = panel.closest('.modal');
                    if (modalEl) {
                        const bsModal = bootstrap.Modal.getInstance(modalEl);
                        if (bsModal) bsModal.hide();
                    }
                } else {
                    // It is your custom Reg Sheet -> Click the HTMX close button
                    const closeBtn = document.getElementById('regSheetCloseBtn');
                    if (closeBtn) closeBtn.click();
                }
            } else {
                // If they didn't drag far enough, snap it gracefully back to the top
                panel.style.transform = '';
            }

            // Clean up the inline styles after the animation finishes (300ms)
            setTimeout(() => {
                if (panel) {
                    panel.style.transform = '';
                    panel.style.transition = '';
                }
            }, 300);
        });
    });
}