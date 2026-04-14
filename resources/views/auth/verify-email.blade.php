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
    .auth-modal-icon {
        width: 64px; height: 64px; border-radius: 18px;
        background: linear-gradient(135deg, #fff5f5, #fde0e0);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
    }
    .auth-modal-icon i { color: #820000; font-size: 28px; }
    .auth-modal h2 { font-size: 24px; font-weight: 900; color: #0f172a; letter-spacing: -0.03em; margin-bottom: 10px; text-align: center; }
    .auth-modal-sub { font-size: 14px; color: #64748b; line-height: 1.7; margin-bottom: 24px; text-align: center; }
    .alert-success { background: #effaf3; border: 1px solid #ccebd6; color: #15803d; border-radius: 12px; padding: 14px 16px; font-size: 14px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .auth-submit { width: 100%; height: 52px; border: none; border-radius: 12px; background: linear-gradient(135deg, #820000, #F8650C); color: #fff; font-size: 15px; font-weight: 800; cursor: pointer; font-family: 'Inter', sans-serif; box-shadow: 0 8px 24px rgba(130,0,0,0.25); transition: transform 0.15s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 12px; }
    .auth-submit:hover { transform: translateY(-1px); }
    .auth-logout { width: 100%; height: 46px; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #fff; color: #64748b; font-size: 14px; font-weight: 700; cursor: pointer; font-family: 'Inter', sans-serif; transition: border-color 0.2s, color 0.2s; }
    .auth-logout:hover { border-color: #820000; color: #820000; }
</style>

<div class="auth-overlay">
    <div class="auth-modal">
        <div class="auth-modal-icon"><i class="fas fa-envelope-open-text"></i></div>
        <h2>Check your inbox</h2>
        <p class="auth-modal-sub">
            Thanks for signing up! We sent a verification link to your MSU-IIT email. Click it to activate your account.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                A new verification link has been sent to your email address.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="auth-submit">
                <i class="fas fa-paper-plane"></i> Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="auth-logout">Log Out</button>
        </form>
    </div>
</div>
</x-guest-layout>
