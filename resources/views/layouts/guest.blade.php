<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'OGC Portal') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { width: 100%; min-height: 100vh; font-family: 'Inter', sans-serif; background: #faf9f7; color: #1a1a1a; }

        /* ── NAVBAR ── */
        .lp-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 68px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .lp-nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .lp-nav-brand img { height: 38px; width: 38px; object-fit: contain; }
        .lp-nav-brand-text { line-height: 1.2; }
        .lp-nav-brand-text strong { display: block; font-size: 14px; font-weight: 800; color: #820000; }
        .lp-nav-brand-text span { font-size: 11px; color: #888; font-weight: 500; }
        .lp-nav-links { display: flex; align-items: center; gap: 8px; }
        .lp-nav-login {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 10px;
            background: #820000; color: #fff;
            font-size: 14px; font-weight: 700; text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }
        .lp-nav-login:hover { background: #5a0000; transform: translateY(-1px); }
        .lp-nav-register {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 10px;
            background: transparent; color: #820000;
            border: 1.5px solid #820000;
            font-size: 14px; font-weight: 700; text-decoration: none;
            transition: background 0.2s;
        }
        .lp-nav-register:hover { background: #fff5f5; }

        /* ── HERO SECTION ── */
        .lp-hero {
            min-height: 100vh;
            padding: 68px 0 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
        }
        .lp-hero-left {
            padding: 80px 64px 80px 80px;
            display: flex; flex-direction: column; justify-content: center;
        }
        .lp-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 16px; border-radius: 999px;
            background: #fff3f3; border: 1px solid #ffd0d0;
            color: #820000; font-size: 12px; font-weight: 700;
            letter-spacing: 0.04em; text-transform: uppercase;
            margin-bottom: 28px; width: fit-content;
        }
        .lp-badge::before {
            content: ''; width: 7px; height: 7px; border-radius: 50%;
            background: #F8650C; display: inline-block;
        }
        .lp-hero-title {
            font-size: clamp(38px, 4.5vw, 62px);
            font-weight: 900; line-height: 1.05;
            letter-spacing: -0.04em; color: #0f172a;
            margin-bottom: 22px;
        }
        .lp-hero-title em { font-style: normal; color: #820000; }
        .lp-hero-desc {
            font-size: 17px; line-height: 1.8; color: #64748b;
            max-width: 480px; margin-bottom: 40px;
        }
        .lp-hero-cta { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 56px; }
        .lp-btn-primary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 32px; border-radius: 12px;
            background: #820000; color: #fff;
            font-size: 16px; font-weight: 800; text-decoration: none;
            box-shadow: 0 8px 24px rgba(130,0,0,0.25);
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .lp-btn-primary:hover { background: #5a0000; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(130,0,0,0.3); }
        .lp-btn-secondary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 32px; border-radius: 12px;
            background: #fff; color: #0f172a;
            border: 1.5px solid #e2e8f0;
            font-size: 16px; font-weight: 700; text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .lp-btn-secondary:hover { border-color: #820000; background: #fff5f5; color: #820000; }
        .lp-stats { display: flex; gap: 40px; flex-wrap: wrap; }
        .lp-stat-num { font-size: 28px; font-weight: 900; color: #0f172a; letter-spacing: -0.03em; }
        .lp-stat-label { font-size: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 2px; }

        /* ── HERO RIGHT (floating card) ── */
        .lp-hero-right {
            padding: 80px 80px 80px 40px;
            display: flex; align-items: center; justify-content: center;
        }
        .lp-card-stack { position: relative; width: 100%; max-width: 440px; }
        .lp-main-card {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(15,23,42,0.1);
        }
        .lp-card-header {
            display: flex; align-items: center; gap: 14px;
            padding-bottom: 20px; margin-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
        }
        .lp-card-icon {
            width: 48px; height: 48px; border-radius: 14px;
            background: linear-gradient(135deg, #820000, #F8650C);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .lp-card-icon i { color: #fff; font-size: 20px; }
        .lp-card-title { font-size: 18px; font-weight: 800; color: #0f172a; }
        .lp-card-sub { font-size: 13px; color: #94a3b8; margin-top: 2px; }
        .lp-service-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
        .lp-service-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 16px; border-radius: 12px;
            background: #f8fafc; border: 1px solid #f1f5f9;
            transition: border-color 0.2s;
        }
        .lp-service-item:hover { border-color: #ffc917; }
        .lp-service-left { display: flex; align-items: center; gap: 12px; }
        .lp-service-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .lp-service-name { font-size: 14px; font-weight: 600; color: #334155; }
        .lp-service-badge {
            font-size: 11px; font-weight: 700; padding: 4px 10px;
            border-radius: 999px;
        }
        .badge-open { background: #effaf3; color: #15803d; }
        .badge-avail { background: #fff9e6; color: #92400e; }
        .badge-new { background: #fff3f3; color: #820000; }
        .lp-card-footer {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 16px; border-top: 1px solid #f1f5f9;
        }
        .lp-footer-stat { text-align: center; }
        .lp-footer-num { font-size: 22px; font-weight: 900; color: #0f172a; }
        .lp-footer-label { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; }

        /* floating mini card */
        .lp-float-card {
            position: absolute; bottom: -20px; right: -24px;
            background: #fff; border: 1px solid #e8edf5;
            border-radius: 16px; padding: 14px 18px;
            box-shadow: 0 8px 24px rgba(15,23,42,0.1);
            display: flex; align-items: center; gap: 12px;
            min-width: 200px;
        }
        .lp-float-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: #fff9e6; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .lp-float-icon i { color: #F8650C; font-size: 16px; }
        .lp-float-label { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; }
        .lp-float-val { font-size: 15px; font-weight: 800; color: #0f172a; }

        /* ── SLOT ── */
        .lp-slot { display: contents; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .lp-hero { grid-template-columns: 1fr; }
            .lp-hero-right { display: none; }
            .lp-hero-left { padding: 60px 32px; }
            .lp-nav { padding: 0 24px; }
        }
        @media (max-width: 640px) {
            .lp-hero-left { padding: 48px 20px; }
            .lp-hero-title { font-size: 34px; }
            .lp-nav-register { display: none; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="lp-nav">
        <a href="/" class="lp-nav-brand">
            <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" onerror="this.style.display='none'">
            <div class="lp-nav-brand-text">
                <strong>MSU-IIT OGC</strong>
                <span>Guidance & Counseling Portal</span>
            </div>
        </a>
        <div class="lp-nav-links">
            @guest
                <a href="{{ route('register') }}" class="lp-nav-register">Register</a>
                <a href="{{ route('login') }}" class="lp-nav-login"><i class="fas fa-sign-in-alt"></i> Login</a>
            @endguest
        </div>
    </nav>

    <!-- Hero -->
    <section class="lp-hero">
        <div class="lp-hero-left">
            <div class="lp-badge">Now available for MSU-IIT students</div>

            <h1 class="lp-hero-title">
                Your <em>guidance</em><br>
                services, all in<br>
                one place.
            </h1>

            <p class="lp-hero-desc">
                The MSU-IIT Office of Guidance and Counseling Portal gives students a secure, organized way to book appointments, access mental health resources, and stay connected with counseling support.
            </p>

            <div class="lp-hero-cta">
                <a href="{{ route('register') }}" class="lp-btn-primary">
                    <i class="fas fa-user-plus"></i> Get Started
                </a>
                <a href="{{ route('login') }}" class="lp-btn-secondary">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
            </div>

            <div class="lp-stats">
                <div>
                    <div class="lp-stat-num">100%</div>
                    <div class="lp-stat-label">Confidential</div>
                </div>
                <div>
                    <div class="lp-stat-num">24/7</div>
                    <div class="lp-stat-label">Resource Access</div>
                </div>
                <div>
                    <div class="lp-stat-num">MSU-IIT</div>
                    <div class="lp-stat-label">Official Portal</div>
                </div>
            </div>
        </div>

        <div class="lp-hero-right">
            <div class="lp-card-stack">
                <div class="lp-main-card">
                    <div class="lp-card-header">
                        <div class="lp-card-icon"><i class="fas fa-heart"></i></div>
                        <div>
                            <div class="lp-card-title">Student Services</div>
                            <div class="lp-card-sub">Office of Guidance & Counseling</div>
                        </div>
                    </div>
                    <div class="lp-service-list">
                        <div class="lp-service-item">
                            <div class="lp-service-left">
                                <div class="lp-service-dot" style="background:#15803d;"></div>
                                <div class="lp-service-name">Book an Appointment</div>
                            </div>
                            <span class="lp-service-badge badge-open">Open</span>
                        </div>
                        <div class="lp-service-item">
                            <div class="lp-service-left">
                                <div class="lp-service-dot" style="background:#F8650C;"></div>
                                <div class="lp-service-name">Mental Health Resources</div>
                            </div>
                            <span class="lp-service-badge badge-avail">Available</span>
                        </div>
                        <div class="lp-service-item">
                            <div class="lp-service-left">
                                <div class="lp-service-dot" style="background:#820000;"></div>
                                <div class="lp-service-name">Wellness Events</div>
                            </div>
                            <span class="lp-service-badge badge-new">New</span>
                        </div>
                        <div class="lp-service-item">
                            <div class="lp-service-left">
                                <div class="lp-service-dot" style="background:#FFC917;"></div>
                                <div class="lp-service-name">Student Feedback</div>
                            </div>
                            <span class="lp-service-badge badge-avail">Available</span>
                        </div>
                    </div>
                    <div class="lp-card-footer">
                        <div class="lp-footer-stat">
                            <div class="lp-footer-num">4+</div>
                            <div class="lp-footer-label">Services</div>
                        </div>
                        <div class="lp-footer-stat">
                            <div class="lp-footer-num">100%</div>
                            <div class="lp-footer-label">Secure</div>
                        </div>
                        <div class="lp-footer-stat">
                            <div class="lp-footer-num">Free</div>
                            <div class="lp-footer-label">For Students</div>
                        </div>
                    </div>
                </div>

                <div class="lp-float-card">
                    <div class="lp-float-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="lp-float-label">Next Available</div>
                        <div class="lp-float-val">Book Today</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Page content (login/register form) -->
    {{ $slot }}

</body>
</html>
