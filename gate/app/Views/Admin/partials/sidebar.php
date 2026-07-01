<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between pt-4 pb-3 px-3">

            <a href="<?= base_url('admin/dashboard') ?>" class="text-nowrap logo-img d-flex align-items-center text-decoration-none">
                <img src="<?= base_url('assets/images/logos/favicon.png') ?>" width="35" alt="Logo" class="me-2" />
                <h4 class="mb-0 fw-bolder text-primary" style="letter-spacing: 0.5px;">GATE <span class="text-dark">Admin</span></h4>
            </a>

        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/dashboard') ?>" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu fw-medium">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/visitors') ?>" aria-expanded="false">
                        <span><i class="ti ti-id-badge"></i></span>
                        <span class="hide-menu fw-medium">Visitors Pass</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/item-reports') ?>" aria-expanded="false">
                        <span><i class="ti ti-alert-triangle"></i></span>
                        <span class="hide-menu fw-medium">Item Reports</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/items') ?>" aria-expanded="false">
                        <span><i class="ti ti-package"></i></span>
                        <span class="hide-menu fw-medium">Registered Items</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/users') ?>" aria-expanded="false">
                        <span><i class="ti ti-users"></i></span>
                        <span class="hide-menu fw-medium">User Management</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>