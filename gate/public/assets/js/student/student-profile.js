document.addEventListener('DOMContentLoaded', function () {
    let cropper;
    const imageInput = document.getElementById('profile_pic');
    const cropperImage = document.getElementById('cropperImage');
    const profilePicPreview = document.getElementById('profilePicPreview');
    const cropModalElement = document.getElementById('cropModal');

    // Safety check: only run this script if we are actually on the profile page
    if (!imageInput || !cropModalElement) return;

    let cropModal;
    let currentFile = null;

    // Initialize the Bootstrap Modal
    if (typeof bootstrap !== 'undefined') {
        cropModal = new bootstrap.Modal(cropModalElement);
    }

    // 1. When the user selects a file, open the Cropper modal
    imageInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            currentFile = files[0];
            const url = URL.createObjectURL(currentFile);
            cropperImage.src = url;
            cropModal.show();
        }
    });

    // 2. Initialize Cropper.js when the modal opens
    cropModalElement.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(cropperImage, {
            aspectRatio: 1, // Enforce a perfect square
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 1,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    });

    // 3. Destroy Cropper when the modal closes
    cropModalElement.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        // If they closed the modal without hitting "Crop & Apply", clear the file input
        if (!cropperImage.dataset.cropped) {
            imageInput.value = '';
        }
        cropperImage.dataset.cropped = ''; // Reset the flag
    });

    // 4. Handle the "Crop & Apply" button click
    document.getElementById('btnCrop').addEventListener('click', function () {
        if (!cropper) return;

        // Export the cropped area as a high-quality 500x500 canvas
        const canvas = cropper.getCroppedCanvas({
            width: 500,
            height: 500
        });

        // Convert the canvas back into a standard file format
        canvas.toBlob(function (blob) {
            // Update the preview image on the main profile page
            const croppedUrl = URL.createObjectURL(blob);
            profilePicPreview.src = croppedUrl;

            // Create a brand new File object from the cropped blob
            const newFile = new File([blob], currentFile.name, { type: currentFile.type, lastModified: new Date().getTime() });

            // Sneakily inject the new cropped file back into the HTML input!
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);
            imageInput.files = dataTransfer.files;

            // Set a flag so the modal closer knows we successfully cropped it
            cropperImage.dataset.cropped = 'true';

            cropModal.hide();
        }, currentFile.type);
    });
});