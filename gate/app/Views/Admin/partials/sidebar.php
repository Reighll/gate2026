<?php
// Get the current URL path to determine active states
$current_uri = uri_string();
?>

<aside class="left-sidebar d-none d-lg-block">
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
                    <a class="sidebar-link <?= strpos($current_uri, 'dashboard') !== false ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu fw-medium">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link <?= strpos($current_uri, 'visitors') !== false ? 'active' : '' ?>" href="<?= base_url('admin/visitors') ?>" aria-expanded="false">
                        <span><i class="ti ti-id-badge"></i></span>
                        <span class="hide-menu fw-medium">Visitors Pass</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link <?= strpos($current_uri, 'items') !== false && strpos($current_uri, 'item-reports') === false ? 'active' : '' ?>" href="<?= base_url('admin/items') ?>" aria-expanded="false">
                        <span><i class="ti ti-package"></i></span>
                        <span class="hide-menu fw-medium">Registered Items</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link <?= strpos($current_uri, 'item-reports') !== false ? 'active' : '' ?>" href="<?= base_url('admin/item-reports') ?>" aria-expanded="false">
                        <span><i class="ti ti-alert-triangle"></i></span>
                        <span class="hide-menu fw-medium">Item Reports</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link <?= strpos($current_uri, 'users') !== false ? 'active' : '' ?>" href="<?= base_url('admin/users') ?>" aria-expanded="false">
                        <span><i class="ti ti-users"></i></span>
                        <span class="hide-menu fw-medium">User Management</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<nav class="mobile-bottom-nav d-flex d-lg-none">
    <a href="<?= base_url('admin/dashboard') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'dashboard') !== false ? 'active' : '' ?>">
        <i class="ti ti-layout-dashboard"></i>
        <span>Dashboard</span>
    </a>
    <a href="<?= base_url('admin/visitors') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'visitors') !== false ? 'active' : '' ?>">
        <i class="ti ti-id-badge"></i>
        <span>Visitors</span>
    </a>
    <a href="<?= base_url('admin/items') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'items') !== false && strpos($current_uri, 'item-reports') === false ? 'active' : '' ?>">
        <i class="ti ti-package"></i>
        <span>Items</span>
    </a>
    <a href="<?= base_url('admin/item-reports') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'item-reports') !== false ? 'active' : '' ?>">
        <i class="ti ti-alert-triangle"></i>
        <span>Reports</span>
    </a>
    <a href="<?= base_url('admin/users') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'users') !== false ? 'active' : '' ?>">
        <i class="ti ti-users"></i>
        <span>Users</span>
    </a>
</nav>