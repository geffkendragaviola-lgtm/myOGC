<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
    }

    .auth-page {
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
        background: #f5f7fb;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px;
    }

    .auth-shell {
        width: 100%;
        max-width: 1120px;
        min-height: 720px;
        background: #ffffff;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.10);
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
    }

    .auth-left {
        background: linear-gradient(180deg, #fff8f8 0%, #fffefe 100%);
        padding: 56px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-right: 1px solid #f1f1f1;
    }

    .brand-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        border-radius: 999px;
        background: #fff1f1;
        color: #d40000;
        font-size: 13px;
        font-weight: 700;
        width: fit-content;
        margin-bottom: 28px;
    }

    .auth-left h1 {
        margin: 0 0 14px;
        font-size: 48px;
        line-height: 1.05;
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.03em;
    }

    .auth-left p {
        margin: 0;
        font-size: 16px;
        line-height: 1.7;
        color: #6b7280;
        max-width: 520px;
    }

    .auth-points {
        margin-top: 32px;
        display: grid;
        gap: 14px;
    }

    .auth-point {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #374151;
        font-size: 15px;
        font-weight: 500;
    }

    .point-icon {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: #fff4f4;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d40000;
        flex-shrink: 0;
    }

    .auth-right {
        padding: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
    }

    .auth-card {
        width: 100%;
        max-width: 460px;
    }

    .card-title {
        font-size: 34px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 8px;
        letter-spacing: -0.02em;
    }

    .card-subtitle {
        font-size: 15px;
        color: #6b7280;
        margin-bottom: 32px;
    }

    .card-subtitle a,
    .register-row a,
    .forgot-link {
        color: #d40000;
        text-decoration: none;
        font-weight: 700;
    }

    .card-subtitle a:hover,
    .register-row a:hover,
    .forgot-link:hover {
        text-decoration: underline;
    }

    .field-group {
        margin-bottom: 20px;
    }

    .field-label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .input-wrap {
        position: relative;
    }

    .field-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        width: 18px;
        height: 18px;
        pointer-events: none;
    }

    .auth-input {
        width: 100%;
        height: 56px;
        border: 1.5px solid #e5e7eb;
        border-radius: 16px;
        background: #ffffff;
        padding: 0 16px 0 48px;
        font-size: 15px;
        color: #111827;
        outline: none;
        transition: 0.2s ease;
    }

    .auth-input:focus {
        border-color: #d40000;
        box-shadow: 0 0 0 4px rgba(212, 0, 0, 0.08);
    }

    .options-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 6px 0 24px;
        gap: 12px;
    }

    .remember-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #4b5563;
    }

    .remember-wrap input[type="checkbox"] {
        accent-color: #d40000;
        width: 16px;
        height: 16px;
    }

    .btn-login {
        width: 100%;
        height: 58px;
        border: none;
        border-radius: 16px;
        background: #d40000;
        color: #ffffff;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.2s ease;
        box-shadow: 0 12px 24px rgba(212, 0, 0, 0.18);
    }

    .btn-login:hover {
        background: #ba0000;
        transform: translateY(-1px);
    }

    .register-row {
        margin-top: 22px;
        text-align: center;
        font-size: 14px;
        color: #6b7280;
    }

    @media (max-width: 980px) {
        .auth-shell {
            grid-template-columns: 1fr;
            min-height: auto;
        }

        .auth-left {
            display: none;
        }

        .auth-right {
            padding: 32px 22px;
        }

        .auth-page {
            padding: 16px;
        }

        .card-title {
            font-size: 28px;
        }
    }
</style>

<div class="auth-page">
    <div class="auth-shell">

        <div class="auth-left">
            <div class="brand-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                </svg>
                MSU-IIT Guidance Portal
            </div>

            <h1>Welcome back.</h1>
            <p>
                Access your student portal with a cleaner, more modern experience.
                This layout is larger, calmer, and easier to focus on.
            </p>

            <div class="auth-points">
                <div class="auth-point">
                    <div class="point-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    Secure student account access
                </div>

                <div class="auth-point">
                    <div class="point-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                    Built for MSU-IIT students
                </div>

                <div class="auth-point">
                    <div class="point-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                    </div>
                    Cleaner and more readable UI
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-card">
                <h2 class="card-title">Log in</h2>
                <div class="card-subtitle">
                    Don’t have an account?
                    <a href="{{ route('register') }}">Create one here</a>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="field-group">
                        <label class="field-label" for="email">Email Address</label>
                        <div class="input-wrap">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input
                                id="email"
                                class="auth-input"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="username@g.msuiit.edu.ph"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="password">Password</label>
                        <div class="input-wrap">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <input
                                id="password"
                                class="auth-input"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="options-row">
                        <label class="remember-wrap">
                            <input type="checkbox" name="remember" id="remember_me">
                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login">
                        Sign In
                    </button>
                </form>

                <div class="register-row">
                    New student?
                    <a href="{{ route('register') }}">Register here</a>
                </div>
            </div>
        </div>

    </div>
</div>
</x-guest-layout>