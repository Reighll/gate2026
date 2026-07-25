<?= $this->extend('Admin/layout/main') ?>
<?= $this->section('title') ?>User Management | Admin Portal<?= $this->endSection() ?>
<?= $this->section('content') ?>

    <div class="page-transition-container pt-5 mt-4">
        <div class="col-lg-12">
            <div class="card w-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h5 class="card-title fw-bold mb-4">User Management</h5>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
                            <i class="ti ti-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
                            <i class="ti ti-alert-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- TABS (Load instantly) -->
                    <ul class="nav nav-tabs mb-4 flex-nowrap overflow-auto" id="userTabs" role="tablist" style="scrollbar-width: none;">
                        <li class="nav-item">
                            <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#students">
                                Students <span class="badge bg-primary rounded-pill ms-2 shadow-sm"><?= count($students ?? []) ?></span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#guards">
                                Guards <span class="badge bg-warning rounded-pill ms-2 shadow-sm"><?= count($guards ?? []) ?></span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#admins">
                                Admins <span class="badge bg-danger rounded-pill ms-2 shadow-sm"><?= count($admins ?? []) ?></span>
                            </button>
                        </li>
                    </ul>

                    <!-- ==========================================
                         SKELETON TABLE AREA
                         ========================================== -->
                    <div class="skeleton-wrapper">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="skeleton skeleton-title w-25 mb-0" style="height: 20px;"></div>
                            <div class="skeleton rounded-2" style="width: 120px; height: 32px;"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-nowrap mb-0 align-middle border-light">
                                <thead style="border-bottom: 2px solid #f0f0f0;">
                                <tr>
                                    <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                    <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                    <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                    <th><div class="skeleton skeleton-text w-50 mb-0"></div></th>
                                </tr>
                                </thead>
                                <?php $usersSkeletonRows = !empty($students) ? count($students) : 1; ?>
                                <tbody>
                                <?php for($i=0; $i<$usersSkeletonRows; $i++): ?>
                                    <tr style="border-bottom: 1px solid #f6f6f6;">
                                        <td class="py-3"><div class="skeleton skeleton-text w-75 mb-0"></div></td>
                                        <td class="py-3"><div class="skeleton skeleton-text w-100 mb-0"></div></td>
                                        <td class="py-3"><div class="skeleton skeleton-text w-100 mb-0"></div></td>
                                        <td class="py-3">
                                            <div class="d-flex gap-2">
                                                <div class="skeleton rounded-2" style="width: 60px; height: 30px;"></div>
                                                <div class="skeleton rounded-2" style="width: 75px; height: 30px;"></div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ==========================================
                         REAL TAB CONTENT AREA
                         ========================================== -->
                    <div class="tab-content real-wrapper d-none">

                        <!-- STUDENTS TAB -->
                        <div class="tab-pane fade show active" id="students">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <h6 class="fw-semibold mb-0 text-dark">Registered Students</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                                            <i class="ti ti-search text-muted"></i>
                                        </span>
                                        <input type="text" id="studentSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="Search students...">
                                    </div>
                                    <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                        <i class="ti ti-plus me-1"></i> Add Student
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle border-light table-hover" id="studentsTable">
                                    <thead style="border-bottom: 2px solid #f0f0f0;">
                                    <tr>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Student No.</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Name</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Email</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Status</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($students)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="ti ti-users fs-1 d-block mb-2 opacity-50"></i>
                                                No students registered yet.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($students as $student): ?>
                                            <tr style="border-bottom: 1px solid #f6f6f6;" class="student-row">
                                                <td data-label="Student No." class="py-3"><h6 class="fw-bold mb-0 text-dark"><?= esc($student['student_number']) ?></h6></td>
                                                <td data-label="Name" class="py-3 text-muted"><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                                <td data-label="Email" class="py-3 text-muted"><?= esc($student['email']) ?></td>
                                                <td data-label="Status" class="py-3">
                                                    <?php if ((int) ($student['is_verified'] ?? 0) === 1): ?>
                                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                        <i class="ti ti-circle-check me-1"></i> Verified
                                                    </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                                        <i class="ti ti-circle-x me-1"></i> Not Verified
                                                    </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td data-label="Action" class="py-3">
                                                    <button class="btn btn-sm btn-info text-white shadow-sm rounded-2 me-1" data-bs-toggle="modal" data-bs-target="#editStudentModal<?= $student['id'] ?>">
                                                        <i class="ti ti-pencil"></i> Edit
                                                    </button>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white shadow-sm rounded-2"
                                                       data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                       data-bs-url="<?= base_url('admin/users/deleteStudent/' . $student['id']) ?>"
                                                       data-bs-message="Delete this student? This removes all their registered items as well.">
                                                        <i class="ti ti-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <?= view('Admin/modals/admin/edit_student', ['student' => $student]) ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- GUARDS TAB -->
                        <div class="tab-pane fade" id="guards">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0 text-dark">Security Guards</h6>
                                <button class="btn btn-warning btn-sm rounded-pill px-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addGuardModal">
                                    <i class="ti ti-plus me-1"></i> Add Guard
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle border-light table-hover">
                                    <thead style="border-bottom: 2px solid #f0f0f0;">
                                    <tr>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Username</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Name</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($guards)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="ti ti-shield fs-1 d-block mb-2 opacity-50"></i>
                                                No guard accounts yet.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($guards as $guard): ?>
                                            <tr style="border-bottom: 1px solid #f6f6f6;">
                                                <td data-label="Username" class="py-3"><h6 class="fw-bold mb-0 text-dark"><?= esc($guard['username']) ?></h6></td>
                                                <td data-label="Name" class="py-3 text-muted"><?= esc($guard['first_name'] . ' ' . $guard['last_name']) ?></td>
                                                <td data-label="Action" class="py-3">
                                                    <button class="btn btn-sm btn-info text-white shadow-sm rounded-2 me-1" data-bs-toggle="modal" data-bs-target="#editGuardModal<?= $guard['id'] ?>">
                                                        <i class="ti ti-pencil"></i> Edit
                                                    </button>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white shadow-sm rounded-2"
                                                       data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                       data-bs-url="<?= base_url('admin/users/deleteGuard/' . $guard['id']) ?>"
                                                       data-bs-message="Are you sure you want to delete this guard account?">
                                                        <i class="ti ti-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <?= view('Admin/modals/admin/edit_guard', ['guard' => $guard]) ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- ADMINS TAB -->
                        <div class="tab-pane fade" id="admins">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0 text-dark">Administrators</h6>
                                <button class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                                    <i class="ti ti-plus me-1"></i> Add Admin
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle border-light table-hover">
                                    <thead style="border-bottom: 2px solid #f0f0f0;">
                                    <tr>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Username</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Name</th>
                                        <th class="border-0 fw-bold text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (empty($admins)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="ti ti-user-shield fs-1 d-block mb-2 opacity-50"></i>
                                                No admin accounts yet.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($admins as $admin): ?>
                                            <tr style="border-bottom: 1px solid #f6f6f6;">
                                                <td data-label="Username" class="py-3"><h6 class="fw-bold mb-0 text-dark"><?= esc($admin['username']) ?></h6></td>
                                                <td data-label="Name" class="py-3 text-muted"><?= esc($admin['first_name'] . ' ' . $admin['last_name']) ?></td>
                                                <td data-label="Action" class="py-3">
                                                    <button class="btn btn-sm btn-info text-white shadow-sm rounded-2 me-1" data-bs-toggle="modal" data-bs-target="#editAdminModal<?= $admin['id'] ?>">
                                                        <i class="ti ti-pencil"></i> Edit
                                                    </button>
                                                    <?php if ($admin['username'] !== 'admin'): ?>
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white shadow-sm rounded-2"
                                                           data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                           data-bs-url="<?= base_url('admin/users/deleteAdmin/' . $admin['id']) ?>"
                                                           data-bs-message="Are you sure you want to delete this admin account?">
                                                            <i class="ti ti-trash"></i> Delete
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?= view('Admin/modals/admin/edit_admin', ['admin' => $admin]) ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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
<?= $this->include('Admin/modals/admin/delete_confirm') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
    <script>
        function bindStudentSearch() {
            const searchInput = document.getElementById('studentSearchInput');
            const table = document.getElementById('studentsTable');
            if (!searchInput || !table || searchInput.dataset.bound === '1') return;

            searchInput.dataset.bound = '1';
            searchInput.addEventListener('input', function () {
                const term = this.value.trim().toLowerCase();
                const rows = table.querySelectorAll('tbody tr.student-row');

                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }

        document.addEventListener('DOMContentLoaded', bindStudentSearch);
        document.body.addEventListener('htmx:afterSettle', bindStudentSearch);

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.toggle-password-btn');
            if (!btn) return;

            const input = btn.previousElementSibling;
            const icon = btn.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        });

        const tuptInputs = document.querySelectorAll('.format-tupt-id');

        tuptInputs.forEach(input => {
            input.addEventListener('focus', function() {
                if (this.value === '') this.value = 'TUPT-';
            });

            input.addEventListener('input', function() {
                let numbers = this.value.replace(/[^0-9]/g, '');

                numbers = numbers.substring(0, 6);

                if (numbers.length === 0) {
                    this.value = 'TUPT-';
                } else if (numbers.length <= 2) {
                    this.value = 'TUPT-' + numbers;
                } else {
                    this.value = 'TUPT-' + numbers.substring(0, 2) + '-' + numbers.substring(2, 6);
                }
            });
        });
    </script>
<?= $this->endSection() ?>