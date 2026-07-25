            function hideMySkeletons() {
                setTimeout(() => {
                    document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                    document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
                }, 600);
            }

            document.addEventListener("DOMContentLoaded", hideMySkeletons);
            document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

            window.toggleEditMode = function(id) {
                const container = document.getElementById('modeContainer' + id);
                const viewPanel = document.getElementById('viewMode' + id);
                const editPanel = document.getElementById('editMode' + id);
                const editBtn = document.getElementById('editBtn' + id);
                const cancelBtn = document.getElementById('cancelBtn' + id); // NEW: Get Cancel button

                const showingView = !viewPanel.classList.contains('d-none');
                const outgoing = showingView ? viewPanel : editPanel;
                const incoming = showingView ? editPanel : viewPanel;

                // Lock the container to its current rendered height before anything changes
                container.style.height = container.offsetHeight + 'px';

                outgoing.classList.add('item-mode-panel-out');

                setTimeout(() => {
                    outgoing.classList.add('d-none');
                    outgoing.classList.remove('item-mode-panel-out');

                    incoming.classList.remove('d-none');
                    incoming.classList.add('item-mode-panel-in');

                    // NEW: Toggle Edit and Cancel button visibility
                    if (editBtn) {
                        editBtn.classList.toggle('d-none', showingView);
                    }
                    if (cancelBtn) {
                        cancelBtn.classList.toggle('d-none', !showingView);
                    }

                    // Measure the incoming panel's natural height, then animate the container to it
                    const targetHeight = incoming.scrollHeight;
                    // Force reflow so the browser registers the starting height before we change it
                    void container.offsetHeight;
                    container.style.height = targetHeight + 'px';

                    setTimeout(() => {
                        incoming.classList.remove('item-mode-panel-in');
                        // Release the fixed height so the modal can respond naturally afterward
                        container.style.height = 'auto';
                    }, 280);
                }, 180);
            };
