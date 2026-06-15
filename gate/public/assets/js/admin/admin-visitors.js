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

// 3. AJAX Check Function
function checkDuplicate(rfid) {
    // UI: Show Loading
    scanState.style.display = 'none';
    checkingState.style.display = 'block';
    rfidInput.readOnly = true; // Lock input

    // AJAX Call
    fetch(`<?= base_url('admin/visitors/check-tag') ?>?rfid=${encodeURIComponent(rfid)}`, {
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
    if(myModal.classList.contains('show') && scanState.style.display !== 'none') {
        if(!e.target.closest('button')) {
            rfidInput.focus();
        }
    }
});
function showId(url) {
    document.getElementById('modalImage').src = url;
}