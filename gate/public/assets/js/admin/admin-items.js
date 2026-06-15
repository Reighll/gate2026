document.addEventListener('DOMContentLoaded', function() {

    // 1. REAL-TIME SEARCH FILTER
    const searchInput = document.getElementById('itemSearch');
    const tableRows = document.querySelectorAll('#itemsTable tbody tr:not(.no-data-row)');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase().trim();

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // 2. ADMIN IOT BRIDGE: Background Scanner for Modals
    setInterval(() => {
        // Check if any modal is currently open on the screen
        const openModal = document.querySelector('.modal.show');

        if (openModal) {
            const rfidInput = openModal.querySelector('input[name="rfid"]');
            const form = openModal.querySelector('form');

            if (rfidInput && form) {
                // Change placeholder to show we are actively listening to the ESP32
                rfidInput.placeholder = "Listening to Wi-Fi Scanner...";

                // Uses the scanApiUrl declared in the PHP view
                fetch(scanApiUrl, {
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // WE GOT A SCAN! Fill the input, flash green, and auto-submit!
                            rfidInput.value = data.epc;
                            rfidInput.classList.remove('border-success', 'text-success');
                            rfidInput.classList.add('bg-success', 'text-white');

                            setTimeout(() => {
                                form.submit();
                            }, 300); // 300ms delay just so the admin sees it happened
                        }
                    })
                    .catch(err => {
                        // Silent fail for network blips
                    });
            }
        }
    }, 1000); // Check every 1 second

});