<aside class="left-sidebar shadow-sm">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between pt-4 pb-3 px-3">

            <a href="<?= base_url('admin/dashboard') ?>" class="text-nowrap logo-img d-flex align-items-center text-decoration-none">
                <img src="<?= base_url('assets/images/logos/favicon.png') ?>" width="35" alt="Logo" class="me-2" />
                <h4 class="mb-0 fw-bolder text-primary" style="letter-spacing: 0.5px;">GATE <span class="text-dark">Admin</span></h4>
            </a>

            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer me-2" id="sidebarCollapse">
                <i class="ti ti-x fs-5 fw-bold text-dark"></i>
            </div>

        </div>

        <nav class="sidebar-nav scroll-sidebar mt-2" data-simplebar="">
            <ul id="sidebarnav">

                <li class="nav-small-cap mt-2">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu fw-bold text-muted uppercase">Home</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link rounded-3" href="<?= base_url('admin/dashboard') ?>" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu fw-medium">Dashboard</span>
                    </a>
                </li>

                <li class="nav-small-cap mt-4">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu fw-bold text-muted uppercase">Modules</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link rounded-3" href="<?= base_url('admin/visitors') ?>" aria-expanded="false">
                        <span><i class="ti ti-id-badge"></i></span>
                        <span class="hide-menu fw-medium">Visitors Pass</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link rounded-3" href="<?= base_url('admin/item-reports') ?>" aria-expanded="false">
                        <span><i class="ti ti-alert-triangle"></i></span>
                        <span class="hide-menu fw-medium">Item Reports</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link rounded-3" href="<?= base_url('admin/items') ?>" aria-expanded="false">
                        <span><i class="ti ti-package"></i></span>
                        <span class="hide-menu fw-medium">Registered Items</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link rounded-3" href="<?= base_url('admin/users') ?>" aria-expanded="false">
                        <span><i class="ti ti-users"></i></span>
                        <span class="hide-menu fw-medium">User Management</span>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>