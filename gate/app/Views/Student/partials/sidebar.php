<?php
$current_uri = uri_string();
$is_registration = strpos($current_uri, 'item-registration') !== false;
$is_profile = strpos($current_uri, 'profile') !== false;

$nav_visibility_class = ($is_registration || $is_profile) ? 'd-none' : 'd-flex';
?>

    <aside class="left-sidebar d-none d-lg-block" hx-boost="true" hx-target="#app-content" hx-select="#app-content" hx-swap="outerHTML">
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
    <nav class="mobile-bottom-nav <?= $nav_visibility_class ?> d-lg-none" hx-boost="true" hx-target="#app-content" hx-select="#app-content" hx-swap="outerHTML">
        <a href="<?= base_url('student/dashboard') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'dashboard') !== false ? 'active' : '' ?>">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
            <span>Home</span>
        </a>
        <a href="<?= base_url('student/remove-item') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'remove-item') !== false ? 'active' : '' ?>">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7h16" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /><path d="M10 12l4 4m0 -4l-4 4" /></svg>
            <span>Remove</span>
        </a>
        <a href="<?= base_url('student/registered-items') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'registered-items') !== false ? 'active' : '' ?>">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.5 5.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 11.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 17.5l1.5 1.5l2.5 -2.5" /><path d="M11 6l9 0" /><path d="M11 12l9 0" /><path d="M11 18l9 0" /></svg>
            <span>Items</span>
        </a>
        <a href="<?= base_url('student/report-item') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'report-item') !== false ? 'active' : '' ?>">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            <span>Report</span>
        </a>
        <a href="<?= base_url('student/history') ?>" class="mobile-bottom-item <?= strpos($current_uri, 'history') !== false ? 'active' : '' ?>">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg>
            <span>History</span>
        </a>
    </nav>

    <a href="<?= base_url('student/item-registration') ?>" hx-get="<?= base_url('student/item-registration') ?>" hx-target="#app-content" hx-select="#app-content" hx-swap="outerHTML" hx-push-url="true"
       onclick="var p=window.location.pathname; if(!p.includes('item-registration') && !p.includes('profile')){var c=document.querySelector('.page-transition-container'); if(c){c.classList.add('page-slide-out');}}"
       class="mobile-fab <?= $nav_visibility_class ?> d-lg-none border border-2 border-white shadow">
        <i class="ti ti-plus"></i>
    </a>
<?php endif; ?>