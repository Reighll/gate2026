document.addEventListener('DOMContentLoaded', function() {

    // ==========================================
    // LOGIN PAGE: Student Number Formatting
    // ==========================================
    const idInput = document.getElementById('student_number');

    if (idInput) {
        idInput.addEventListener('focus', function() {
            if (this.value === '') {
                this.value = 'TUPT-';
            }
        });

        idInput.addEventListener('input', function(e) {
            // 1. Temporarily remove the prefix and dashes to isolate the user's input
            let userInput = this.value.toUpperCase().replace('TUPT', '').replace(/-/g, '');

            // 2. The Magic Fix: Strip out ANY character that is not a number (0-9)
            let numbersOnly = userInput.replace(/\D/g, '');

            // 3. Limit the input to a maximum of 6 digits
            numbersOnly = numbersOnly.substring(0, 6);

            // 4. Reconstruct the perfectly formatted string
            let formatted = 'TUPT-';
            if (numbersOnly.length > 0) {
                formatted += numbersOnly.substring(0, 2);
            }
            if (numbersOnly.length > 2) {
                formatted += '-' + numbersOnly.substring(2, 6);
            }

            // 5. Instantly update the input field so letters never even appear
            this.value = formatted;
        });

        idInput.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === 'TUPT-') {
                e.preventDefault();
            }
        });
    }

    // ==========================================
    // REGISTER PAGE: Email Prefix Auto-fill
    // ==========================================
    const prefixInput = document.getElementById('email_prefix');
    const fullEmailInput = document.getElementById('full_email');
    const domain = '@tup.edu.ph';

    if (prefixInput && fullEmailInput) {
        prefixInput.addEventListener('input', function() {
            fullEmailInput.value = this.value ? this.value + domain : '';
        });

        // Trigger on load for browsers that autofill passwords/usernames
        if (prefixInput.value) {
            fullEmailInput.value = prefixInput.value + domain;
        }
    }
    // ==========================================
    // SHOW / HIDE PASSWORD TOGGLE
    // ==========================================
    const togglePasswordButtons = document.querySelectorAll('.btn-toggle-pass');

    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Find the input field right next to this button
            const passwordInput = this.previousElementSibling;
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
    });
});