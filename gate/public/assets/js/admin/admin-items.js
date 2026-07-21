// ==========================================================================
// 1. INSTANT UI UPDATE (Fixes the visual delay completely)
// ==========================================================================

// This fires the exact millisecond you click the button (before animation finishes)
document.addEventListener('show.bs.modal', function (event) {
    const modal = event.target;
    const rfidInput = modal.querySelector('input[name="rfid"]');
    if (rfidInput) {
        rfidInput.placeholder = "Listening to Wi-Fi Scanner..."; // Instant text change!
    }
});

// This fires after the animation finishes (Bootstrap requires this for focus)
document.addEventListener('shown.bs.modal', function (event) {
    const modal = event.target;
    const rfidInput = modal.querySelector('input[name="rfid"]');
    if (rfidInput) {
        rfidInput.focus();
    }
});

// ==========================================================================
// 2. ADMIN IOT BRIDGE: GLOBAL SCANNER
// ==========================================================================
if (!window.iotScannerRunning) {
    window.iotScannerRunning = true;
    let isSubmitting = false;

    setInterval(() => {
        const openModal = document.querySelector('.modal.show');

        if (openModal && !isSubmitting) {
            const rfidInput = openModal.querySelector('input[name="rfid"]');
            const form = openModal.querySelector('form.rfid-approval-form');

            // Make sure the input, form, and our global URL exist
            if (rfidInput && form && window.scanApiUrl) {

                // Poll the ESP32 via CodeIgniter
                fetch(window.scanApiUrl, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Cache-Control": "no-cache"
                    }
                })
                    .then(response => response.json())
                    .then(async data => {
                        if (data.status === 'success' && data.epc) {

                            // Lock the script so it doesn't double-submit
                            isSubmitting = true;

                            // Fill the input and flash green
                            rfidInput.value = data.epc;
                            rfidInput.classList.remove('border-success', 'text-success');
                            rfidInput.classList.add('bg-success', 'text-white');

                            // Show loading state
                            const submitBtn = form.querySelector('button[type="submit"]');
                            if (submitBtn) {
                                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                                submitBtn.disabled = true;
                            }

                            // Short pause so the user sees the success state
                            await new Promise(resolve => setTimeout(resolve, 400));

                            // SECURE POST & REFRESH
                            try {
                                await fetch(form.action, {
                                    method: 'POST',
                                    body: new FormData(form)
                                });
                                // Force bypass the cache with a fresh timestamp
                                window.location.href = window.location.pathname + "?refresh=" + new Date().getTime();
                            } catch (err) {
                                console.error('Submission failed', err);
                                isSubmitting = false;
                            }
                        }
                    })
                    .catch(err => { /* Silent fail for network blips */ });
            }
        }
    }, 1000); // Check every 1 second
}

// ==========================================================================
// 3. UI INITIALIZATION (Search Bar + Status Filter Cards)
// ==========================================================================

// Tracks which summary card (if any) is currently filtering the table.
// Lives outside initAdminItemsUI so it isn't reset on htmx re-init.
let activeStatusFilter = null;

// Applies both the text search and the active status card filter together.
function applyItemsFilters() {
    const table = document.getElementById('itemsTable');
    if (!table) return;

    const searchInput = document.getElementById('itemSearch');
    const term = searchInput ? searchInput.value.toLowerCase().trim() : '';
    // Strip dashes for comparison so "TUPT-23-0264" matches a search for
    // "230264" (no dash). This never removes a match that already worked —
    // it only adds matches, so names containing a dash (e.g. "Mary-Jane")
    // still match exactly as before.
    const termNoDash = term.replace(/-/g, '');

    const rows = table.querySelectorAll('tbody tr:not(.no-data-row)');
    let visibleCount = 0;

    rows.forEach(row => {
        const matchesStatus = !activeStatusFilter || row.dataset.status === activeStatusFilter;
        const rowTextNoDash = row.textContent.toLowerCase().replace(/-/g, '');
        const matchesSearch = !term || rowTextNoDash.includes(termNoDash);
        const show = matchesStatus && matchesSearch;

        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });

    // Show/hide a "no results" row when the search + filter combo matches nothing
    let emptyRow = table.querySelector('tbody tr.filter-empty-row');
    if (visibleCount === 0) {
        if (!emptyRow) {
            const tbody = table.querySelector('tbody.real-wrapper') || table.querySelector('tbody');
            if (tbody) {
                emptyRow = document.createElement('tr');
                emptyRow.className = 'filter-empty-row';
                const colCount = table.querySelectorAll('thead th').length || 9;
                emptyRow.innerHTML = '<td colspan="' + colCount + '" class="text-center py-5 text-muted">No items match this filter.</td>';
                tbody.appendChild(emptyRow);
            }
        } else {
            emptyRow.style.display = '';
        }
    } else if (emptyRow) {
        emptyRow.style.display = 'none';
    }
}

function initAdminItemsUI() {
    if (document.getElementById('itemsTable')) {
        applyItemsFilters(); // re-apply whatever filter/search state is active
    }
}

// Delegated listeners: bound once to document.body, which htmx never
// replaces (it only swaps the content inside it). This means the search
// bar and status cards keep working after any number of htmx page swaps,
// with no rebinding needed — the previous clone/replace approach only
// worked if 'htmx:afterSettle' actually fired and re-ran init, which
// wasn't happening reliably.
if (!window.__itemsFiltersDelegated) {
    window.__itemsFiltersDelegated = true;

    // --- Search bar ---
    document.body.addEventListener('input', function (e) {
        if (e.target && e.target.id === 'itemSearch') {
            applyItemsFilters();
        }
    });

    // --- Status filter cards: click ---
    document.body.addEventListener('click', function (e) {
        const card = e.target.closest('.status-filter-card');
        if (!card) return;

        const status = card.dataset.statusFilter;
        // Clicking the already-active card clears the filter
        activeStatusFilter = (activeStatusFilter === status) ? null : status;

        document.querySelectorAll('.status-filter-card').forEach(c => {
            c.classList.toggle('status-filter-active', c.dataset.statusFilter === activeStatusFilter);
        });

        applyItemsFilters();
    });

    // --- Status filter cards: keyboard accessibility ---
    document.body.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter' && e.key !== ' ') return;
        const card = e.target.closest ? e.target.closest('.status-filter-card') : null;
        if (!card) return;
        e.preventDefault();
        card.click();
    });
}

document.addEventListener('DOMContentLoaded', initAdminItemsUI);
document.body.addEventListener('htmx:afterSettle', initAdminItemsUI);