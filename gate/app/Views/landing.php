<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | Gate System</title>

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
        /* Modern Soft Background (Light Mode) */
        .landing-bg {
            background: radial-gradient(circle at 10% 20%, #ffffff 0%, #e6eff7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            transition: background 0.3s ease;
        }

        /* Dark Mode: Landing Background */
        html[data-bs-theme="dark"] .landing-bg {
            background: radial-gradient(circle at 10% 20%, #11142d 0%, #1b2e38 100%);
        }

        /* Floating Interactive Cards */
        .portal-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid #eef2f6;
            border-radius: 24px;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Dark Mode: Portal Cards */
        html[data-bs-theme="dark"] .portal-card {
            background: rgba(34, 54, 64, 0.85); /* Dark Navy Glass */
            border-color: #4f5467;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        html[data-bs-theme="dark"] .portal-card h3,
        html[data-bs-theme="dark"] h1 {
            color: #f1f9ff !important;
        }

        /* Hover Effects */
        .portal-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(93, 135, 255, 0.15);
            border-color: #5d87ff;
        }

        html[data-bs-theme="dark"] .portal-card:hover {
            box-shadow: 0 20px 40px rgba(139, 180, 250, 0.15);
            border-color: #8bb4fa;
        }

        .portal-card:hover .btn-go {
            background-color: #5d87ff;
            color: #ffffff;
            border-color: #5d87ff;
        }

        /* Dark Mode: Hover Button */
        html[data-bs-theme="dark"] .portal-card:hover .btn-go {
            background-color: #8bb4fa;
            color: #11142d;
            border-color: #8bb4fa;
        }

        /* Icon Wrapper Styles */
        .icon-box {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
        }

        /* Distinct Colors for each Role */
        .role-student .icon-box { background: rgba(93, 135, 255, 0.1); color: #5d87ff; }
        .role-guard .icon-box { background: rgba(19, 222, 185, 0.1); color: #13deb9; }
        .role-admin .icon-box { background: rgba(42, 53, 71, 0.1); color: #2a3547; }

        html[data-bs-theme="dark"] .role-admin .icon-box { color: #f1f9ff; background: rgba(241, 249, 255, 0.1); }

        .portal-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        /* Go Button Base Style */
        .btn-go {
            margin-top: auto;
            border: 2px solid #eef2f6;
            color: #5a6a85;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            width: 100%;
        }

        html[data-bs-theme="dark"] .btn-go {
            border-color: #4f5467;
            color: #a1aab2;
        }

        /* Slide Up Animations */
        .animate-up {
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        @keyframes slideUpFade {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="landing-bg">

<div class="container py-5">

    <div class="row justify-content-center mb-5 animate-up delay-1">
        <div class="col-12 text-center">
            <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                <!-- Swapped the icon for the image here -->
                <img src="<?= base_url('assets/images/logos/favicon.png') ?>" alt="Gatepass Logo" style="height: 3.5rem; width: auto;">
                <h1 class="fw-bolder mb-0 display-5" style="letter-spacing: -1px; color: #2a3547;">
                    <span class="text-primary">GA</span>TE
                </h1>
            </div>
            <p class="text-muted fs-5 fw-medium">Welcome! Please select your designated portal to continue.</p>
        </div>
    </div>

    <div class="row justify-content-center g-4" style="max-width: 1100px; margin: 0 auto;">

        <div class="col-md-6 col-lg-4 animate-up delay-2">
            <a href="<?= base_url('student/login') ?>" class="text-decoration-none">
                <div class="portal-card role-student">
                    <div class="icon-box">
                        <i class="ti ti-backpack" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark">Student</h3>
                    <p class="text-muted mb-4 small">Register items, view your gate history, and manage your account details.</p>
                    <span class="btn-go">Access Portal <i class="ti ti-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4 animate-up delay-3">
            <a href="<?= base_url('guard/login') ?>" class="text-decoration-none">
                <div class="portal-card role-guard">
                    <div class="icon-box">
                        <i class="ti ti-scan" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark">Security Guard</h3>
                    <p class="text-muted mb-4 small">Monitor entryways, scan RFID gateways, and manually log campus visitors.</p>
                    <span class="btn-go">Access Portal <i class="ti ti-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4 animate-up delay-4">
            <a href="<?= base_url('admin/login') ?>" class="text-decoration-none">
                <div class="portal-card role-admin">
                    <div class="icon-box">
                        <i class="ti ti-settings" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark">Administrator</h3>
                    <p class="text-muted mb-4 small">Manage system users, view system-wide logs, and handle item reports.</p>
                    <span class="btn-go">Access Portal <i class="ti ti-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>

    </div>

    <div class="row mt-5 animate-up delay-4">
        <div class="col-12 text-center">
            <p class="text-muted small fw-medium">
                &copy; <?= date('Y') ?> GATE System. All rights reserved.
            </p>
        </div>
    </div>

</div>

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
                .then(reg => console.log('Service Worker Registered'))
                .catch(err => console.log('Service Worker Registration failed: ', err));
        });
    }
</script>
</body>
</html>