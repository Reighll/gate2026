<?php

// Determine which portals have an active session, so the utility bar can adapt.
$studentActive = (bool) session()->get('student_logged_in');
$guardActive = (bool) session()->get('guard_logged_in');
$adminActive = (bool) session()->get('admin_logged_in');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ============== SEARCH ENGINE / SEO TAGS ============== -->
    <title>GATE | RFID-Based Item Protection System - TUP Taguig</title>
    <meta name="description" content="GATE is the official RFID-based item protection and gatepass system for Technological University of the Philippines - Taguig. Register belongings, track items, and manage gate entry logs.">
    <meta name="keywords" content="GATE system, TUP Taguig, RFID item tracking, gatepass system, campus security">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://tuptgate.tech/">

    <!-- ============== OPEN GRAPH (Facebook, Messenger, LinkedIn previews) ============== -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://tuptgate.tech/">
    <meta property="og:title" content="GATE | RFID-Based Item Protection System">
    <meta property="og:description" content="Register belongings, track items, and manage gate entry logs at TUP Taguig.">
    <meta property="og:image" content="https://tuptgate.tech/favicon.ico">

    <!-- ============== TWITTER/X CARD ============== -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="GATE | RFID-Based Item Protection System">
    <meta name="twitter:description" content="Register belongings, track items, and manage gate entry logs at TUP Taguig.">
    <meta name="twitter:image" content="https://tuptgate.tech/favicon.ico">

    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/favicon.png') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/icons/tabler-icons/tabler-icons.css') ?>" />
    <link rel="manifest" href="/manifest.json">

    <script>
        // Check if dark mode was enabled inside the system and apply it instantly
        // (Adjust 'theme' to whatever key your dashboard toggle uses in localStorage)
        const savedTheme = localStorage.getItem('theme') || localStorage.getItem('bs-theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    </script>

    <style>
        /* ============== UTILITY BAR (updated for hidden links) ============== */
        .utility-bar {
            background: #11142d;
            color: #ffffff;
            font-size: 0.8rem;
            padding: 6px 5%;
            position: relative;
            z-index: 6;
        }

        .utility-bar .utility-time {
            font-weight: 500;
            color: #f1f9ff;
        }

        /* 1. Subtle, muted color so they don't draw attention */
        .utility-bar a {
            color: #2c3554; /* A dark, muted blue-grey */
            text-decoration: none;
            font-weight: 600;
            transition: color 0.4s ease;
        }

        /* 2. Bright reveal when hovered */
        .utility-bar a:hover {
            color: #8bb4fa;
        }

        /* 3. Match the dividers to the subtle link color */
        .utility-bar .divider {
            color: #2c3554;
        }

        /* ============== HERO SECTION ============== */
        .hero-section {
            background: radial-gradient(circle at 50% 0%, #eef3ff 0%, #f7f9fc 55%, #ffffff 100%);
            min-height: max(720px, calc(88vh - 33px)); /* was: calc(88vh - 33px) */
            font-family: 'Plus Jakarta Sans', sans-serif;
            position: relative;
            overflow: hidden;
            transition: background 0.3s ease;
        }

        .hero-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(#c9d6ee 1px, transparent 1px);
            background-size: 26px 26px;
            -webkit-mask-image: radial-gradient(circle at 50% 25%, #000 0%, transparent 65%);
            mask-image: radial-gradient(circle at 50% 25%, #000 0%, transparent 65%);
            opacity: 0.55;
            z-index: 0;
            pointer-events: none;
        }

        html[data-bs-theme="dark"] .hero-section {
            background: radial-gradient(circle at 50% 0%, #1b2e38 0%, #11142d 55%, #0d0f22 100%);
        }
        html[data-bs-theme="dark"] .hero-section::before {
            background-image: radial-gradient(#2c3a55 1px, transparent 1px);
            opacity: 0.4;
        }

        /* ============== HERO CONTENT ============== */
        .hero-content { padding: 110px 20px 160px; text-align: center; position: relative; z-index: 3; }

        .hero-content h1 {
            font-size: clamp(2.1rem, 5vw, 3.4rem);
            font-weight: 800;
            line-height: 1.14;
            letter-spacing: -0.5px;
            color: #2a3547;
            margin-bottom: 18px;
            transition: color 0.3s ease;
            opacity: 0;
            animation: fadeSlideUp 0.7s 0.1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .hero-content h1 .accent {
            background: linear-gradient(90deg, #5d87ff, #8bb4fa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        html[data-bs-theme="dark"] .hero-content h1 { color: #f1f9ff; }
        html[data-bs-theme="dark"] .hero-content h1 .accent {
            background: linear-gradient(90deg, #8bb4fa, #5d87ff);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .hero-subtext {
            max-width: 480px;
            margin: 0 auto 32px;
            color: #334155;
            font-size: 1.3rem;
            line-height: 1.6;
            opacity: 0;
            animation: fadeSlideUp 0.7s 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        html[data-bs-theme="dark"] .hero-subtext { color: #d1dbe8; }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============== FLOATING ICON BADGES ============== */
        .floating-icons { position: absolute; inset: 0; z-index: 2; pointer-events: none; }
        .float-badge {
            position: absolute;
            width: 58px;
            height: 58px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
            animation: floatY 4.5s ease-in-out infinite;
        }
        .float-badge::after {
            content: "";
            position: absolute;
            inset: -6px;
            border-radius: 22px;
            border: 1px solid currentColor;
            opacity: 0.15;
        }
        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-16px); }
        }
        .fb-1 { top: 10%; left: 10%; background: #5d87ff; animation-delay: 0s; }
        .fb-3 { top: 16%; right: 10%; background: #2a3547; animation-delay: 1.1s; }
        .fb-4 { top: 38%; left: 5%; background: #ffae1f; animation-delay: 1.6s; width:50px; height:50px; font-size:1.3rem; }
        .fb-5 { top: 40%; right: 5%; background: #fc4b6c; animation-delay: 0.3s; width:50px; height:50px; font-size:1.3rem; }
        .fb-6 { bottom: 40%; left: 12%; background: #8bb4fa; animation-delay: 2.1s; width:44px; height:44px; font-size:1.1rem; }
        .fb-7 { bottom: 38%; right: 14%; background: #13deb9; animation-delay: 1.8s; }

        html[data-bs-theme="dark"] .float-badge { box-shadow: 0 12px 24px rgba(0,0,0,0.35); }

        /* ============== 3D LAPTOP — PEEKING FROM BOTTOM ============== */
        .laptop-peek-wrap {
            position: absolute;
            bottom: calc(min(820px, 94vw) * -0.34);
            left: 50%;
            width: min(820px, 94vw);
            z-index: 1;
            pointer-events: none;
            opacity: 0;
            transform: translateX(-50%) perspective(1800px) rotateX(10deg);
            transform-origin: bottom center;
            animation: laptopRiseUp 1s 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes laptopRiseUp {
            from { opacity: 0; transform: translateX(-50%) perspective(1800px) rotateX(10deg) translateY(40px); }
            to { opacity: 1; transform: translateX(-50%) perspective(1800px) rotateX(10deg) translateY(0); }
        }

        /* Ground shadow beneath the laptop for depth */
        .laptop-peek-wrap::after {
            content: "";
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 78%;
            height: 50px;
            background: radial-gradient(ellipse at center, rgba(17,20,45,0.30) 0%, transparent 70%);
            filter: blur(5px);
            z-index: -1;
        }

        /* --- Screen / lid --- */
        .laptop-screen {
            width: 100%;
            aspect-ratio: 16 / 10.4;
            background: #0d0f22;
            border-radius: 20px 20px 5px 5px;
            padding: 16px 16px 10px;
            position: relative;
            box-shadow:
                    0 60px 90px -20px rgba(17,20,45,0.45),
                    0 20px 40px rgba(17,20,45,0.22),
                    inset 0 0 0 1px rgba(255,255,255,0.06);
        }
        .laptop-screen::before {
            content: none;
        }

        .laptop-notch {
            position: absolute;
            top: 16px; /* aligns with laptop-screen's top padding, so it sits right at the display's top edge */
            left: 50%;
            transform: translateX(-50%);
            width: 130px;
            height: 20px;
            background: #0d0f22;
            border-radius: 0 0 10px 10px;
            z-index: 4;
        }
        .laptop-notch::after {
            /* camera dot inside the notch */
            content: "";
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #2a3547;
            box-shadow: 0 0 0 1.5px rgba(255,255,255,0.05);
        }
        /* diagonal glass highlight */
        .laptop-screen .glass-sheen {
            position: absolute;
            inset: 16px 16px 10px;
            border-radius: 8px;
            background: linear-gradient(115deg, rgba(255,255,255,0.10) 0%, rgba(255,255,255,0) 20%, rgba(255,255,255,0) 80%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
            z-index: 2;
        }

        .laptop-display {
            width: 100%;
            height: 100%;
            background: linear-gradient(150deg, #5d87ff 0%, #2a3547 100%);
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            color: #fff;
            padding: 22px 30px;
        }

        /* fake browser/app top bar inside the screen */
        .laptop-display .app-topbar {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 22px;
            opacity: 0.8;
        }
        .laptop-display .app-topbar .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
        }

        .laptop-display .brand-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 75px;
        }
        .laptop-display .brand-row img { height: 32px; width: auto; }
        .laptop-display .brand-row span { font-weight: 800; font-size: 1.1rem; }

        .laptop-display .display-center {
            display: flex;
            align-items: center;
            gap: 20px;
            margin: 0 0 auto;
        }
        .laptop-center-logo {
            width: 76px;
            height: 76px;
            flex-shrink: 0;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15), inset 0 0 0 1px rgba(255,255,255,0.15);
        }
        .laptop-center-logo img { width: 42px; height: 42px; object-fit: contain; }

        .laptop-display .display-copy { text-align: left; }
        .laptop-display .display-copy .app-name { font-weight: 800; font-size: 1.35rem; margin-bottom: 4px; }
        .laptop-display .display-copy .app-sub { font-size: 0.85rem; opacity: 0.75; }

        /* --- Base / keyboard deck --- */
        .laptop-base {
            width: 116%;
            margin-left: -8%;
            height: 22px;
            background: linear-gradient(180deg, #1b1e38 0%, #11142d 100%);
            border-radius: 0 0 14px 14px;
            position: relative;
            box-shadow: 0 30px 40px -10px rgba(17,20,45,0.4);
        }
        .laptop-base::before {
            /* trackpad notch cut on the hinge edge */
            content: "";
            position: absolute;
            top: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 90px;
            height: 6px;
            background: #0d0f22;
            border-radius: 0 0 8px 8px;
        }

        html[data-bs-theme="dark"] .laptop-screen,
        html[data-bs-theme="dark"] .laptop-base { box-shadow: 0 60px 90px -20px rgba(0,0,0,0.55); }

        @media (max-width: 767.98px) {
            .float-badge { transform: scale(0.7); }

            .utility-bar .d-flex.justify-content-between {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .utility-links {
                justify-content: center;
            }

            .hero-section { display: flex; flex-direction: column; }
            .hero-content { padding: 20px 20px 80px; margin: auto 0; }

            .laptop-display { padding: 16px 18px; }
            .laptop-display .display-center { gap: 12px; }
            .laptop-center-logo { width: 56px; height: 56px; }
            .laptop-center-logo img { width: 32px; height: 32px; }
            .laptop-display .display-copy .app-name { font-size: 1.05rem; }
            .laptop-display .display-copy .app-sub { font-size: 0.72rem; }
        }
    </style>
</head>
<body>

<div class="utility-bar">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="utility-time" id="utilityClock"></span>
        <div class="d-flex align-items-center gap-2 utility-links">
            <?php if ($adminActive): ?>
                <a href="<?= base_url('admin/dashboard') ?>">Admin Dashboard</a>
            <?php else: ?>
                <a href="<?= base_url('admin/login') ?>">GATE Admin</a>
            <?php endif; ?>

            <span class="divider">|</span>

            <?php if ($guardActive): ?>
                <a href="<?= base_url('guard/dashboard') ?>">Guard Dashboard</a>
            <?php else: ?>
                <a href="<?= base_url('guard/login') ?>">GATE Guard</a>
            <?php endif; ?>

            <span class="divider">|</span>

            <?php if ($studentActive): ?>
                <a href="<?= base_url('student/dashboard') ?>">Student Dashboard</a>
            <?php else: ?>
                <a href="<?= base_url('student/login') ?>">GATE Student</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="hero-section">

    <div class="floating-icons">
        <div class="float-badge fb-1"><i class="ti ti-shield-check"></i></div>
        <div class="float-badge fb-3"><i class="ti ti-scan"></i></div>
        <div class="float-badge fb-4"><i class="ti ti-device-laptop"></i></div>
        <div class="float-badge fb-5"><i class="ti ti-qrcode"></i></div>
        <div class="float-badge fb-6"><i class="ti ti-cpu"></i></div>
        <div class="float-badge fb-7"><i class="ti ti-lock"></i></div>
    </div>

    <div class="hero-content">

        <h1>
            Secure Your Items<br>
            Through <span class="accent">GATE</span>
        </h1>

        <p class="hero-subtext">
            Register, verify, and track your belongings across campus.
        </p>

    </div>

    <!-- Big 3D laptop peeking from the bottom edge, cropped by hero-section overflow -->
    <div class="laptop-peek-wrap">
        <div class="laptop-screen">
            <div class="laptop-notch"></div>
            <div class="glass-sheen"></div>
            <div class="laptop-display">
                <div class="app-topbar">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>

                <div class="brand-row">
                    <img src="<?= base_url('assets/images/logos/favicon.png') ?>" alt="GATE Logo">
                    <span>GATE</span>
                </div>

                <div class="display-center">
                    <div class="laptop-center-logo">
                        <img src="<?= base_url('assets/images/logos/favicon.png') ?>" alt="GATE">
                    </div>
                    <div class="display-copy">
                        <div class="app-name">GATE</div>
                        <div class="app-sub">Your items, scanned &amp; secure</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="laptop-base"></div>
    </div>

</div>

<script>
    function updateUtilityClock() {
        const el = document.getElementById('utilityClock');
        if (!el) return;
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        el.textContent = now.toLocaleDateString('en-US', options);
    }
    updateUtilityClock();
    setInterval(updateUtilityClock, 1000);
</script>

</body>
</html>