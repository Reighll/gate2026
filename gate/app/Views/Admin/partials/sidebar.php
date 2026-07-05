<?php
// Get the current URL path to determine active states
$current_uri = uri_string();
$is_profile = strpos($current_uri, 'profile') !== false;

// If on the Profile page, prepare the 'd-none' class to hide the bottom nav
$nav_visibility_class = $is_profile ? 'd-none' : 'd-flex';
?>

<!-- 1. DESKTOP SIDEBAR -->
<aside class="left-sidebar d-none d-lg-block" hx-boost="true" hx-target="#app-content" hx-select="#app-content" hx-swap="outerHTML">
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

<!-- 2. MOBILE BOTTOM NAVIGATION -->
<nav class="mobile-bottom-nav <?= $nav_visibility_class ?> d-lg-none" hx-boost="true" hx-target="#app-content" hx-select="#app-content" hx-swap="outerHTML">

    <a href="<?= base_url('admin/dashboard') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'dashboard') !== false ? 'active' : '' ?>">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v8h-6z" /><path d="M4 16h6v4h-6z" /><path d="M14 12h6v8h-6z" /><path d="M14 4h6v4h-6z" /></svg>
        <span>Dashboard</span>
    </a>

    <a href="<?= base_url('admin/visitors') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'visitors') !== false ? 'active' : '' ?>">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 3m0 3a3 3 0 0 1 3 -3h8a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-8a3 3 0 0 1 -3 -3z" /><path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M10 6h4" /><path d="M9 18h6" /></svg>
        <span>Visitors</span>
    </a>

    <a href="<?= base_url('admin/items') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'items') !== false && strpos($current_uri, 'item-reports') === false ? 'active' : '' ?>">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
        <span>Items</span>
    </a>

    <a href="<?= base_url('admin/item-reports') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'item-reports') !== false ? 'active' : '' ?>">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
        <span>Reports</span>
    </a>

    <a href="<?= base_url('admin/users') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'users') !== false ? 'active' : '' ?>">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
        <span>Users</span>
    </a>

</nav>