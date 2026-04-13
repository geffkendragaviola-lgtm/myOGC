{{-- reset-password.blade.php --}}
<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap');
    .auth-wrapper { font-family:'Nunito',sans-serif; min-height:100vh; background:linear-gradient(145deg,#f0f4ff 0%,#e8f0fe 40%,#fce8e8 100%); display:flex; align-items:center; justify-content:center; padding:2rem 1rem; position:relative; overflow:hidden; }
    .auth-wrapper::before { content:''; position:absolute; top:-60px; right:-60px; width:320px; height:320px; background:radial-gradient(circle,rgba(240,0,0,0.07) 0%,transparent 70%); border-radius:50%; }
    .auth-card { background:#fff; border-radius:28px; box-shadow:0 20px 60px rgba(0,0,0,0.10),0 4px 16px rgba(0,0,0,0.06); width:100%; max-width:440px; padding:2.5rem; position:relative; z-index:1; animation:cardIn 0.5s cubic-bezier(.22,1,.36,1) both; }
    @keyframes cardIn { from{opacity:0;transform:translateY(32px) scale(0.97)} to{opacity:1;transform:translateY(0) scale(1)} }
    .card-icon { width:60px; height:60px; background:linear-gradient(135deg,#fff5f5,#fde0e0); border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 1.1rem; }
    .auth-title { font-family:'Poppins',sans-serif; font-size:1.5rem; font-weight:700; color:#1a1a2e; text-align:center; margin:0 0 0.3rem; }
    .auth-desc { font-size:0.83rem; color:#888; text-align:center; margin-bottom:1.75rem; }
    .field-label { font-size:0.73rem; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.35rem; display:block; }
    .field-wrap { position:relative; margin-bottom:1rem; }
    .field-icon { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#ccc; width:17px; height:17px; pointer-events:none; }
    .auth-input { width:100%; padding:0.75rem 1rem 0.75rem 2.5rem; border:1.5px solid #e8e8e8; border-radius:12px; font-size:0.91rem; font-family:'Nunito',sans-serif; color:#333; background:#fafafa; outline:none; transition:all 0.2s; box-sizing:border-box; }
    .auth-input:focus { border-color:#F00000; box-shadow:0 0 0 3px rgba(240,0,0,0.09); background:#fff; }
    .btn-primary { width:100%; padding:0.82rem; background:linear-gradient(135deg,#F00000 0%,#c20000 100%); color:#fff; border:none; border-radius:12px; font-family:'Poppins',sans-serif; font-size:0.95rem; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.5rem; box-shadow:0 4px 18px rgba(240,0,0,0.28); transition:transform 0.15s,box-shadow 0.2s; }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(240,0,0,0.35); }
</style>
<div class="auth-wrapper">
<div class="auth-card">
    <div class="card-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F00000" stroke-width="2.5"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
    </div>
    <h1 class="auth-title">Reset Password</h1>
    <p class="auth-desc">Enter your email and choose a new password below.</p>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="field-wrap">
            <label class="field-label">Email Address</label>
            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="username@g.msuiit.edu.ph" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="field-wrap">
            <label class="field-label">New Password</label>
            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="field-wrap">
            <label class="field-label">Confirm New Password</label>
            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <button type="submit" class="btn-primary">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Reset Password
        </button>
    </form>
</div>
</div>
</x-guest-layout>