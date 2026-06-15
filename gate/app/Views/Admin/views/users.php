<?= $this->extend('Admin/layout/main') ?>

<?= $this->section('content') ?>

    <div class="pt-5 mt-4">
        <div class="col-lg-12">
            <div class="card w-100">
                <div class="card-body p-4">

                    <h5 class="card-title fw-semibold mb-4">User Management</h5>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <ul class="nav nav-tabs mb-4 flex-nowrap overflow-auto" id="userTabs" role="tablist" style="scrollbar-width: none;">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#students">
                                Students <span class="badge bg-primary rounded-pill ms-2"><?= count($students ?? []) ?></span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#guards">
                                Guards <span class="badge bg-warning rounded-pill ms-2"><?= count($guards ?? []) ?></span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#admins">
                                Admins <span class="badge bg-danger rounded-pill ms-2"><?= count($admins ?? []) ?></span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="students">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0">Registered Students</h6>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                    <i class="ti ti-plus"></i> Add Student
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Student No.</h6></th>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Name</h6></th>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Email</h6></th>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Action</h6></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td class="border-bottom-0" data-label="Student No."><h6 class="fw-semibold mb-0"><?= esc($student['student_number']) ?></h6></td>
                                            <td class="border-bottom-0" data-label="Name"><p class="mb-0 fw-normal"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></p></td>
                                            <td class="border-bottom-0" data-label="Email"><p class="mb-0 fw-normal"><?= esc($student['email']) ?></p></td>
                                            <td class="border-bottom-0" data-label="Action">
                                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#editStudentModal<?= $student['id'] ?>">
                                                    <i class="ti ti-pencil"></i> Edit
                                                </button>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white"
                                                   data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                   data-bs-url="<?= base_url('admin/users/deleteStudent/' . $student['id']) ?>"
                                                   data-bs-message="Delete this student? This removes all their registered items as well.">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?= view('Admin/modals/admin/edit_student', ['student' => $student]) ?>                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="guards">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0">Security Guards</h6>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addGuardModal">
                                    <i class="ti ti-plus"></i> Add Guard
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Username</h6></th>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Name</h6></th>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Action</h6></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($guards as $guard): ?>
                                        <tr>
                                            <td class="border-bottom-0" data-label="Username"><h6 class="fw-semibold mb-0"><?= esc($guard['username']) ?></h6></td>
                                            <td class="border-bottom-0" data-label="Name"><p class="mb-0 fw-normal"><?= esc($guard['first_name'] . ' ' . $guard['last_name']) ?></p></td>
                                            <td class="border-bottom-0" data-label="Action">
                                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#editGuardModal<?= $guard['id'] ?>">
                                                    <i class="ti ti-pencil"></i> Edit
                                                </button>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white"
                                                   data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                   data-bs-url="<?= base_url('admin/users/deleteGuard/' . $guard['id']) ?>"
                                                   data-bs-message="Are you sure you want to delete this guard account?">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?= view('Admin/modals/admin/edit_guard', ['guard' => $guard]) ?>                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="admins">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0">Administrators</h6>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                                    <i class="ti ti-plus"></i> Add Admin
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Username</h6></th>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Name</h6></th>
                                        <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Action</h6></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($admins as $admin): ?>
                                        <tr>
                                            <td class="border-bottom-0" data-label="Username"><h6 class="fw-semibold mb-0"><?= esc($admin['username']) ?></h6></td>
                                            <td class="border-bottom-0" data-label="Name"><p class="mb-0 fw-normal"><?= esc($admin['first_name'] . ' ' . $admin['last_name']) ?></p></td>
                                            <td class="border-bottom-0" data-label="Action">
                                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#editAdminModal<?= $admin['id'] ?>">
                                                    <i class="ti ti-pencil"></i> Edit
                                                </button>
                                                <?php if ($admin['username'] !== 'admin'): ?>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white"
                                                       data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                       data-bs-url="<?= base_url('admin/users/deleteAdmin/' . $admin['id']) ?>"
                                                       data-bs-message="Are you sure you want to delete this admin account?">
                                                        <i class="ti ti-trash"></i> Delete
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?= view('Admin/modals/admin/edit_admin', ['admin' => $admin]) ?>                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->include('Admin/modals/admin/add_student') ?>
<?= $this->include('Admin/modals/admin/add_guard') ?>
<?= $this->include('Admin/modals/admin/add_admin') ?>

<?= $this->endSection() ?>