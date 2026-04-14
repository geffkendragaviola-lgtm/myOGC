<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap');
    .auth-wrapper { font-family:'Nunito',sans-serif; min-height:100vh; background:linear-gradient(145deg,#f0f4ff 0%,#e8f0fe 40%,#fce8e8 100%); display:flex; align-items:center; justify-content:center; padding:2rem 1rem; position:relative; }
    .auth-wrapper::before { content:''; position:absolute; top:-60px; right:-60px; width:320px; height:320px; background:radial-gradient(circle,rgba(240,0,0,0.07) 0%,transparent 70%); border-radius:50%; }
    .auth-card { background:#fff; border-radius:28px; box-shadow:0 20px 60px rgba(0,0,0,0.10),0 4px 16px rgba(0,0,0,0.06); width:100%; max-width:430px; padding:2.5rem; position:relative; z-index:1; animation:cardIn 0.5s cubic-bezier(.22,1,.36,1) both; }
    @keyframes cardIn { from{opacity:0;transform:translateY(32px) scale(0.97)} to{opacity:1;transform:translateY(0) scale(1)} }
    .envelope-wrap { display:flex; justify-content:center; margin-bottom:1.1rem; }
    .auth-title { font-family:'Poppins',sans-serif; font-size:1.45rem; font-weight:700; color:#1a1a2e; text-align:center; margin:0 0 0.35rem; }
    .auth-desc { font-size:0.84rem; color:#888; text-align:center; margin-bottom:1.5rem; line-height:1.65; }
    .success-banner { background:#e8f5e9; border:1.5px solid #c8e6c9; color:#2e7d32; border-radius:12px; padding:0.85rem 1rem; font-size:0.84rem; margin-bottom:1.2rem; display:flex; align-items:flex-start; gap:0.5rem; }
    .btn-primary { width:100%; padding:0.82rem; background:linear-gradient(135deg,#F00000 0%,#c20000 100%); color:#fff; border:none; border-radius:12px; font-family:'Poppins',sans-serif; font-size:0.93rem; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.5rem; box-shadow:0 4px 18px rgba(240,0,0,0.28); transition:transform 0.15s,box-shadow 0.2s; margin-bottom:0.85rem; }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(240,0,0,0.35); }
    .btn-logout { width:100%; padding:0.72rem; background:#fff; border:1.5px solid #e0e0e0; border-radius:12px; font-family:'Poppins',sans-serif; font-size:0.88rem; font-weight:600; color:#888; cursor:pointer; transition:all 0.15s; }
    .btn-logout:hover { border-color:#F00000; color:#F00000; }
    .divider { height:1px; background:#eee; margin:1rem 0; }
</style>
<div class="auth-wrapper">
<div class="auth-card">
    <div class="envelope-wrap">
        <svg width="100" height="88" viewBox="0 0 100 88" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="8" y="20" width="84" height="58" rx="6" fill="#e8eaf6"/>
            <rect x="8" y="20" width="84" height="58" rx="6" stroke="#c5cae9" stroke-width="1.5"/>
            <path d="M8 26l42 28 42-28" stroke="#9fa8da" stroke-width="2" fill="none"/>
            <path d="M8 78l30-26M92 78L62 52" stroke="#c5cae9" stroke-width="1.5"/>
            <!-- Check badge -->
            <circle cx="76" cy="22" r="16" fill="#fff5f5" stroke="#fde0e0" stroke-width="2"/>
            <circle cx="76" cy="22" r="11" fill="#F00000" opacity=".12"/>
            <polyline points="70,22 74,26 82,17" stroke="#F00000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <!-- Dots animation -->
            <circle cx="38" cy="49" r="3" fill="#9fa8da" opacity=".4"/>
            <circle cx="50" cy="49" r="3" fill="#9fa8da" opacity=".6"/>
            <circle cx="62" cy="49" r="3" fill="#9fa8da" opacity=".4"/>
        </svg>
    </div>

    <h1 class="auth-title">Check Your Inbox</h1>
    <p class="auth-desc">Thanks for signing up! We sent a verification link to your MSU-IIT email. Click it to activate your account.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="success-banner">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;margin-top:1px"><polyline points="20 6 9 17 4 12"/></svg>
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Resend Verification Email
        </button>
    </form>

    <div class="divider"></div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
            Log Out
        </button>
    </form>
</div>
</div>
</x-guest-layout>