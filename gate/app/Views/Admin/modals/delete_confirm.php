<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-auto" style="max-width: 360px; width: 90%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-body text-center p-4">

                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 80px; height: 80px; background-color: #fdf3f5; border-radius: 50%;">
                    <i class="ti ti-trash text-danger" style="font-size: 38px; color: #fa896b !important;"></i>
                </div>

                <h4 class="fw-bolder mb-2" style="color: #2a3547;">Are you sure?</h4>
                <p class="text-muted mb-4" id="deleteModalMessage" style="font-size: 0.9rem;">You won't be able to revert this!</p>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <button type="button" class="btn fw-semibold text-muted py-2 w-100" data-bs-dismiss="modal" style="border-radius: 10px; background-color: #f6f9fc; border: 1px solid #e9ecef; transition: all 0.2s;">
                        Cancel
                    </button>
                    <a href="#" id="deleteModalConfirmBtn" class="btn btn-danger fw-semibold py-2 w-100 d-flex align-items-center justify-content-center" style="border-radius: 10px; background-color: #fa896b; border: none; box-shadow: 0 4px 10px rgba(250, 137, 107, 0.3); transition: all 0.2s;">
                        Yes, delete it
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>