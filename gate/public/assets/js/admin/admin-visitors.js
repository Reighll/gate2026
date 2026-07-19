const myModal = document.getElementById('addPassModal');
const rfidInput = document.getElementById('rfidInput');
const scanForm = document.getElementById('scanForm');

// UI Elements
const scanState = document.getElementById('scanState');
const checkingState = document.getElementById('checkingState');
const errorState = document.getElementById('errorState');
const confirmState = document.getElementById('confirmState');

// Data Display Elements
const displayUID = document.getElementById('displayUID');
const existingOwner = document.getElementById('existingOwner');

// 1. Reset on Open
if (myModal) {
    myModal.addEventListener('shown.bs.modal', () => {
        resetScan();
    });
}

// 2. Intercept Scan
if (scanForm) {
    scanForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Stop form

        // Only process if we are waiting for a scan
        if (scanState.style.display !== 'none') {
            const scannedValue = rfidInput.value.trim();
            if (scannedValue) {
                checkDuplicate(scannedValue);
            }
        }
    });
}

// 3. AJAX Check Function
function checkDuplicate(rfid) {
    // UI: Show Loading
    scanState.style.display = 'none';
    checkingState.style.display = 'block';
    rfidInput.readOnly = true; // Lock input

    // AJAX Call
    fetch(window.location.origin + '/admin/visitors/check-tag?rfid=' + encodeURIComponent(rfid), {
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
        .then(response => response.json())
        .then(data => {
            checkingState.style.display = 'none';

            if (data.exists) {
                // DUPLICATE FOUND
                existingOwner.innerText = data.pass_number;
                errorState.style.display = 'block';
            } else {
                // CLEAN / NEW
                displayUID.innerText = rfid;
                confirmState.style.display = 'block';
            }
        })
        .catch(err => {
            console.error(err);
            alert("System Error during check. Please try again.");
            resetScan();
        });
}

// 4. Reset Function
function resetScan() {
    rfidInput.readOnly = false;
    rfidInput.value = '';

    // Hide all others, show scan
    checkingState.style.display = 'none';
    errorState.style.display = 'none';
    confirmState.style.display = 'none';
    scanState.style.display = 'block';

    setTimeout(() => { rfidInput.focus(); }, 100);
}

// 5. Final Submit
function finalSubmit() {
    scanForm.submit();
}

// 6. Focus Trap
document.addEventListener('click', function(e) {
    if (myModal && myModal.classList.contains('show') && scanState.style.display !== 'none') {
        if(!e.target.closest('button')) {
            rfidInput.focus();
        }
    }
});
function showId(url, idText) {
    // 1. Match the ID you have in your HTML (modalIdImage)
    const modalImg = document.getElementById('modalIdImage');

    // 2. Add safety check
    if (modalImg) {
        modalImg.src = url;
    } else {
        console.error("Error: Could not find element with ID 'modalIdImage'");
    }

    // 3. Update the text if the ID exists
    const modalText = document.getElementById('modalIdText');
    if (modalText) {
        modalText.textContent = idText;
    }
}

// 7. History Logs date filter (same behavior as the Admin Dashboard filter)
const visitorDateFilter = document.getElementById('visitorDateFilter');
const visitorCustomDateContainer = document.getElementById('visitorCustomDateContainer');

if (visitorDateFilter) {
    visitorDateFilter.addEventListener('change', function () {
        if (this.value === 'custom') {
            visitorCustomDateContainer.classList.remove('d-none');
            // Custom still needs both dates picked, so wait for the Filter button
        } else {
            visitorCustomDateContainer.classList.add('d-none');
            // Preset ranges (Today / 7 Days / Month / Year) apply instantly
            this.form.submit();
        }
    });
}

// The date filter only applies to History Logs, so hide it on the Passes tab
const visitorTabs = document.getElementById('visitorTabs');
const visitorFilterTabItem = document.getElementById('visitorFilterTabItem');

if (visitorTabs && visitorFilterTabItem) {
    visitorTabs.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tabButton => {
        tabButton.addEventListener('shown.bs.tab', function () {
            const isLogsTab = this.getAttribute('data-bs-target') === '#logs';
            visitorFilterTabItem.classList.toggle('d-none', !isLogsTab);
        });
    });
}