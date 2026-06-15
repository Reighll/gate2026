<div class="modal fade" id="editStudentModal<?= $student['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-2 px-sm-0">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <form action="<?= base_url('admin/users/updateStudent/' . $student['id']) ?>" method="post">
                <div class="modal-header border-bottom-0 pt-4 px-4 pb-2">
                    <h5 class="modal-title fw-bolder text-dark d-flex align-items-center">
                        <div class="bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                            <i class="ti ti-edit fs-5"></i>
                        </div>
                        Edit Student Details
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Student Number</label>
                        <input type="text" class="form-control form-control-lg rounded-3 bg-light shadow-none" style="border: 1px solid #eef2f6;" name="student_number" value="<?= esc($student['student_number']) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">First Name</label>
                            <input type="text" class="form-control form-control-lg rounded-3 bg-light shadow-none" style="border: 1px solid #eef2f6;" name="first_name" value="<?= esc($student['first_name']) ?>" required>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Last Name</label>
                            <input type="text" class="form-control form-control-lg rounded-3 bg-light shadow-none" style="border: 1px solid #eef2f6;" name="last_name" value="<?= esc($student['last_name']) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Email Address</label>
                        <input type="email" class="form-control form-control-lg rounded-3 bg-light shadow-none" style="border: 1px solid #eef2f6;" name="email" value="<?= esc($student['email']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Reset Password</label>
                        <input type="password" class="form-control form-control-lg rounded-3 bg-light shadow-none" style="border: 1px solid #eef2f6;" name="password" placeholder="Leave blank to keep current password">
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-grid gap-2 d-sm-flex justify-content-sm-end">
                    <button type="button" class="btn btn-light fw-bold px-4 py-2 m-0" style="border-radius: 10px; color: #5a6a85;" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2 shadow-sm m-0" style="border-radius: 10px; background-color: #5d87ff; border: none;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>