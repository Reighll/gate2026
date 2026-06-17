/* File: public/assets/js/student/student-auth.js */

document.addEventListener('DOMContentLoaded', function() {

    // ==========================================
    // STUDENT NUMBER FORMATTING (Login & Register)
    // ==========================================

    // Grab BOTH inputs from the newly merged view
    const loginIdInput = document.getElementById('student_number');
    const regIdInput = document.getElementById('reg_student_number');

    // Create a reusable formatting function
    function setupStudentIdFormatting(inputElement) {
        if (!inputElement) return;

        inputElement.addEventListener('focus', function() {
            if (this.value === '') {
                this.value = 'TUPT-';
            }
        });

        inputElement.addEventListener('input', function(e) {
            // 1. Temporarily remove the prefix and dashes
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

            // 5. Instantly update the input field
            this.value = formatted;
        });

        inputElement.addEventListener('keydown', function(e) {
            // Prevent deleting the prefix
            if (e.key === 'Backspace' && this.value === 'TUPT-') {
                e.preventDefault();
            }
        });
    }

    // Apply the formatting magic to BOTH fields!
    setupStudentIdFormatting(loginIdInput);
    setupStudentIdFormatting(regIdInput);


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

    // Because you used classes and DOM traversal here, this part
    // magically works for both forms without any changes!
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
    // ==========================================
    // SLIDING ANIMATION LOGIC (Login <-> Register)
    // ==========================================
    const btnToRegister = document.getElementById('btn-to-register');
    const btnToLogin = document.getElementById('btn-to-login');
    const loginView = document.getElementById('login-view');
    const registerView = document.getElementById('register-view');

    // NEW: Grab the main white card container
    const cardWrapper = document.querySelector('.auth-card-wrapper');

    function triggerSlide(target, url) {
        // 1. Find the starting pixel height of the card
        const startHeight = cardWrapper.offsetHeight;

        // 2. Temporarily lock the card to this height so it doesn't instantly snap
        cardWrapper.style.height = startHeight + 'px';

        // 3. Swap the forms (slide them left and right)
        if (target === 'register') {
            loginView.className = 'auth-form-view form-slide-out-left';
            registerView.className = 'auth-form-view form-slide-in';
        } else {
            registerView.className = 'auth-form-view form-slide-out-right';
            loginView.className = 'auth-form-view form-slide-in';
        }

        // 4. Quickly calculate what the NEW height is supposed to be
        cardWrapper.style.height = 'auto';
        const endHeight = cardWrapper.offsetHeight;

        // 5. Snap back to the start height, then force the browser to register it
        cardWrapper.style.height = startHeight + 'px';
        cardWrapper.offsetHeight; // Forces a browser reflow

        // 6. Trigger the smooth CSS height transition
        cardWrapper.style.height = endHeight + 'px';

        // 7. Remove the hardcoded pixel height after the animation finishes (0.6s)
        // This ensures the card stays responsive if the user resizes their browser window
        setTimeout(() => {
            cardWrapper.style.height = 'auto';
        }, 600);

        // Updates the browser URL silently
        if (url) window.history.pushState({}, '', url);
    }

    if (btnToRegister) {
        btnToRegister.addEventListener('click', function(e) {
            e.preventDefault();
            triggerSlide('register', this.getAttribute('data-target-url'));
        });
    }

    if (btnToLogin) {
        btnToLogin.addEventListener('click', function(e) {
            e.preventDefault();
            triggerSlide('login', this.getAttribute('data-target-url'));
        });
    }

});