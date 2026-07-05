<?php
// Get the current URL path to determine active states and visibility
$current_uri = uri_string();
$is_profile = strpos($current_uri, 'profile') !== false;

// If on the Profile page, prepare the 'd-none' class to hide the bottom nav
$nav_visibility_class = $is_profile ? 'd-none' : 'd-flex';
?>

<aside class="left-sidebar d-none d-lg-block" hx-boost="true" hx-target="#app-content" hx-select="#app-content" hx-swap="outerHTML">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between pt-4 pb-3 px-3">
            <a href="<?= base_url('guard/dashboard') ?>" class="text-nowrap logo-img d-flex align-items-center text-decoration-none">
                <img src="<?= base_url('assets/images/logos/favicon.png') ?>" width="35" alt="Logo" class="me-2" />
                <h4 class="mb-0 fw-bolder text-primary" style="letter-spacing: 0.5px;">GATE <span class="text-dark">Guard</span></h4>
            </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link <?= strpos($current_uri, 'dashboard') !== false ? 'active' : '' ?>" href="<?= base_url('guard/dashboard') ?>" aria-expanded="false">
                        <span><i class="ti ti-scan"></i></span>
                        <span class="hide-menu">Scanner</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link <?= strpos($current_uri, 'profile') !== false ? 'active' : '' ?>" href="<?= base_url('guard/profile') ?>" aria-expanded="false">
                        <span><i class="ti ti-user"></i></span>
                        <span class="hide-menu">My Profile</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<nav class="mobile-bottom-nav <?= $nav_visibility_class ?> d-lg-none justify-content-around" hx-boost="true" hx-target="#app-content" hx-select="#app-content" hx-swap="outerHTML">
    <a href="<?= base_url('guard/dashboard') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'dashboard') !== false ? 'active' : '' ?>" style="max-width: 150px;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 12l14 0" /></svg>
        <span>Scanner</span>
    </a>

    <a href="<?= base_url('guard/profile') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'profile') !== false ? 'active' : '' ?>" style="max-width: 150px;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
        <span>My Profile</span>
    </a>
</nav>