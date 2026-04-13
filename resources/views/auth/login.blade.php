<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100%;
    }

    * {
        box-sizing: border-box;
    }

    .auth-page {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        overflow: auto;
        font-family: 'Inter', sans-serif;
        background:
            linear-gradient(135deg, rgba(255,255,255,0.96), rgba(255,255,255,0.96)),
            linear-gradient(135deg, #f4f6fb 0%, #edf2f9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px;
    }

    .auth-shell {
        width: min(1280px, 100%);
        min-height: 760px;
        background: #ffffff;
        border: 1px solid #e8edf5;
        border-radius: 30px;
        overflow: hidden;
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.08);
    }

    .auth-left {
        position: relative;
        padding: 72px 64px;
        background:
            radial-gradient(circle at top left, rgba(220, 38, 38, 0.07), transparent 30%),
            linear-gradient(180deg, #fbfbfd 0%, #f7f9fc 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .auth-left::after {
        content: "";
        position: absolute;
        right: 0;
        top: 10%;
        width: 1px;
        height: 80%;
        background: #eceff5;
    }

    .portal-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: fit-content;
        padding: 10px 16px;
        border-radius: 999px;
        background: #fff3f3;
        border: 1px solid #ffdede;
        color: #c81e1e;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 28px;
    }

    .auth-left h1 {
        margin: 0 0 18px;
        font-size: 52px;
        line-height: 1.04;
        letter-spacing: -0.04em;
        color: #0f172a;
        font-weight: 800;
        max-width: 620px;
    }

    .auth-left p {
        margin: 0;
        max-width: 590px;
        font-size: 18px;
        line-height: 1.75;
        color: #475569;
    }

    .left-note {
        margin-top: 34px;
        max-width: 580px;
        padding: 20px 22px;
        border-radius: 18px;
        background: #ffffff;
        border: 1px solid #ebeff5;
        color: #334155;
        font-size: 15px;
        line-height: 1.7;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }

    .left-note strong {
        color: #b91c1c;
    }

    .auth-right {
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 56px 52px;
    }

    .auth-card {
        width: 100%;
        max-width: 470px;
    }

    .card-title {
        margin: 0 0 10px;
        font-size: 42px;
        line-height: 1.05;
        letter-spacing: -0.03em;
        font-weight: 800;
        color: #0f172a;
    }

    .card-subtitle {
        margin: 0 0 34px;
        font-size: 15px;
        line-height: 1.6;
        color: #64748b;
    }

    .card-subtitle a,
    .forgot-link,
    .register-row a {
        color: #d70000;
        font-weight: 700;
        text-decoration: none;
    }

    .card-subtitle a:hover,
    .forgot-link:hover,
    .register-row a:hover {
        text-decoration: underline;
    }

    .field-group {
        margin-bottom: 20px;
    }

    .field-label {
        display: block;
        margin-bottom: 9px;
        font-size: 13px;
        font-weight: 800;
        color: #334155;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .input-wrap {
        position: relative;
    }

    .field-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #94a3b8;
        pointer-events: none;
    }

    .auth-input {
        width: 100%;
        height: 60px;
        border: 1.5px solid #dbe3ee;
        border-radius: 16px;
        background: #f8fbff;
        padding: 0 18px 0 52px;
        font-size: 16px;
        color: #0f172a;
        outline: none;
        transition: 0.2s ease;
    }

    .auth-input::placeholder {
        color: #94a3b8;
    }

    .auth-input:focus {
        border-color: #d70000;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(215, 0, 0, 0.08);
    }

    .options-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin: 8px 0 26px;
    }

    .remember-wrap {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #475569;
    }

    .remember-wrap input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #d70000;
    }

    .btn-login {
        width: 100%;
        height: 60px;
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #e00000 0%, #bf0000 100%);
        color: #ffffff;
        font-size: 17px;
        font-weight: 800;
        letter-spacing: 0.01em;
        cursor: pointer;
        box-shadow: 0 14px 30px rgba(191, 0, 0, 0.20);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 36px rgba(191, 0, 0, 0.26);
    }

    .register-row {
        margin-top: 22px;
        text-align: center;
        font-size: 14px;
        color: #64748b;
    }

    .auth-footer-note {
        margin-top: 28px;
        text-align: center;
        font-size: 13px;
        color: #94a3b8;
        line-height: 1.6;
    }

    @media (max-width: 1100px) {
        .auth-shell {
            min-height: auto;
            grid-template-columns: 1fr;
        }

        .auth-left {
            display: none;
        }

        .auth-right {
            padding: 42px 28px;
        }

        .auth-card {
            max-width: 100%;
        }

        .card-title {
            font-size: 34px;
        }
    }

    @media (max-width: 640px) {
        .auth-page {
            padding: 16px;
        }

        .auth-shell {
            border-radius: 22px;
        }

        .auth-right {
            padding: 28px 18px;
        }

        .card-title {
            font-size: 30px;
        }

        .auth-input,
        .btn-login {
            height: 56px;
        }

        .options-row {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="auth-page">
    <div class="auth-shell">
        <div class="auth-left">
            <div class="portal-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                </svg>
                MSU-IIT Guidance and Counseling Portal
            </div>

            <h1>Support starts with a safe and accessible space.</h1>

            <p>
                The Office of Guidance and Counseling Portal provides MSU-IIT students with a more convenient way to access guidance services, manage their portal account, and stay connected with student support resources.
            </p>

            <div class="left-note">
                <strong>Create an account</strong> to begin accessing student support services through an official platform designed to make guidance resources more organized, secure, and accessible.
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-card">
                <h2 class="card-title">Log in</h2>
                <p class="card-subtitle">
                    Don’t have an account?
                    <a href="{{ route('register') }}">Create one here</a>
                </p>

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

                <div class="auth-footer-note">
                    Office of Guidance and Counseling • MSU-IIT
                </div>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>