<style>
    @keyframes pulseCheckout {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.15); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }

    .btn-adaptive-cancel:hover {
        background-color: rgba(150, 160, 175, 0.2) !important;
    }
</style>

<div class="modal fade" id="forceCheckoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-auto" style="max-width: 360px; width: 90%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-body text-center p-4">

                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 80px; height: 80px; background-color: rgba(255, 174, 31, 0.15); border-radius: 50%;">
                    <i class="ti ti-door-exit text-warning" style="font-size: 42px; color: #ffae1f !important; animation: pulseCheckout 2s infinite ease-in-out;"></i>
                </div>

                <h4 class="fw-bolder mb-2">Force checkout?</h4>
                <p class="text-muted mb-4" id="forceCheckoutModalMessage" style="font-size: 0.9rem;">This visitor will be marked as checked out and their pass freed up.</p>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">

                    <button type="button" class="btn btn-adaptive-cancel fw-semibold py-2 w-100" data-bs-dismiss="modal" style="border-radius: 10px; background-color: rgba(150, 160, 175, 0.1); border: 1px solid rgba(150, 160, 175, 0.2); color: inherit; transition: all 0.2s;">
                        Cancel
                    </button>

                    <a href="#" id="forceCheckoutModalConfirmBtn" class="btn fw-semibold py-2 w-100 d-flex align-items-center justify-content-center text-white" style="border-radius: 10px; background-color: #ffae1f; border: none; box-shadow: 0 4px 10px rgba(255, 174, 31, 0.3); transition: all 0.2s;">
                        Yes, check them out
                    </a>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forceCheckoutModal = document.getElementById('forceCheckoutModal');

        if (forceCheckoutModal) {
            forceCheckoutModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const targetUrl = button.getAttribute('data-bs-url');
                const targetMessage = button.getAttribute('data-bs-message');

                document.getElementById('forceCheckoutModalConfirmBtn').setAttribute('href', targetUrl);

                if (targetMessage) {
                    document.getElementById('forceCheckoutModalMessage').textContent = targetMessage;
                }
            });
        }
    });
</script>