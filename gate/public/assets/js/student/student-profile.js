/**
 * Student profile page: skeleton loader, inline alert helper, the
 * face-detection rate limiter, password-toggle buttons, and the
 * Cropper.js avatar-crop flow (with a liveness/face check before and
 * after crop, gated by window.imageHasFace() from the face-model
 * loader in profile.php).
 *
 * NOTE: this file replaces an older, out-of-date version that only had
 * a plain Cropper.js flow (no face-detection integration) and wasn't
 * actually linked from profile.php anymore — that old copy had drifted
 * from what the page really does.
 */
        // 1. Skeleton Loaders
        function hideMySkeletons() {
            setTimeout(() => {
                document.querySelectorAll('.skeleton-wrapper').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.real-wrapper').forEach(el => el.classList.remove('d-none'));
            }, 600);
        }
        document.addEventListener("DOMContentLoaded", hideMySkeletons);
        document.body.addEventListener('htmx:afterSettle', hideMySkeletons);

        // 1b. Inline Alert Helper (mirrors the server-side flash alert markup/style)
        function showFormAlert(type, message) {
            const container = document.getElementById('profile-container');
            const existing = container.querySelector('.js-inline-alert');
            if (existing) existing.remove();

            const icon = type === 'success' ? 'ti-check-circle' : 'ti-alert-triangle';

            const alertEl = document.createElement('div');
            alertEl.className = `alert alert-${type} shadow-sm border-0 mb-4 d-flex align-items-center rounded-3 js-inline-alert`;
            alertEl.innerHTML = `<i class="ti ${icon} fs-4 me-2"></i>${message}`;

            const heading = container.querySelector('.d-flex.align-items-center.mb-4');
            heading.insertAdjacentElement('afterend', alertEl);

            alertEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        // 1b-2. Face-check rate limiter (max 3 failed "no face" attempts per 2-minute window)
        const FACE_CHECK_LIMIT = 3;
        const FACE_CHECK_WINDOW_MS = 120000;

        function getFaceCheckState() {
            try {
                const raw = localStorage.getItem('faceCheckAttempts');
                if (!raw) return { count: 0, firstAttemptAt: null };
                return JSON.parse(raw);
            } catch (e) {
                return { count: 0, firstAttemptAt: null };
            }
        }

        function saveFaceCheckState(state) {
            try {
                localStorage.setItem('faceCheckAttempts', JSON.stringify(state));
            } catch (e) {}
        }

        function registerFaceCheckFailure() {
            let state = getFaceCheckState();
            const now = Date.now();
            if (!state.firstAttemptAt || (now - state.firstAttemptAt) >= FACE_CHECK_WINDOW_MS) {
                state = { count: 0, firstAttemptAt: now };
            }
            state.count++;
            saveFaceCheckState(state);
        }

        function resetFaceCheckState() {
            saveFaceCheckState({ count: 0, firstAttemptAt: null });
        }

        function getFaceCheckLockStatus() {
            const state = getFaceCheckState();
            if (!state.firstAttemptAt) return { locked: false };
            const elapsed = Date.now() - state.firstAttemptAt;
            if (elapsed >= FACE_CHECK_WINDOW_MS) return { locked: false };
            if (state.count >= FACE_CHECK_LIMIT) {
                return { locked: true, secondsLeft: Math.ceil((FACE_CHECK_WINDOW_MS - elapsed) / 1000) };
            }
            return { locked: false };
        }
        // 1c. Show/hide password toggle (delegated - works for any .toggle-password-btn on this page)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.toggle-password-btn');
            if (!btn) return;

            const input = btn.previousElementSibling;
            const icon = btn.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        });

        // 2. The Cropper.js Logic
        function initProfileCropper() {
            const fileInput = document.getElementById('profile_pic');
            const cropperImage = document.getElementById('cropperImage');
            const previewImage = document.getElementById('profilePicPreview');
            const cropModalEl = document.getElementById('cropModal');
            const btnCrop = document.getElementById('btnCrop');

            if (!fileInput || fileInput.hasAttribute('data-cropper-init')) return;
            fileInput.setAttribute('data-cropper-init', 'true');

            let cropper = null;
            let bootstrapModal = new bootstrap.Modal(cropModalEl);

            const faceCheckLoading = document.getElementById('faceCheckLoading');

            fileInput.addEventListener('change', function (e) {
                const lockStatus = getFaceCheckLockStatus();
                if (lockStatus.locked) {
                    showFormAlert('danger', `Too many failed face-detection attempts. Please try again in ${lockStatus.secondsLeft} seconds.`);
                    fileInput.value = '';
                    return;
                }

                const files = e.target.files;
                if (files && files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = async function (event) {
                        const dataUrl = event.target.result;

                        if (faceCheckLoading) {
                            faceCheckLoading.classList.remove('d-none');
                            faceCheckLoading.classList.add('d-flex');
                        }

                        const hasFace = await imageHasFace(dataUrl);

                        if (faceCheckLoading) {
                            faceCheckLoading.classList.add('d-none');
                            faceCheckLoading.classList.remove('d-flex');
                        }

                        if (!hasFace) {
                            registerFaceCheckFailure();
                            const status = getFaceCheckLockStatus();
                            if (status.locked) {
                                showFormAlert('danger', `No face detected. You've reached the limit of 3 attempts. Please try again in ${status.secondsLeft} seconds.`);
                            } else {
                                showFormAlert('danger', 'No face detected in this photo. Please upload a clear, front-facing picture of yourself.');
                            }
                            fileInput.value = '';
                            return;
                        }

                        resetFaceCheckState();
                        cropperImage.src = dataUrl;
                        bootstrapModal.show();
                    };
                    reader.readAsDataURL(files[0]);
                }
            });

            cropModalEl.addEventListener('shown.bs.modal', function () {
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    autoCropArea: 1,
                    responsive: true,
                });
            });

            cropModalEl.addEventListener('hidden.bs.modal', function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                if (previewImage.getAttribute('data-updated') !== 'true') {
                    fileInput.value = '';
                }
                previewImage.removeAttribute('data-updated');
            });

            btnCrop.addEventListener('click', async function () {
                if (!cropper) return;

                const lockStatus = getFaceCheckLockStatus();
                if (lockStatus.locked) {
                    showFormAlert('danger', `Too many failed face-detection attempts. Please try again in ${lockStatus.secondsLeft} seconds.`);
                    bootstrapModal.hide();
                    return;
                }

                const canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500,
                });

                const croppedDataUrl = canvas.toDataURL('image/jpeg');

                const hasFace = await imageHasFace(croppedDataUrl);
                if (!hasFace) {
                    registerFaceCheckFailure();
                    const status = getFaceCheckLockStatus();
                    if (status.locked) {
                        showFormAlert('danger', `No face detected. You've reached the limit of 3 attempts. Please try again in ${status.secondsLeft} seconds.`);
                        bootstrapModal.hide();
                    } else {
                        showFormAlert('danger', 'No face detected in the cropped area. Please adjust the crop so your face is fully visible.');
                    }
                    return;
                }

                resetFaceCheckState();

                previewImage.src = croppedDataUrl;
                previewImage.setAttribute('data-updated', 'true');

                canvas.toBlob(function (blob) {
                    const file = new File([blob], "cropped_profile.jpg", { type: "image/jpeg", lastModified: new Date().getTime() });
                    const container = new DataTransfer();
                    container.items.add(file);
                    fileInput.files = container.files;

                    bootstrapModal.hide();
                }, 'image/jpeg', 0.9);
            });
        }

        document.addEventListener("DOMContentLoaded", initProfileCropper);
        document.body.addEventListener('htmx:afterSettle', initProfileCropper);
