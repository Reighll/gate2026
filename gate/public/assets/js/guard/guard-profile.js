// public/assets/js/guard/guard-profile.js

document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('profile_pic');
    const cropperImage = document.getElementById('cropperImage');
    const profilePicPreview = document.getElementById('profilePicPreview');
    const cropModalElement = document.getElementById('cropModal');

    // Safety check: Only run this script if the crop modal exists on the page
    if (!cropModalElement || !imageInput) return;

    let cropper;
    let cropModal;
    let currentFile = null;

    if (typeof bootstrap !== 'undefined') {
        cropModal = new bootstrap.Modal(cropModalElement);
    }

    imageInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            currentFile = files[0];
            const url = URL.createObjectURL(currentFile);
            cropperImage.src = url;
            cropModal.show();
        }
    });

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

    cropModalElement.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (!cropperImage.dataset.cropped) {
            imageInput.value = '';
        }
        cropperImage.dataset.cropped = '';
    });

    document.getElementById('btnCrop').addEventListener('click', function () {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 500,
            height: 500
        });

        canvas.toBlob(function (blob) {
            const croppedUrl = URL.createObjectURL(blob);
            profilePicPreview.src = croppedUrl;

            const newFile = new File([blob], currentFile.name, { type: currentFile.type, lastModified: new Date().getTime() });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);
            imageInput.files = dataTransfer.files;

            cropperImage.dataset.cropped = 'true';
            cropModal.hide();
        }, currentFile.type);
    });
});