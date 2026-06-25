document.addEventListener('DOMContentLoaded', function() {
    const avatars = document.querySelectorAll('.avatar-option');
    const preview = document.getElementById('profilePicPreview');
    const hiddenInput = document.getElementById('selected_profile_pic');

    avatars.forEach(avatar => {
        // Re-add the hover grow effect
        avatar.addEventListener('mouseover', () => avatar.style.transform = 'scale(1.1)');
        avatar.addEventListener('mouseout', () => avatar.style.transform = 'scale(1)');

        // Handle the click
        avatar.addEventListener('click', function() {
            const filename = this.getAttribute('data-filename');

            // 1. Update hidden input for the database
            hiddenInput.value = filename;

            // 2. Update the big preview image
            preview.src = this.src;

            // 3. Remove the blue border from ALL avatars and make them white
            avatars.forEach(img => {
                img.classList.remove('border-primary');
                img.classList.add('border-white');
            });

            // 4. Add the blue border only to the one you just clicked
            this.classList.remove('border-white');
            this.classList.add('border-primary');
        });
    });
});