/**
 * Remove Item page: skeleton loader, the inline success/error alert
 * helper, and the background "unregister" AJAX flow with optimistic
 * UI (fades the item card out, shows the empty state if none remain).
 *
 * NOTE: this replaces an older, unrelated version of this file that
 * handled a single shared #unregisterModal populated via data-item-name/
 * data-action-url attributes. The page has since moved to one modal per
 * item plus processUnregister(btn, url, itemId) — the old file wasn't
 * linked from remove_item.php anymore and no longer matched the markup.
 */
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }

        window.addEventListener("load", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // --- NEW: Inline Alert System ---
        function showInlineAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            if (alertContainer) {
                // Pick the right icon based on success/error
                const icon = type === 'success' ? 'ti-check-circle' : 'ti-alert-triangle';

                // Build the HTML matching your dashboard's style
                const alertHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4 d-flex align-items-center" role="alert">
                        <i class="ti ${icon} fs-5 me-2 text-${type}"></i>
                        <span class="text-${type}-emphasis fw-medium">${message}</span>
                        <button type="button" class="btn-close m-0 ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Inject it above the card
                alertContainer.innerHTML = alertHTML;

                // Optional: Auto-hide the alert after 4 seconds so it doesn't sit there forever
                setTimeout(() => {
                    const alertNode = alertContainer.querySelector('.alert');
                    if (alertNode) {
                        const bsAlert = new bootstrap.Alert(alertNode);
                        bsAlert.close();
                    }
                }, 4000);
            }
        }

        // --- Background Processor ---
        window.processUnregister = async function(btn, url, itemId) {
            const originalText = btn.innerHTML;

            // Show loading state
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            btn.classList.add('disabled');
            btn.disabled = true;

            try {
                // Send the background request
                await fetch(url, {
                    method: 'GET',
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });

                // Close the modal cleanly
                const modalEl = document.getElementById('unregisterModal' + itemId);
                if (modalEl) {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalInstance.hide();
                }

                // Clean up grey backdrops
                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';

                    // TRIGGER THE INLINE ALERT HERE!
                    showInlineAlert("Item unregistration requested successfully!");
                }, 300);

                // Animate the item away
                const itemCard = document.getElementById('itemCard' + itemId);
                if (itemCard) {
                    itemCard.style.transition = 'all 0.4s ease';
                    itemCard.style.opacity = '0';
                    itemCard.style.transform = 'scale(0.9)';

                    setTimeout(() => {
                        itemCard.remove();

                        // Show empty state if needed
                        const remainingItems = document.querySelectorAll('.item-card');
                        if(remainingItems.length === 0) {
                            const emptyMsg = document.getElementById('noItemsMessage');
                            if(emptyMsg) emptyMsg.classList.remove('d-none');
                        }
                    }, 400);
                }

            } catch (error) {
                console.error("Error unregistering item:", error);
                btn.innerHTML = originalText;
                btn.classList.remove('disabled');
                btn.disabled = false;

                // Show a red inline alert if something breaks
                showInlineAlert("Something went wrong. Please try again.", "danger");
            }
        };
