<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap');
    .auth-wrapper {
        font-family: 'Nunito', sans-serif;
        min-height: 100vh;
        background: linear-gradient(145deg, #f0f4ff 0%, #e8f0fe 40%, #fce8e8 100%);
        display: flex; align-items: center; justify-content: center;
        padding: 2rem 1rem; position: relative; overflow: hidden;
    }
    .auth-wrapper::before {
        content: ''; position: absolute; top: -60px; right: -60px;
        width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(240,0,0,0.07) 0%, transparent 70%);
        border-radius: 50%;
    }
    .auth-card {
        background: #fff; border-radius: 28px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.10), 0 4px 16px rgba(0,0,0,0.06);
        width: 100%; max-width: 420px; padding: 2.5rem;
        position: relative; z-index: 1;
        animation: cardIn 0.5s cubic-bezier(.22,1,.36,1) both;
    }
    @keyframes cardIn { from{opacity:0;transform:translateY(32px) scale(0.97)} to{opacity:1;transform:translateY(0) scale(1)} }
    .illustration-wrap { display: flex; justify-content: center; margin-bottom: 0.75rem; }
    .auth-title { font-family:'Poppins',sans-serif; font-size:1.5rem; font-weight:700; color:#1a1a2e; text-align:center; margin:0 0 0.35rem; }
    .auth-desc { font-size:0.84rem; color:#888; text-align:center; margin-bottom:1.75rem; line-height:1.6; }
    .field-label { font-size:0.73rem; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.35rem; display:block; }
    .field-wrap { position:relative; margin-bottom:1rem; }
    .field-icon { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#ccc; width:17px; height:17px; pointer-events:none; }
    .auth-input {
        width:100%; padding:0.75rem 1rem 0.75rem 2.5rem;
        border:1.5px solid #e8e8e8; border-radius:12px;
        font-size:0.91rem; font-family:'Nunito',sans-serif; color:#333; background:#fafafa;
        outline:none; transition:all 0.2s; box-sizing:border-box;
    }
    .auth-input:focus { border-color:#F00000; box-shadow:0 0 0 3px rgba(240,0,0,0.09); background:#fff; }
    .btn-primary {
        width:100%; padding:0.82rem;
        background:linear-gradient(135deg,#F00000 0%,#c20000 100%);
        color:#fff; border:none; border-radius:12px;
        font-family:'Poppins',sans-serif; font-size:0.95rem; font-weight:600; cursor:pointer;
        display:flex; align-items:center; justify-content:center; gap:0.5rem;
        box-shadow:0 4px 18px rgba(240,0,0,0.28);
        transition:transform 0.15s, box-shadow 0.2s;
    }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(240,0,0,0.35); }
    .back-link { text-align:center; margin-top:1.2rem; font-size:0.84rem; }
    .back-link a { color:#888; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; }
    .back-link a:hover { color:#F00000; }
</style>

<div class="auth-wrapper">
<div class="auth-card">
    <div class="illustration-wrap">
        <svg width="110" height="100" viewBox="0 0 110 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Building -->
            <rect x="18" y="32" width="74" height="58" rx="4" fill="#e8eaf6"/>
            <rect x="28" y="23" width="54" height="13" rx="2" fill="#c5cae9"/>
            <rect x="40" y="14" width="30" height="12" rx="2" fill="#9fa8da"/>
            <polygon points="40,14 55,5 70,14" fill="#7986cb"/>
            <circle cx="55" cy="28" r="5" fill="white" stroke="#9fa8da" stroke-width="1"/>
            <line x1="55" y1="26" x2="55" y2="28" stroke="#555" stroke-width="1" stroke-linecap="round"/>
            <line x1="55" y1="28" x2="57" y2="28" stroke="#555" stroke-width="1" stroke-linecap="round"/>
            <rect x="28" y="44" width="14" height="11" rx="2" fill="#bbdefb"/>
            <rect x="48" y="44" width="14" height="11" rx="2" fill="#bbdefb"/>
            <rect x="68" y="44" width="14" height="11" rx="2" fill="#bbdefb"/>
            <rect x="28" y="62" width="14" height="10" rx="2" fill="#bbdefb"/>
            <rect x="68" y="62" width="14" height="10" rx="2" fill="#bbdefb"/>
            <rect x="44" y="66" width="22" height="24" rx="3" fill="#7986cb"/>
            <circle cx="53" cy="79" r="1.5" fill="white"/>
            <!-- Lock icon overlay -->
            <circle cx="85" cy="25" r="14" fill="#fff5f5" stroke="#fde0e0" stroke-width="1.5"/>
            <rect x="78" y="24" width="14" height="10" rx="2" fill="#F00000" opacity=".15"/>
            <rect x="79" y="25" width="12" height="8" rx="1.5" fill="#F00000"/>
            <path d="M82 25v-3a3 3 0 016 0v3" stroke="#F00000" stroke-width="1.5" stroke-linecap="round" fill="none"/>
            <circle cx="85" cy="29" r="1.5" fill="white"/>
        </svg>
    </div>

    <h1 class="auth-title">Forgot Password?</h1>
    <p class="auth-desc">No problem. Enter your MSU-IIT email and we'll send you a reset link.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="field-wrap">
            <label class="field-label">MSU-IIT Email Address</label>
            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus placeholder="username@g.msuiit.edu.ph" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="btn-primary">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Send Reset Link
        </button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Login
        </a>
    </div>
</div>
</div>
</x-guest-layout>