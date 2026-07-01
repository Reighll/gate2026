<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between pt-4 pb-3 px-3">

            <a href="<?= base_url('student/dashboard') ?>" class="text-nowrap logo-img d-flex align-items-center text-decoration-none">
                <img src="<?= base_url('assets/images/logos/favicon.png') ?>" width="35" alt="Logo" class="me-2" />
                <h4 class="mb-0 fw-bolder text-primary" style="letter-spacing: 0.5px;">GATE <span class="text-dark">Student</span></h4>
            </a>

        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('student/dashboard') ?>" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('student/item-registration') ?>" aria-expanded="false">
                        <span><i class="ti ti-device-laptop"></i></span>
                        <span class="hide-menu">Item Registration</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('student/registered-items') ?>" aria-expanded="false">
                        <span><i class="ti ti-list-check"></i></span>
                        <span class="hide-menu">Registered Items</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('student/remove-item') ?>" aria-expanded="false">
                        <span><i class="ti ti-trash-x"></i></span>
                        <span class="hide-menu">Remove Item</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('student/report-item') ?>" aria-expanded="false">
                        <span><i class="ti ti-alert-triangle"></i></span>
                        <span class="hide-menu">Report Item</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('student/history') ?>" aria-expanded="false">
                        <span><i class="ti ti-history"></i></span>
                        <span class="hide-menu">History</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>