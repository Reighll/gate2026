document.addEventListener('DOMContentLoaded', function () {
    const rfidInput = document.getElementById('hiddenRfidInput');
    const hiddenForm = document.getElementById('hiddenScanForm');

    // Only run the scanner logic if the scanner form exists on the page
    if (rfidInput && hiddenForm) {

        // Grab the base URL securely from the data attribute we set in the HTML
        const checkUrl = hiddenForm.getAttribute('data-check-url');

        // IoT BRIDGE: Ask the server every 1 second if the ESP32 successfully sent a scan!
        setInterval(() => {
            fetch(checkUrl, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        console.log("Detecting EPC: " + data.epc);

                        // Drop it into the hidden input and submit the Check In/Out form
                        rfidInput.value = data.epc;
                        hiddenForm.submit();
                    }
                })
                .catch(err => {
                    // Fail silently (server might be busy or offline)
                });
        }, 1000); // Check every 1000ms (1 second)
    }
});