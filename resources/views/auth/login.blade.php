<x-guest-layout>
<style>
    .auth-overlay {
        position: fixed; inset: 0; z-index: 200;
        background: rgba(15,23,42,0.55);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        padding: 24px;
    }
    .auth-modal {
        background: #fff;
        border-radius: 24px;
        width: 100%; max-width: 460px;
        padding: 44px 40px;
        box-shadow: 0 32px 80px rgba(15,23,42,0.18);
        position: relative;
        animation: slideUp 0.3s ease;
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
    .auth-modal-logo {
        display: flex; align-items: center; gap: 10px; margin-bottom: 28px;
    }
    .auth-modal-logo img { height: 36px; width: 36px; object-fit: contain; }
    .auth-modal-logo-text strong { display: block; font-size: 13px; font-weight: 800; color: #820000; }
    .auth-modal-logo-text span { font-size: 11px; color: #94a3b8; }
    .auth-modal h2 {
        font-size: 30px; font-weight: 900; color: #0f172a;
        letter-spacing: -0.03em; margin-bottom: 6px;
    }
    .auth-modal-sub { font-size: 14px; color: #64748b; margin-bottom: 28px; }
    .auth-field { margin-bottom: 18px; }
    .auth-field label {
        display: block; font-size: 12px; font-weight: 800;
        color: #334155; text-transform: uppercase; letter-spacing: 0.07em;
        margin-bottom: 8px;
    }
    .auth-input-wrap { position: relative; }
    .auth-input-icon {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        color: #94a3b8; font-size: 14px; pointer-events: none;
    }
    .auth-input {
        width: 100%; height: 52px;
        border: 1.5px solid #e2e8f0; border-radius: 12px;
        background: #f8fafc; padding: 0 16px 0 44px;
        font-size: 15px; color: #0f172a; outline: none;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .auth-input::placeholder { color: #94a3b8; }
    .auth-input:focus {
        border-color: #820000; background: #fff;
        box-shadow: 0 0 0 4px rgba(130,0,0,0.08);
    }
    .auth-options {
        display: flex; align-items: center; justify-content: space-between;
        margin: 4px 0 24px; font-size: 13px;
    }
    .auth-remember { display: flex; align-items: center; gap: 8px; color: #475569; }
    .auth-remember input { accent-color: #820000; width: 15px; height: 15px; }
    .auth-forgot { color: #820000; font-weight: 700; text-decoration: none; font-size: 13px; }
    .auth-forgot:hover { text-decoration: underline; }
    .auth-submit {
        width: 100%; height: 52px; border: none; border-radius: 12px;
        background: linear-gradient(135deg, #820000, #F8650C);
        color: #fff; font-size: 16px; font-weight: 800;
        cursor: pointer; font-family: 'Inter', sans-serif;
        box-shadow: 0 8px 24px rgba(130,0,0,0.25);
        transition: transform 0.15s, box-shadow 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .auth-submit:hover { transform: translateY(-1px); box-shadow: 0 12px 32px rgba(130,0,0,0.3); }
    .auth-divider {
        text-align: center; margin: 20px 0;
        font-size: 13px; color: #94a3b8; position: relative;
    }
    .auth-divider::before, .auth-divider::after {
        content: ''; position: absolute; top: 50%; width: 38%; height: 1px; background: #e2e8f0;
    }
    .auth-divider::before { left: 0; }
    .auth-divider::after { right: 0; }
    .auth-register-link {
        text-align: center; font-size: 14px; color: #64748b;
    }
    .auth-register-link a { color: #820000; font-weight: 700; text-decoration: none; }
    .auth-register-link a:hover { text-decoration: underline; }
</style>

<div class="auth-overlay">
    <div class="auth-modal">
        <a href="/" class="auth-modal-close" title="Back to home"><i class="fas fa-times"></i></a>

        <div class="auth-modal-logo">
            <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" onerror="this.style.display='none'">
            <div class="auth-modal-logo-text">
                <strong>MSU-IIT OGC</strong>
                <span>Guidance & Counseling</span>
            </div>
        </div>

        <h2>Welcome back</h2>
        <p class="auth-modal-sub">Sign in to your student portal account.</p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="auth-field">
                <label for="email">Email Address</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-envelope auth-input-icon"></i>
                    <input id="email" class="auth-input" type="email" name="email"
                        value="{{ old('email') }}" required autofocus autocomplete="username"
                        placeholder="username@g.msuiit.edu.ph" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="auth-field">
                <label for="password">Password</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-lock auth-input-icon"></i>
                    <input id="password" class="auth-input" type="password" name="password"
                        required autocomplete="current-password" placeholder="Enter your password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="auth-options">
                <label class="auth-remember">
                    <input type="checkbox" name="remember" id="remember_me"> Remember me
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
</x-guest-layout>
