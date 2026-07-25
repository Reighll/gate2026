/**
 * Item Registration page: inline alert helper, the "Others" category
 * text-field toggle, and the AJAX form submit that closes whichever
 * container it was opened in (modal or standalone sheet) and hands off
 * to the dashboard with a success banner.
 *
 * Requires window.itemRegistrationConfig.dashboardUrl to be set by
 * item_registration.php before this file loads.
 *
 * (The skeleton-hider that used to live in this page's own <script>
 * block has been removed — it's a duplicate of the one already global
 * in student-app.js.)
 */
window.showInlineAlert = function(message, type = 'danger') {
    const alertContainer = document.getElementById('alertContainer');
    if (alertContainer) {
        const icon = type === 'success' ? 'ti-check-circle' : 'ti-alert-circle';
        const bgClass = type === 'success' ? 'bg-success-subtle text-success border-success-subtle' : 'bg-danger-subtle text-danger border-danger-subtle';

        alertContainer.innerHTML = `
            <div class="alert alert-dismissible fade show shadow-sm rounded-3 mb-4 d-flex align-items-center ${bgClass} border" role="alert" style="padding: 1rem 1.25rem;">
                <i class="ti ${icon} fs-5 me-2"></i>
                <span class="fw-medium" style="opacity: 0.9;">${message}</span>
                <button type="button" class="btn-close m-0 ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

window.toggleOtherCategory = function(select) {
    const wrapper = document.getElementById('otherCategoryWrapper');
    const input = document.getElementById('categoryOtherInput');
    const isOthers = select.value === 'Others';

    wrapper.classList.toggle('d-none', !isOthers);
    input.required = isOthers;
    if (!isOthers) input.value = '';
};

window.submitRegistration = async function(e, form) {
    e.preventDefault();
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
    submitBtn.classList.add('disabled');
    submitBtn.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: { "X-Requested-With": "XMLHttpRequest" },
            body: new FormData(form)
        });

        const data = await response.json();

        if (data.status === 'error') {
            window.showInlineAlert(data.message, 'danger');
            submitBtn.innerHTML = originalText;
            submitBtn.classList.remove('disabled');
            submitBtn.disabled = false;
        } else {
            // Close the mobile modal, if that's where this was submitted from
            const regModalEl = document.getElementById('itemRegistrationModal');
            if (regModalEl) {
                const regModalInstance = bootstrap.Modal.getInstance(regModalEl);
                if (regModalInstance) regModalInstance.hide();
            }

            // Close the standalone-page sheet, if that's where this was submitted from
            const container = document.getElementById('registration-container');
            if (container) {
                container.classList.add('reg-sheet-closing');
            }

            const dashboardUrl = window.itemRegistrationConfig.dashboardUrl;

            const successLink = document.createElement('a');
            successLink.setAttribute('hx-get', dashboardUrl + '?nocache=' + Date.now());
            successLink.setAttribute('hx-push-url', dashboardUrl);
            successLink.setAttribute('hx-target', '#app-content');
            successLink.setAttribute('hx-select', '#app-content');
            successLink.setAttribute('hx-swap', 'outerHTML swap:300ms');

            const injectAlert = function(event) {
                if (event.detail.target.id === 'app-content') {
                    const appContent = event.detail.target;
                    const targetWrapper = appContent.querySelector('div') || appContent;

                    const alertHtml = `
                        <div class="alert alert-dismissible fade show shadow-sm rounded-3 mt-3 mt-lg-0 mx-3 mx-lg-0 mb-4 d-flex align-items-center bg-success-subtle text-success border border-success-subtle" role="alert" style="padding: 1rem 1.25rem;">
                            <i class="ti ti-check-circle fs-5 me-2"></i>
                            <span class="fw-medium" style="opacity: 0.9;">Item registered successfully! Awaiting admin verification.</span>
                            <button type="button" class="btn-close m-0 ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;

                    targetWrapper.insertAdjacentHTML('afterbegin', alertHtml);
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    document.body.removeEventListener('htmx:afterSettle', injectAlert);
                }
            };

            document.body.addEventListener('htmx:afterSettle', injectAlert);

            document.body.appendChild(successLink);
            htmx.process(successLink);
            successLink.click();
        }
    } catch (err) {
        console.error("Submission failed:", err);
        window.showInlineAlert("A network error occurred. Please try again.", "danger");
        submitBtn.innerHTML = originalText;
        submitBtn.classList.remove('disabled');
        submitBtn.disabled = false;
    }
};
