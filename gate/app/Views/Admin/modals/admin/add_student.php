<div class="modal fade sheet-modal" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-2 px-sm-0">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">

            <div class="d-flex justify-content-center pt-3 pb-1 d-sm-none">
                <div style="width: 40px; height: 5px; background-color: #dee2e6; border-radius: 10px;"></div>
            </div>

            <form action="<?= base_url('admin/users/createStudent') ?>" method="post" id="addStudentForm">
                <?= csrf_field() ?>

                <div class="modal-header border-bottom-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bolder d-flex align-items-center mb-0 text-truncate pe-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px; background-color: rgba(93, 135, 255, 0.15); color: #5d87ff;">
                            <i class="ti ti-user-plus fs-5"></i>
                        </div>
                        Add New Student
                    </h5>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <button type="button" class="btn-close shadow-none m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                        <button type="button" class="mobile-sheet-close d-sm-none" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none;">
                            <i class="ti ti-x fs-4"></i>
                        </button>
                    </div>
                </div>

                <div class="modal-body px-4 py-3">

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="first_name" class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">First Name</label>
                            <input type="text" class="form-control form-control-lg bg-light rounded-3 input-adaptive shadow-none" id="first_name" name="first_name" value="<?= old('first_name') ?>" placeholder="Juan" required>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="last_name" class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Last Name</label>
                            <input type="text" class="form-control form-control-lg bg-light rounded-3 input-adaptive shadow-none" id="last_name" name="last_name" value="<?= old('last_name') ?>" placeholder="Dela Cruz" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reg_student_number" class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Student Number</label>
                        <input type="text" class="form-control form-control-lg bg-light rounded-3 input-adaptive shadow-none format-tupt-id" id="reg_student_number" name="student_number" value="<?= old('student_number') ?>" placeholder="TUPT-XX-XXXX" maxlength="12" required>
                    </div>

                    <div class="mb-3">
                        <label for="email_prefix" class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Email Address</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                            <input type="text" class="form-control bg-light border-end-0 text-end shadow-none" id="email_prefix" placeholder="juan.delacruz" required autocomplete="off">
                            <span class="input-group-text bg-primary-subtle text-primary border-primary border-opacity-25 fw-bold" style="font-size: 0.85rem;">@tup.edu.ph</span>
                        </div>
                        <input type="hidden" name="email" id="full_email">
                        <div class="form-text mt-1"><i class="ti ti-info-circle"></i> Enter username only.</div>
                    </div>

                    <div class="mb-3">
                        <label for="reg_password" class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Password</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                            <input type="password" class="form-control bg-light border-end-0 shadow-none" id="reg_password" name="password" placeholder="••••••••" required>
                            <button class="btn btn-light border border-start-0 bg-light px-3 btn-toggle-pass shadow-none" type="button" tabindex="-1">
                                <i class="ti ti-eye fs-5 text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label for="department" class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Department</label>
                            <select class="form-select form-select-lg bg-light rounded-3 shadow-none" id="department" name="department" required>
                                <option value="" disabled <?= empty(old('department')) ? 'selected' : '' ?>>Select Dept</option>
                                <option value="BASD" <?= old('department') == 'BASD' ? 'selected' : '' ?>>BASD</option>
                                <option value="CAAD" <?= old('department') == 'CAAD' ? 'selected' : '' ?>>CAAD</option>
                                <option value="EAAD" <?= old('department') == 'EAAD' ? 'selected' : '' ?>>EAAD</option>
                                <option value="MAAD" <?= old('department') == 'MAAD' ? 'selected' : '' ?>>MAAD</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="year_level" class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Year Level</label>
                            <select class="form-select form-select-lg bg-light rounded-3 shadow-none" id="year_level" name="year_level" required>
                                <option value="" disabled <?= empty(old('year_level')) ? 'selected' : '' ?>>Select Year</option>
                                <option value="1st Year" <?= old('year_level') == '1st Year' ? 'selected' : '' ?>>1st Year</option>
                                <option value="2nd Year" <?= old('year_level') == '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                                <option value="3rd Year" <?= old('year_level') == '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                                <option value="4th Year" <?= old('year_level') == '4th Year' ? 'selected' : '' ?>>4th Year</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-end modal-actions-mobile">
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2 shadow-sm m-0" style="border-radius: 10px;">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 1. Combine Email Prefix before form submission
        const form = document.getElementById('addStudentForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const emailPrefix = document.getElementById('email_prefix').value;
                const fullEmailInput = document.getElementById('full_email');

                if (emailPrefix) {
                    // Combine the prefix with the domain behind the scenes
                    fullEmailInput.value = emailPrefix + '@tup.edu.ph';
                }
            });
        }

        // 2. Prevent "@" from being typed in the email prefix field
        const emailPrefixInput = document.getElementById('email_prefix');
        if (emailPrefixInput) {
            emailPrefixInput.addEventListener('input', function() {
                // Check if the user typed or pasted an '@' symbol
                if (this.value.includes('@')) {
                    // Split the string at the '@' and only keep the first part (the username)
                    this.value = this.value.split('@')[0];
                }

                // Optional: You can also force it to be lowercase so it looks cleaner
                this.value = this.value.toLowerCase();
            });
        }

        // 3. Toggle Password Visibility (Updated for maximum reliability)
        // We use querySelectorAll to grab ALL toggle buttons on the page
        const togglePasswordBtns = document.querySelectorAll('.btn-toggle-pass');

        togglePasswordBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Find the specific input inside the same input-group as the button clicked
                const inputGroup = this.closest('.input-group');
                const passwordInput = inputGroup.querySelector('input');
                const icon = this.querySelector('i');

                if (passwordInput && icon) {
                    // Check current type and swap it
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                    // Swap the icons
                    if (isPassword) {
                        icon.classList.remove('ti-eye');
                        icon.classList.add('ti-eye-off');
                    } else {
                        icon.classList.remove('ti-eye-off');
                        icon.classList.add('ti-eye');
                    }
                }
            });
        });

    });
</script>