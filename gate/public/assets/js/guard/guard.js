document.addEventListener("DOMContentLoaded", function() {
    // [Skeleton Loader]
    setTimeout(() => {
        document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
    }, 600);

    // ==========================================
    // 1. WEBCAM CAPTURE LOGIC (Front/Rear Toggle)
    // ==========================================
    const startCameraBtn = document.getElementById('startCameraBtn');
    const takePhotoBtn = document.getElementById('takePhotoBtn');
    const retakePhotoBtn = document.getElementById('retakePhotoBtn');
    const switchCameraBtn = document.getElementById('switchCameraBtn');
    const webcamVideo = document.getElementById('webcamVideo');
    const photoPreview = document.getElementById('photoPreview');
    const photoCanvas = document.getElementById('photoCanvas');
    const cameraIcon = document.getElementById('cameraIcon');
    const webcamPhotoInput = document.getElementById('webcamPhotoInput');
    const manualPhotoInput = document.getElementById('manualPhotoInput');

    let videoStream = null;
    let currentFacingMode = 'user';

    async function initCamera(facingMode) {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
        }
        try {
            videoStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: facingMode }
            });

            webcamVideo.srcObject = videoStream;
            webcamVideo.style.display = 'block';
            cameraIcon.style.display = 'none';
            photoPreview.style.display = 'none';

            startCameraBtn.style.display = 'none';
            takePhotoBtn.style.display = 'block';
            switchCameraBtn.style.display = 'block';
            retakePhotoBtn.style.display = 'none';
        } catch (err) {
            alert("Camera access denied or not available. Please check your browser permissions.");
            console.error(err);
        }
    }

    startCameraBtn?.addEventListener('click', () => {
        initCamera(currentFacingMode);
    });

    switchCameraBtn?.addEventListener('click', () => {
        currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
        initCamera(currentFacingMode);
    });

    takePhotoBtn?.addEventListener('click', () => {
        const context = photoCanvas.getContext('2d');
        photoCanvas.width = webcamVideo.videoWidth;
        photoCanvas.height = webcamVideo.videoHeight;
        context.drawImage(webcamVideo, 0, 0, photoCanvas.width, photoCanvas.height);

        const imageData = photoCanvas.toDataURL('image/png');
        webcamPhotoInput.value = imageData;
        photoPreview.src = imageData;

        webcamVideo.style.display = 'none';
        photoPreview.style.display = 'block';

        takePhotoBtn.style.display = 'none';
        switchCameraBtn.style.display = 'none';
        retakePhotoBtn.style.display = 'block';
    });

    retakePhotoBtn?.addEventListener('click', () => {
        webcamPhotoInput.value = '';
        webcamVideo.style.display = 'block';
        photoPreview.style.display = 'none';

        takePhotoBtn.style.display = 'block';
        switchCameraBtn.style.display = 'block';
        retakePhotoBtn.style.display = 'none';
    });

    manualPhotoInput?.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                photoPreview.src = event.target.result;
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                }
                photoPreview.style.display = 'block';
                cameraIcon.style.display = 'none';
                webcamVideo.style.display = 'none';

                startCameraBtn.style.display = 'none';
                takePhotoBtn.style.display = 'none';
                switchCameraBtn.style.display = 'none';
                retakePhotoBtn.style.display = 'block';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // ==========================================
    // 2. IoT BRIDGE (ESP32 Polling)
    // ==========================================
    const rfidInput = document.getElementById('hiddenRfidInput');
    const hiddenForm = document.getElementById('hiddenScanForm');
    const debugOutput = document.getElementById('debugOutput');
    const debugStatus = document.getElementById('debugStatus');

    if (rfidInput && hiddenForm) {
        const checkUrl = hiddenForm.getAttribute('data-check-url');

        setInterval(() => {
            fetch(checkUrl, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        console.log("Detecting EPC: " + data.epc);

                        if (debugOutput && debugStatus) {
                            debugOutput.textContent = `Scanned EPC: ${data.epc}`;
                            debugStatus.textContent = "PROCESSING...";
                            debugStatus.className = "text-info fw-bold small";
                        }

                        rfidInput.value = data.epc;
                        hiddenForm.submit();
                    }
                })
                .catch(err => {
                    // Fail silently (server might be busy or offline)
                });
        }, 1000);
    }

    // Manual RFID entry — reuses the same hidden scan form/input, so it goes
    // through the exact same guard/check-in flow as a real scan. Lives here
    // (not as an inline <script> in the view) because this file loads from
    // the persistent Guard layout — an inline script in the page view would
    // silently never run after an htmx navigation, same issue we hit on the
    // Admin side.
    const manualScanBtn = document.getElementById('manualScanBtn');
    const manualRfidInput = document.getElementById('manualRfidInput');

    if (manualScanBtn && manualRfidInput && rfidInput && hiddenForm) {
        manualScanBtn.addEventListener('click', function () {
            const value = manualRfidInput.value.trim();
            if (!value) {
                manualRfidInput.focus();
                return;
            }

            rfidInput.value = value;
            hiddenForm.submit();
        });

        manualRfidInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                manualScanBtn.click();
            }
        });
    }
});

// Password Toggle (if you have one on this page)
document.getElementById('togglePassword')?.addEventListener('click', function (e) {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('ti-eye');
        icon.classList.add('ti-eye-off');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('ti-eye-off');
        icon.classList.add('ti-eye');
    }
});