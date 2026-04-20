<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'OGC Portal') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
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
            border: none; cursor: pointer; font-family: 'Inter', sans-serif;
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
            background: #6b0000 url('{{ asset('images/ogc-banner.jpg') }}') center center / cover no-repeat;
            position: relative;
        }
        .lp-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: rgba(90, 0, 0, 0.72);
        }
        .lp-hero-left {
            padding: 80px 64px 80px 80px;
            display: flex; flex-direction: column; justify-content: center;
            position: relative; z-index: 1;
            text-align: center; align-items: center;
        }
        .lp-hero-welcome {
            font-size: 15px; font-weight: 600; color: rgba(255,255,255,0.85);
            letter-spacing: 0.04em; margin-bottom: 16px;
        }
        .lp-hero-title {
            font-size: clamp(36px, 4.5vw, 58px);
            font-weight: 900; line-height: 1.1;
            letter-spacing: -0.02em; color: #fff;
            margin-bottom: 20px;
        }
        .lp-hero-divider {
            width: 60px; height: 3px;
            background: #FFC917;
            border-radius: 2px; margin: 0 auto 24px;
        }
        .lp-hero-quote {
            font-size: 16px; line-height: 1.8; color: rgba(255,255,255,0.88);
            font-style: italic; max-width: 560px; margin-bottom: 12px;
        }
        .lp-quote-mark { font-size: 22px; color: #FFC917; font-style: normal; }
        .lp-hero-attribution {
            font-size: 13px; font-weight: 700; color: #FFC917;
            letter-spacing: 0.03em; margin-bottom: 32px;
        }
        .lp-hero-cta { display: flex; gap: 14px; flex-wrap: wrap; justify-content: center; }
        .lp-btn-primary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 32px; border-radius: 12px;
            background: #FFC917; color: #5a0000;
            font-size: 16px; font-weight: 800; text-decoration: none;
            box-shadow: 0 8px 24px rgba(255,201,23,0.3);
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .lp-btn-primary:hover { background: #e6b400; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(255,201,23,0.4); }
        .lp-btn-secondary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 32px; border-radius: 12px;
            background: rgba(255,255,255,0.15); color: #fff;
            border: 1.5px solid rgba(255,255,255,0.4);
            font-size: 16px; font-weight: 700; text-decoration: none;
            cursor: pointer; font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, background 0.2s;
        }
        .lp-btn-secondary:hover { border-color: #fff; background: rgba(255,255,255,0.25); }

        /* ── HERO RIGHT (floating card) ── */
        .lp-hero-right {
            padding: 80px 80px 80px 40px;
            display: flex; align-items: center; justify-content: center;
            position: relative; z-index: 1;
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

        /* ── AUTH MODAL ── */
        .auth-overlay {
            position: fixed; inset: 0; z-index: 200;
            background: rgba(15,23,42,0.55);
            backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center;
            padding: 24px;
        }
        .auth-overlay.active { display: flex; }
        .auth-modal {
            background: #fff; border-radius: 24px;
            width: 100%; max-width: 460px; padding: 44px 40px;
            box-shadow: 0 32px 80px rgba(15,23,42,0.18);
            position: relative; animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .auth-modal-close {
            position: absolute; top: 18px; right: 18px;
            width: 32px; height: 32px; border-radius: 8px;
            background: #f1f5f9; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; font-size: 14px; transition: background 0.2s;
        }
        .auth-modal-close:hover { background: #e2e8f0; color: #0f172a; }
        .auth-modal-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; }
        .auth-modal-logo img { height: 36px; width: 36px; object-fit: contain; }
        .auth-modal-logo-text strong { display: block; font-size: 13px; font-weight: 800; color: #820000; }
        .auth-modal-logo-text span { font-size: 11px; color: #94a3b8; }
        .auth-modal h2 { font-size: 30px; font-weight: 900; color: #0f172a; letter-spacing: -0.03em; margin-bottom: 6px; }
        .auth-modal-sub { font-size: 14px; color: #64748b; margin-bottom: 28px; }
        .auth-field { margin-bottom: 18px; }
        .auth-field label { display: block; font-size: 12px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 8px; }
        .auth-input-wrap { position: relative; }
        .auth-input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; pointer-events: none; }
        .auth-input {
            width: 100%; height: 52px; border: 1.5px solid #e2e8f0; border-radius: 12px;
            background: #f8fafc; padding: 0 16px 0 44px; font-size: 15px; color: #0f172a;
            outline: none; font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .auth-input::placeholder { color: #94a3b8; }
        .auth-input:focus { border-color: #820000; background: #fff; box-shadow: 0 0 0 4px rgba(130,0,0,0.08); }
        .auth-options { display: flex; align-items: center; justify-content: space-between; margin: 4px 0 24px; font-size: 13px; }
        .auth-remember { display: flex; align-items: center; gap: 8px; color: #475569; }
        .auth-remember input { accent-color: #820000; width: 15px; height: 15px; }
        .auth-forgot { color: #820000; font-weight: 700; text-decoration: none; font-size: 13px; }
        .auth-forgot:hover { text-decoration: underline; }
        .auth-submit {
            width: 100%; height: 52px; border: none; border-radius: 12px;
            background: #820000;
            color: #fff; font-size: 16px; font-weight: 800; cursor: pointer;
            font-family: 'Inter', sans-serif; box-shadow: 0 8px 24px rgba(130,0,0,0.25);
            transition: transform 0.15s, box-shadow 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .auth-submit:hover { transform: translateY(-1px); box-shadow: 0 12px 32px rgba(130,0,0,0.3); }
        .auth-divider { text-align: center; margin: 20px 0; font-size: 13px; color: #94a3b8; position: relative; }
        .auth-divider::before, .auth-divider::after { content: ''; position: absolute; top: 50%; width: 38%; height: 1px; background: #e2e8f0; }
        .auth-divider::before { left: 0; }
        .auth-divider::after { right: 0; }
        .auth-register-link { text-align: center; font-size: 14px; color: #64748b; }
        .auth-register-link a { color: #820000; font-weight: 700; text-decoration: none; }
        .auth-register-link a:hover { text-decoration: underline; }

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
                <strong>myOGC</strong>
                <span>MSU-IIT Guidance & Counseling Portal</span>
            </div>
        </a>
        <div class="lp-nav-links">
            @guest
                <a href="{{ route('register') }}" class="lp-nav-register">Register</a>
                <button onclick="openLoginModal()" class="lp-nav-login"><i class="fas fa-sign-in-alt"></i> Login</button>
            @endguest
        </div>
    </nav>

    <!-- Hero -->
    <section class="lp-hero">
        <div class="lp-hero-left">
            <p class="lp-hero-welcome">Welcome to MSU-IIT OGC</p>

            <h1 class="lp-hero-title">
                Office of Guidance<br>and Counseling
            </h1>

            <div class="lp-hero-divider"></div>

            <p class="lp-hero-quote">
                <span class="lp-quote-mark">&ldquo;</span>
                Make it a daily practice to purposefully look for joy — and when you find it, take a moment, inhale it, treasure it, and take it with you.
                <span class="lp-quote-mark">&rdquo;</span>
            </p>

            <p class="lp-hero-attribution">— Office of Guidance and Counseling</p>

            <div class="lp-hero-divider"></div>

            <div class="lp-hero-cta">
                <a href="{{ route('register') }}" class="lp-btn-primary">
                    <i class="fas fa-user-plus"></i> Get Started
                </a>
                <button onclick="openLoginModal()" class="lp-btn-secondary">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
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
                </div>
            </div>
        </div>
    </section>

    <!-- Page content (login/register form) -->
    {{ $slot }}

    <!-- Login Modal -->
    <div class="auth-overlay" id="authOverlay">
        <div class="auth-modal" id="authModal">
            <button class="auth-modal-close" onclick="closeLoginModal()" title="Close"><i class="fas fa-xmark"></i></button>

            <div class="auth-modal-logo">
                <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" onerror="this.style.display='none'">
                <div class="auth-modal-logo-text">
                    <strong>MSU-IIT OGC</strong>
                    <span>Guidance & Counseling</span>
                </div>
            </div>

            <h2>Welcome back</h2>
            <p class="auth-modal-sub">Sign in to your student portal account.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="auth-field">
                    <label for="modal_email">Email Address</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-envelope auth-input-icon"></i>
                        <input id="modal_email" class="auth-input" type="email" name="email"
                            value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="username@g.msuiit.edu.ph" />
                    </div>
                </div>
                <div class="auth-field">
                    <label for="modal_password">Password</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-lock auth-input-icon"></i>
                        <input id="modal_password" class="auth-input" type="password" name="password"
                            required autocomplete="current-password" placeholder="Enter your password" />
                    </div>
                </div>
                <div class="auth-options">
                    <label class="auth-remember">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a class="auth-forgot" href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>
                <button type="submit" class="auth-submit">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="auth-divider">or</div>
            <div class="auth-register-link">
                Don't have an account? <a href="{{ route('register') }}">Register here</a>
            </div>
        </div>
    </div>

    <script>
        function openLoginModal() {
            document.getElementById('authOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeLoginModal() {
            document.getElementById('authOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
        document.getElementById('authOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeLoginModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLoginModal();
        });

        @if($errors->any())
            openLoginModal();
        @endif
    </script>

</body>
</html>
