<?php
// Get the current URL path to determine active states and visibility
$current_uri = uri_string();
$is_registration = strpos($current_uri, 'item-registration') !== false;
?>

    <aside class="left-sidebar d-none d-lg-block">
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
                        <a class="sidebar-link <?= strpos($current_uri, 'dashboard') !== false ? 'active' : '' ?>" href="<?= base_url('student/dashboard') ?>" aria-expanded="false">
                            <span><i class="ti ti-layout-dashboard"></i></span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?= $is_registration ? 'active' : '' ?>" href="<?= base_url('student/item-registration') ?>" aria-expanded="false">
                            <span><i class="ti ti-device-laptop"></i></span>
                            <span class="hide-menu">Item Registration</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?= strpos($current_uri, 'registered-items') !== false ? 'active' : '' ?>" href="<?= base_url('student/registered-items') ?>" aria-expanded="false">
                            <span><i class="ti ti-list-check"></i></span>
                            <span class="hide-menu">Registered Items</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?= strpos($current_uri, 'remove-item') !== false ? 'active' : '' ?>" href="<?= base_url('student/remove-item') ?>" aria-expanded="false">
                            <span><i class="ti ti-trash-x"></i></span>
                            <span class="hide-menu">Remove Item</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?= strpos($current_uri, 'report-item') !== false ? 'active' : '' ?>" href="<?= base_url('student/report-item') ?>" aria-expanded="false">
                            <span><i class="ti ti-alert-triangle"></i></span>
                            <span class="hide-menu">Report Item</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?= strpos($current_uri, 'history') !== false ? 'active' : '' ?>" href="<?= base_url('student/history') ?>" aria-expanded="false">
                            <span><i class="ti ti-history"></i></span>
                            <span class="hide-menu">History</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

<?php if (!$is_registration): ?>
    <nav class="mobile-bottom-nav d-flex d-lg-none">
        <a href="<?= base_url('student/dashboard') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'dashboard') !== false ? 'active' : '' ?>">
            <i class="ti ti-home"></i>
            <span>Home</span>
        </a>
        <a href="<?= base_url('student/remove-item') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'remove-item') !== false ? 'active' : '' ?>">
            <i class="ti ti-trash-x"></i>
            <span>Remove</span>
        </a>
        <a href="<?= base_url('student/registered-items') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'registered-items') !== false ? 'active' : '' ?>">
            <i class="ti ti-list-check"></i>
            <span>Items</span>
        </a>
        <a href="<?= base_url('student/report-item') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'report-item') !== false ? 'active' : '' ?>">
            <i class="ti ti-alert-triangle"></i>
            <span>Report</span>
        </a>
        <a href="<?= base_url('student/history') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'history') !== false ? 'active' : '' ?>">
            <i class="ti ti-history"></i>
            <span>History</span>
        </a>
    </nav>

    <a href="<?= base_url('student/item-registration') ?>" class="mobile-fab d-flex d-lg-none border border-2 border-white shadow">
        <i class="ti ti-plus"></i>
    </a>
<?php endif; ?>