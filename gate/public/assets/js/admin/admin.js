document.addEventListener('DOMContentLoaded', function () {

    // --- 1. SIDEBAR: Mobile Auto-Close ---
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const sidebarToggler = document.querySelector('#sidebarCollapse');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth < 1200 && sidebarToggler) {
                sidebarToggler.click();
            }
        });
    });

    // --- 2. BOOTSTRAP: Tooltips ---
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // --- 3. DASHBOARD: Toggle Custom Date Fields ---
    // Moved below to a delegated listener so it survives htmx page swaps
    // (this DOMContentLoaded block only ever fires once per hard load).

    // --- 4. ITEMS: Real-time Search Filter ---
    // Moved to admin-items.js (handles dash-insensitive matching + status
    // card filtering together). Kept out of here to avoid two competing
    // listeners fighting over the same #itemSearch input.

    // --- 5. RFID: Background Scanner Logic ---
    const scanForm = document.getElementById('scanForm');
    const rfidInput = document.getElementById('rfidInput');

    if (scanForm && rfidInput) {
        setInterval(() => {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                const modalRfidInput = openModal.querySelector('input[name="rfid"]');
                const form = openModal.querySelector('form');

                if (modalRfidInput && form) {
                    modalRfidInput.placeholder = "Listening to Wi-Fi Scanner...";

                    fetch(window.location.origin + '/admin/items/check-latest-scan', {
                        headers: { "X-Requested-With": "XMLHttpRequest" }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                modalRfidInput.value = data.epc;
                                modalRfidInput.classList.remove('border-success', 'text-success');
                                modalRfidInput.classList.add('bg-success', 'text-white');
                                setTimeout(() => { form.submit(); }, 300);
                            }
                        })
                        .catch(() => {}); // Silent fail
                }
            }
        }, 1000);
    }

    console.log("Admin JS Loaded successfully.");
});
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteConfirmModal');

    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;

            // Extract info from data-bs-* attributes
            const deleteUrl = button.getAttribute('data-bs-url');
            const customMessage = button.getAttribute('data-bs-message');

            // Update the modal's content
            const confirmBtn = deleteModal.querySelector('#deleteModalConfirmBtn');
            const messageText = deleteModal.querySelector('#deleteModalMessage');

            confirmBtn.href = deleteUrl; // Set the actual delete link
            if (customMessage) {
                messageText.textContent = customMessage; // Set the custom warning
            }
        });
    }
});

// ==========================================================================
// DASHBOARD: Date Filter — event-delegated on document.body so it survives
// htmx page swaps. Direct-binding to #dateFilter breaks after the first
// navigation because htmx replaces that element with a new node that never
// gets a listener attached. Delegation avoids that entirely: document.body
// itself isn't replaced by htmx, only the content inside it.
// ==========================================================================
if (!window.__dashboardFilterDelegated) {
    window.__dashboardFilterDelegated = true;

    document.body.addEventListener('change', function (e) {
        if (!e.target || e.target.id !== 'dateFilter') return;

        const customContainer = document.getElementById('customDateContainer');
        if (e.target.value === 'custom') {
            if (customContainer) customContainer.classList.remove('d-none');
            // Custom still needs both dates picked, so wait for the Filter button
        } else {
            if (customContainer) customContainer.classList.add('d-none');
            // Preset ranges (Today / 7 Days / Month / Year) apply instantly
            e.target.form.submit();
        }
    });
}