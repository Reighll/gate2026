// Delegated on document.body so it works regardless of whether this page
// was reached via a hard reload (DOMContentLoaded) or an htmx swap from
// the dashboard nav — document.body itself is never replaced by htmx,
// only the content inside it, so this only needs to be bound once, ever.
if (!window.__adminProfileAvatarDelegated) {
    window.__adminProfileAvatarDelegated = true;

    document.body.addEventListener('mouseover', function (e) {
        const avatar = e.target.closest('.avatar-option');
        if (avatar) avatar.style.transform = 'scale(1.1)';
    });

    document.body.addEventListener('mouseout', function (e) {
        const avatar = e.target.closest('.avatar-option');
        if (avatar) avatar.style.transform = 'scale(1)';
    });

    document.body.addEventListener('click', function (e) {
        const avatar = e.target.closest('.avatar-option');
        if (!avatar) return;

        const preview = document.getElementById('profilePicPreview');
        const hiddenInput = document.getElementById('selected_profile_pic');
        if (!preview || !hiddenInput) return;

        const filename = avatar.getAttribute('data-filename');
        hiddenInput.value = filename;
        preview.src = avatar.src;

        document.querySelectorAll('.avatar-option').forEach(img => {
            img.classList.remove('border-primary');
            img.classList.add('border-white');
        });

        avatar.classList.remove('border-white');
        avatar.classList.add('border-primary');
    });
}