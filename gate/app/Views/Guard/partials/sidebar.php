<?php
// Get the current URL path to determine active states
$current_uri = uri_string();
?>

<aside class="left-sidebar d-none d-lg-block">
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

<nav class="mobile-bottom-nav d-flex d-lg-none justify-content-around">
    <a href="<?= base_url('guard/dashboard') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'dashboard') !== false ? 'active' : '' ?>" style="max-width: 150px;">
        <i class="ti ti-scan"></i>
        <span>Scanner</span>
    </a>

    <a href="<?= base_url('guard/profile') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'profile') !== false ? 'active' : '' ?>" style="max-width: 150px;">
        <i class="ti ti-user"></i>
        <span>My Profile</span>
    </a>
</nav>