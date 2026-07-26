<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light px-3">

        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover ps-0" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2 fs-6"></i>
                </a>
            </li>
        </ul>

        <a href="<?= base_url('student/dashboard') ?>" class="d-flex align-items-center text-decoration-none ms-2">
            <img src="<?= base_url('assets/images/logos/favicon.png') ?>" alt="Gatepass Logo" width="35" height="35" class="me-2 drop-shadow-sm">
            <h4 class="mb-0 fw-bolder text-primary d-none d-sm-block" style="letter-spacing: 0.5px;">GATE <span class="text-dark">Student</span></h4>
        </a>

        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end gap-2">

                <li class="nav-item">
                    <a class="nav-link nav-icon-hover cursor-pointer" id="theme-toggle" style="border-radius: 50%;">
                        <i class="ti ti-moon fs-6" id="theme-icon"></i>
                    </a>
                </li>

                <li class="nav-item dropdown position-relative">
                    <a class="nav-link d-flex align-items-center gap-2 px-2" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false" style="line-height: normal; height: auto;">

                        <div class="text-end mt-1" style="max-width: 140px;">
                            <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.85rem; line-height: 1.2;">
                                <?= session()->get('student_name') ?? 'Student Name' ?>
                            </h6>
                            <span class="text-muted d-block mt-1 text-truncate" style="font-size: 0.75rem; line-height: 1;">
                                <?= session()->get('student_number') ?? 'TUPT-XX-XXXX' ?>
                            </span>
                        </div>

                        <?php $navPic = session()->get('student_profile_pic') ?? 'default.png'; ?>
                        <img src="<?= base_url('uploads/profiles/' . $navPic) ?>" alt="Profile Picture" width="40" height="40" class="rounded-circle border border-2 border-primary shadow-sm ms-1" style="object-fit: cover;">

                    </a>

                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up dropdown-menu-with-caret shadow-lg rounded-3 border-0"
                         aria-labelledby="drop2"
                         style="min-width: 200px; margin-top: 10px;">
                        <div class="message-body">
                            <a href="javascript:void(0);"
                               id="navProfileItem"
                               hx-get="<?= base_url('student/profile') ?>"
                               hx-target="#app-content"
                               hx-select="#app-content"
                               hx-push-url="true"
                               hx-swap="outerHTML swap:0ms"
                               hx-indicator="#page-transition-loader"
                               onclick="var c=document.querySelector('#app-content > *'); if(c){c.classList.remove('page-slide-in'); c.classList.add('page-slide-out');}"
                               class="d-flex align-items-center gap-2 dropdown-item px-4 py-2">
                                <i class="ti ti-user-circle fs-5"></i>
                                <p class="mb-0 fw-semibold">My Profile</p>
                            </a>

                            <a href="<?= base_url('student/logout') ?>" class="d-flex align-items-center gap-2 dropdown-item px-4 py-2 text-danger">
                                <i class="ti ti-logout fs-5"></i>
                                <p class="mb-0 fw-semibold">Logout</p>
                            </a>
                        </div>
                    </div>
                </li>

            </ul>
        </div>

    </nav>
</header>