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
        background: #fff; border-radius: 24px;
        width: 100%; max-width: 440px;
        padding: 44px 40px;
        box-shadow: 0 32px 80px rgba(15,23,42,0.18);
        position: relative;
        animation: slideUp 0.3s ease;
        font-family: 'Inter', sans-serif;
    }
    @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .auth-modal-close {
        position: absolute; top: 18px; right: 18px;
        width: 32px; height: 32px; border-radius: 8px;
        background: #f1f5f9; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #64748b; font-size: 14px; transition: background 0.2s; text-decoration: none;
    }
    .auth-modal-close:hover { background: #e2e8f0; color: #0f172a; }
    .auth-modal-icon {
        width: 56px; height: 56px; border-radius: 16px;
        background: linear-gradient(135deg, #fff5f5, #fde0e0);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 20px;
    }
    .auth-modal-icon i { color: #820000; font-size: 24px; }
    .auth-modal h2 { font-size: 26px; font-weight: 900; color: #0f172a; letter-spacing: -0.03em; margin-bottom: 8px; }
    .auth-modal-sub { font-size: 14px; color: #64748b; line-height: 1.7; margin-bottom: 28px; }
    .auth-field { margin-bottom: 20px; }
    .auth-field label { display: block; font-size: 12px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 8px; }
    .auth-input-wrap { position: relative; }
    .auth-input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; pointer-events: none; }
    .auth-input { width: 100%; height: 52px; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #f8fafc; padding: 0 16px 0 44px; font-size: 15px; color: #0f172a; outline: none; font-family: 'Inter', sans-serif; transition: border-color 0.2s, box-shadow 0.2s; }
    .auth-input:focus { border-color: #820000; background: #fff; box-shadow: 0 0 0 4px rgba(130,0,0,0.08); }
    .auth-submit { width: 100%; height: 52px; border: none; border-radius: 12px; background: linear-gradient(135deg, #820000, #F8650C); color: #fff; font-size: 16px; font-weight: 800; cursor: pointer; font-family: 'Inter', sans-serif; box-shadow: 0 8px 24px rgba(130,0,0,0.25); transition: transform 0.15s, box-shadow 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .auth-submit:hover { transform: translateY(-1px); box-shadow: 0 12px 32px rgba(130,0,0,0.3); }
    .auth-back { text-align: center; margin-top: 18px; font-size: 14px; }
    .auth-back a { color: #820000; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .auth-back a:hover { text-decoration: underline; }
</style>

<div class="auth-overlay">
    <div class="auth-modal">
        <a href="{{ route('login') }}" class="auth-modal-close"><i class="fas fa-xmark"></i></a>

        <div class="auth-modal-icon"><i class="fas fa-lock"></i></div>
        <h2>Forgot password?</h2>
        <p class="auth-modal-sub">No problem. Enter your MSU-IIT email and we'll send you a password reset link.</p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="auth-field">
                <label for="email">MSU-IIT Email Address</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-envelope auth-input-icon"></i>
                    <input id="email" class="auth-input" type="email" name="email"
                        value="{{ old('email') }}" required autofocus
                        placeholder="username@g.msuiit.edu.ph" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <button type="submit" class="auth-submit">
                <i class="fas fa-paper-plane"></i> Send Reset Link
            </button>
        </form>

        <div class="auth-back">
            <a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> Back to Login</a>
        </div>
    </div>
</div>
</x-guest-layout>
