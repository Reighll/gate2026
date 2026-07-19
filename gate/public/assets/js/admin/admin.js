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
    const dateFilter = document.getElementById('dateFilter');
    const customContainer = document.getElementById('customDateContainer');

    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            if (this.value === 'custom') {
                customContainer.classList.remove('d-none');
                // Custom still needs both dates picked, so wait for the Filter button
            } else {
                customContainer.classList.add('d-none');
                // Preset ranges (Today / 7 Days / Month / Year) apply instantly
                this.form.submit();
            }
        });
    }

    // --- 4. ITEMS: Real-time Search Filter ---
    const searchInput = document.getElementById('itemSearch');
    const itemsTable = document.getElementById('itemsTable');

    if (searchInput && itemsTable) {
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase().trim();
            const rows = itemsTable.querySelectorAll('tbody tr:not(.no-data-row)');

            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    }

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