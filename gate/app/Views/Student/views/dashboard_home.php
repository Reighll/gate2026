<?= $this->extend('Student/layout/main') ?>

<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <h4 class="fw-semibold mb-3 d-none d-md-block">Student Dashboard</h4>

        <div class="digital-id-card mb-4">
            <div class="digital-id-header position-relative" style="background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%); color: white; padding: 30px 20px; text-align: center; border-radius: 15px 15px 0 0;">
                <h5 class="text-white mb-0 opacity-75 fs-5">TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES</h5>
                <div class="mt-4 mb-n5 position-relative" style="z-index: 2;">
                    <?php $pic = session()->get('profile_pic') ?? 'default.png'; ?>
                    <img src="<?= base_url('uploads/profiles/' . $pic) ?>" alt="Profile" class="rounded-circle border border-4 border-white shadow-sm bg-white" width="120" height="120" style="object-fit: cover;">
                </div>
            </div>
            <div class="digital-id-body pt-5 pb-4 bg-white rounded-bottom shadow-sm text-center" style="border-radius: 0 0 15px 15px;">
                <div class="pt-3">
                    <h3 class="fw-bold text-dark mb-1 text-uppercase"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></h3>
                    <p class="text-primary fw-semibold fs-5 mb-3 font-monospace"><?= esc($student['student_number']) ?></p>
                    <div class="row text-start bg-light rounded-3 p-3 mx-2 mx-md-4">
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Department</small>
                            <span class="fw-semibold"><?= esc($student['department']) ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-success rounded-3 fw-semibold px-3">Enrolled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 fw-semibold">Campus Status</h6>
                    <small class="text-muted">Your current location</small>
                </div>
                <span class="badge bg-light-primary text-primary fs-3 rounded-pill px-3 py-2">
                <i class="ti ti-building me-1"></i> Outside Campus
            </span>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>