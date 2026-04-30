@extends('layouts.student')

@section('title', 'Book Appointment - OGC')

@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c;
        --maroon-800: #5c1a1a;
        --maroon-700: #7a2a2a;
        --gold-500: #c9a227;
        --gold-400: #d4af37;
        --bg-warm: #faf8f5;
        --border-soft: #e5e0db;
        --text-primary: #2c2420;
        --text-secondary: #6b5e57;
        --text-muted: #8b7e76;
    }

    .ogc-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .ogc-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2;
    }
    .ogc-glow.one { top: -40px; left: -50px; width: 240px; height: 240px; background: var(--gold-400); }
    .ogc-glow.two { bottom: -50px; right: -70px; width: 280px; height: 280px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .form-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .form-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before, .glass-card::before, .form-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .panel-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-card { min-height: 100px; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.9);
        padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.16em; color: var(--maroon-700);
    }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }

    .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
        min-width: 280px;
    }
    @media (min-width: 1024px) {
        .summary-card {
           width: 500px;
            min-width: 500px;
        }
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .primary-btn, .btn-primary, .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        font-size: 0.8rem; padding: 0.55rem 1rem; gap: 0.4rem;
    }
    .primary-btn, .btn-primary {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover, .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        color: var(--text-primary); background: rgba(255,255,255,0.95);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .input-field, .select-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.95); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .textarea-field { padding: 0.75rem; resize: vertical; min-height: 4rem; }
    .input-field:focus, .select-field:focus, .textarea-field:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }
    .input-field:disabled, .select-field:disabled { background: rgba(245,240,235,0.6); color: var(--text-muted); cursor: not-allowed; }

    .radio-label, .checkbox-label {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.8rem; color: var(--text-primary); cursor: pointer;
    }
    .radio-label input[type="radio"], .checkbox-label input[type="checkbox"] {
        width: 1rem; height: 1rem; accent-color: var(--maroon-700);
    }

    .calendar-card {
        border: 1px solid var(--border-soft); border-radius: 0.75rem;
        background: white; padding: 1rem;
        position: relative;
    }

    .calendar-loading-overlay {
        position: absolute; inset: 0; border-radius: 0.75rem;
        background: rgba(255,255,255,0.85); backdrop-filter: blur(3px);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 0.6rem; z-index: 10; opacity: 0; pointer-events: none;
        transition: opacity 0.2s ease;
    }
    .calendar-loading-overlay.visible {
        opacity: 1; pointer-events: all;
    }
    .calendar-loading-spinner {
        width: 2rem; height: 2rem; border-radius: 50%;
        border: 3px solid rgba(92,26,26,0.15);
        border-top-color: var(--maroon-700);
        animation: spin 0.7s linear infinite;
    }
    .calendar-loading-text {
        font-size: 0.75rem; font-weight: 600; color: var(--maroon-700);
        letter-spacing: 0.02em;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .calendar-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    .calendar-nav-btn {
        width: 2rem; height: 2rem; border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-soft); background: white;
        color: var(--text-secondary); font-weight: 600; cursor: pointer;
        transition: all 0.15s ease;
    }
    .calendar-nav-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); color: var(--maroon-700); }
    .calendar-month { font-size: 0.85rem; font-weight: 700; color: var(--text-primary); }
    .calendar-days {
        display: grid; grid-template-columns: repeat(7, 1fr);
        gap: 0.25rem; margin-bottom: 0.5rem;
    }
    .calendar-day-header {
        text-align: center; font-size: 0.65rem; font-weight: 600;
        color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;
    }
    .calendar-grid {
        display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.35rem;
    }
    .calendar-date-btn {
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 500; border: 1px solid transparent;
        color: var(--text-muted); cursor: not-allowed; transition: all 0.15s ease;
    }
    .calendar-date-btn.available {
        border-color: rgba(212,175,55,0.4); color: var(--maroon-700);
        background: rgba(212,175,55,0.1); cursor: pointer;
    }
    .calendar-date-btn.available:hover {
        background: rgba(212,175,55,0.2); border-color: var(--gold-400);
    }
    .calendar-date-btn.selected {
        background: var(--maroon-700); color: white; border-color: var(--maroon-700);
    }
    .calendar-status {
        margin-top: 0.5rem; font-size: 0.7rem; color: var(--text-muted);
    }
    .calendar-status.success { color: var(--maroon-700); }
    .calendar-status.error { color: #b91c1c; }

    .time-slots-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;
    }
    @media (min-width: 768px) { .time-slots-grid { grid-template-columns: repeat(3, 1fr); } }
    .time-slot-btn {
        padding: 0.6rem 0.75rem; border-radius: 0.5rem;
        border: 2px solid var(--border-soft); background: white;
        font-size: 0.75rem; font-weight: 500; color: var(--text-primary);
        text-align: center; cursor: pointer; transition: all 0.15s ease;
    }
    .time-slot-btn:hover { border-color: var(--maroon-700); background: rgba(254,249,231,0.6); }
    .time-slot-btn.selected {
        border-color: var(--maroon-700); background: rgba(212,175,55,0.15);
        color: var(--maroon-800); font-weight: 600;
    }
    .time-slot-placeholder {
        padding: 1rem; border: 2px dashed var(--border-soft);
        border-radius: 0.5rem; text-align: center;
        font-size: 0.75rem; color: var(--text-muted);
    }

    .modal-overlay {
        position: fixed; inset: 0; background: rgba(44,36,32,0.5);
        display: flex; align-items: center; justify-content: center;
        z-index: 50; padding: 1rem;
    }
    .modal-overlay.hidden { display: none; }
    .modal-card {
        background: white; border-radius: 0.75rem; border: 1px solid var(--border-soft);
        box-shadow: 0 8px 32px rgba(44,36,32,0.12); max-width: 42rem; width: 100%;
        overflow: hidden; display: flex; flex-direction: column; max-height: 70vh;
    }
    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft);
        background: rgba(250,248,245,0.6); flex-shrink: 0;
    }
    .modal-title { font-size: 0.9rem; font-weight: 700; color: var(--text-primary); }
    .modal-close {
        background: none; border: none; color: var(--text-muted);
        font-size: 1.1rem; cursor: pointer; transition: color 0.15s ease;
        width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;
        border-radius: 999px;
    }
    .modal-close:hover { background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .modal-body {
        padding: 1rem 1.25rem; overflow-y: auto; flex: 1;
        font-size: 0.8rem; color: var(--text-primary); line-height: 1.6;
    }
    .modal-body h3 {
        font-size: 0.85rem; font-weight: 700; color: var(--text-primary);
        margin: 1rem 0 0.5rem;
    }
    .modal-body ol { padding-left: 1.25rem; margin: 0.5rem 0; }
    .modal-body li { margin: 0.25rem 0; }
    .modal-footer {
        padding: 0.85rem 1.25rem; border-top: 1px solid var(--border-soft);
        background: rgba(250,248,245,0.6); flex-shrink: 0;
        display: flex; flex-direction: column; gap: 0.75rem;
    }
    .modal-footer-row {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
        gap: 0.75rem;
    }
    .modal-hint {
        font-size: 0.7rem; color: var(--text-muted); text-align: center;
    }
    .modal-hint.success { color: var(--text-muted); }

    .field-help {
        font-size: 0.7rem; color: var(--text-muted); margin-top: 0.35rem;
    }
    .field-help.error { color: #b91c1c; }
    .field-help.success { color: #065f46; }

    .loading-text {
        font-size: 0.75rem; color: var(--maroon-700); font-weight: 500;
    }

    /* Alert notifications */
    .alert-stack {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 80;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        width: min(24rem, calc(100vw - 2rem));
        pointer-events: none;
    }

    .system-alert {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        padding: 0.95rem 1rem 0.95rem 0.95rem;
        border-radius: 0.9rem;
        border: 1px solid var(--border-soft);
        background: rgba(255,255,255,0.97);
        box-shadow: 0 12px 30px rgba(44,36,32,0.14);
        backdrop-filter: blur(10px);
        pointer-events: auto;
        animation: alertSlideIn 0.24s ease;
    }

    .system-alert::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        border-radius: 999px;
    }

    .system-alert.success::before {
        background: linear-gradient(180deg, #15803d, #22c55e);
    }

    .system-alert.error::before {
        background: linear-gradient(180deg, #991b1b, #dc2626);
    }

    .system-alert.warning::before {
        background: linear-gradient(180deg, var(--gold-500), var(--gold-400));
    }

    .system-alert.info::before {
        background: linear-gradient(180deg, var(--maroon-800), var(--maroon-700));
    }

    .system-alert-icon {
        width: 2.2rem;
        height: 2.2rem;
        min-width: 2.2rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 0.05rem;
        font-size: 0.9rem;
    }

    .system-alert.success .system-alert-icon {
        background: rgba(34,197,94,0.12);
        color: #15803d;
    }

    .system-alert.error .system-alert-icon {
        background: rgba(220,38,38,0.12);
        color: #b91c1c;
    }

    .system-alert.warning .system-alert-icon {
        background: rgba(212,175,55,0.16);
        color: #9a3412;
    }

    .system-alert.info .system-alert-icon {
        background: rgba(92,26,26,0.10);
        color: var(--maroon-700);
    }

    .system-alert-content {
        flex: 1;
        min-width: 0;
    }

    .system-alert-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.15rem;
        letter-spacing: 0.02em;
    }

    .system-alert-message {
        font-size: 0.76rem;
        line-height: 1.5;
        color: var(--text-secondary);
    }

    .system-alert-close {
        width: 1.85rem;
        height: 1.85rem;
        min-width: 1.85rem;
        border: none;
        background: transparent;
        color: var(--text-muted);
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .system-alert-close:hover {
        background: rgba(254,249,231,0.9);
        color: var(--maroon-700);
    }

    .system-alert-progress {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 3px;
        background: rgba(44,36,32,0.06);
        overflow: hidden;
    }

    .system-alert-progress-bar {
        width: 100%;
        height: 100%;
        transform-origin: left center;
    }

    .system-alert.success .system-alert-progress-bar {
        background: linear-gradient(90deg, #15803d, #22c55e);
    }

    .system-alert.error .system-alert-progress-bar {
        background: linear-gradient(90deg, #991b1b, #dc2626);
    }

    .system-alert.warning .system-alert-progress-bar {
        background: linear-gradient(90deg, var(--gold-500), var(--gold-400));
    }

    .system-alert.info .system-alert-progress-bar {
        background: linear-gradient(90deg, var(--maroon-800), var(--maroon-700));
    }

    @keyframes alertSlideIn {
        from {
            opacity: 0;
            transform: translateY(-10px) translateX(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0) translateX(0);
        }
    }

    @keyframes alertProgress {
        from {
            transform: scaleX(1);
        }
        to {
            transform: scaleX(0);
        }
    }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field, .textarea-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; padding: 0.7rem; font-size: 0.75rem; }
        .btn-row-mobile { flex-direction: column; gap: 0.75rem !important; }
        .hero-card { padding: 1rem !important; }
        .hero-icon { width: 2.25rem; height: 2.25rem; }
        .calendar-date-btn { width: 1.75rem; height: 1.75rem; font-size: 0.7rem; }
        .time-slots-grid { grid-template-columns: 1fr 1fr; }
        .modal-footer-row { flex-direction: column; align-items: stretch; }
        .checkbox-label { font-size: 0.75rem; }

        .alert-stack {
            top: 0.75rem;
            left: 0.75rem;
            right: 0.75rem;
            width: auto;
        }

        .system-alert {
            padding: 0.85rem 0.9rem 0.85rem 0.9rem;
        }
    }

    .back-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--maroon-700); font-size: 0.75rem; font-weight: 600;
        transition: all 0.18s ease;
    }
    .back-link:hover { color: var(--maroon-900); transform: translateX(-2px); }

    /* Form sections */
    .form-section { padding:1.5rem 1.25rem; border-bottom:1px solid var(--border-soft); }
    .form-section:last-of-type { border-bottom:none; }
    .form-section-header { display:flex; align-items:flex-start; gap:0.75rem; margin-bottom:1.25rem; }
    .form-step-num {
        width:2rem; height:2rem; border-radius:50%; flex-shrink:0;
        background:linear-gradient(135deg, var(--maroon-800), var(--maroon-700));
        color:white; font-weight:700; font-size:0.85rem;
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 2px 6px rgba(92,26,26,0.2);
    }
    .form-section-title { font-size:0.9rem; font-weight:700; color:var(--text-primary); line-height:1.2; }
    .form-section-sub { font-size:0.72rem; color:var(--text-muted); margin-top:0.15rem; }
    .form-section-body { padding-left:2.75rem; }
    @media (max-width:640px) { .form-section-body { padding-left:0; } }

    /* Field wrap */
    .field-wrap { margin-bottom:1.25rem; }
    .field-label { display:flex; align-items:center; gap:0.4rem; font-size:0.72rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.06em; }
    .field-icon { font-size:0.7rem; color:var(--maroon-700); }
    .field-help { font-size:0.7rem; color:var(--text-muted); margin-top:0.35rem; }
    .success-help { color: var(--maroon-700); font-weight: 700; font-size: 0.95rem; }

    /* Type radio cards */
    .type-radio-card { cursor:pointer; }
    .type-radio-inner {
        display:flex; align-items:center; gap:0.5rem;
        padding:0.6rem 1rem; border-radius:0.6rem;
        border:2px solid var(--border-soft); background:white;
        font-size:0.78rem; font-weight:600; color:var(--text-primary);
        transition:all 0.15s ease;
    }
    .type-radio-card:hover .type-radio-inner { border-color:var(--maroon-700); background:rgba(254,249,231,0.5); }
    .type-radio-card input:checked + .type-radio-inner {
        border-color:var(--maroon-700); background:rgba(212,175,55,0.12);
        color:var(--maroon-800); box-shadow:0 2px 6px rgba(92,26,26,0.1);
    }
    .sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border-width:0; }

    /* Category tabs */
    .category-tab {
        display:inline-flex; align-items:center; gap:0.4rem;
        padding:0.55rem 1rem; border-radius:0.6rem; border:2px solid;
        font-size:0.75rem; font-weight:600; cursor:pointer; transition:all 0.15s ease;
    }
    .category-tab:hover { background:rgba(122,42,42,0.05); }

    /* Concern panels */
    .concern-panel {
        border:1px solid var(--border-soft); border-radius:0.75rem;
        background:rgba(250,248,245,0.6); padding:1rem;
    }
    .concern-panel-title {
        font-size:0.8rem; font-weight:700; color:var(--maroon-700);
        text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.75rem;
        display:flex; align-items:center; gap:0.4rem;
    }
    .concern-sub-title {
        font-size:0.75rem; font-weight:600; color:var(--maroon-700);
        margin:1rem 0 0.5rem; padding-top:0.75rem; border-top:1px dashed var(--border-soft);
    }
    .concern-grid { display:grid; grid-template-columns:1fr; gap:0.5rem; }
    @media (min-width:640px) { .concern-grid { grid-template-columns:1fr 1fr; } }
    .concern-item {
        display:flex; align-items:flex-start; gap:0.4rem;
        font-size:0.75rem; color:var(--text-primary); cursor:pointer;
        padding:0.4rem 0.5rem; border-radius:0.4rem; transition:background 0.15s ease;
    }
    .concern-item:hover { background:rgba(212,175,55,0.08); }
    .concern-checkbox { width:1rem; height:1rem; margin-top:0.1rem; flex-shrink:0; accent-color:var(--maroon-700); cursor:pointer; }
    .other-text-input {
        flex:1; border:none; border-bottom:1px solid var(--border-soft);
        background:transparent; font-size:0.72rem; padding:0.1rem 0.3rem;
        outline:none; color:var(--text-primary);
    }
    .other-text-input:focus { border-bottom-color:var(--maroon-700); }

    /* Calendar legend */
    .calendar-legend {
        display:flex; flex-wrap:wrap; gap:0.75rem; justify-content:center;
        padding:0.75rem 0.5rem 0; margin-top:0.75rem; border-top:1px solid var(--border-soft);
    }
    .legend-item { display:flex; align-items:center; gap:0.35rem; font-size:0.7rem; color:var(--text-secondary); }
    .legend-dot {
        width:0.75rem; height:0.75rem; border-radius:50%; border:1px solid;
    }
    .legend-dot.available { background:rgba(212,175,55,0.15); border-color:rgba(212,175,55,0.5); }
    .legend-dot.selected { background:var(--maroon-700); border-color:var(--maroon-700); }
    .legend-dot.unavailable { background:transparent; border-color:var(--border-soft); }

    /* Form submit bar */
    .form-submit-bar {
        display:flex; align-items:center; justify-content:space-between;
        padding:1.25rem; background:rgba(250,248,245,0.6); border-top:1px solid var(--border-soft);
    }
    @media (max-width:640px) {
        .form-submit-bar { flex-direction:column; gap:0.75rem; }
        .form-submit-bar .primary-btn, .form-submit-bar .secondary-btn { width:100%; }
    }
</style>

<div class="min-h-screen ogc-shell">
    <div id="alertStack" class="alert-stack"></div>

    <div class="ogc-glow one"></div>
    <div class="ogc-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card h-full">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-plus text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('mhc') }}" class="back-link mb-2">
                                <i class="fas fa-arrow-left text-[9px]"></i> Back to Mental Health Corner
                            </a>
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                New Appointment
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Book an Appointment</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Schedule a counseling session with our guidance team.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card h-full">
                    <div class="relative h-full flex items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-check text-sm"></i>
                            </div>
                            <div>
                                <p class="summary-label">Counseling Session</p>
                                <p class="summary-value">Book a Session</p>
                            </div>
                        </div>
                        <a href="{{ route('appointments.index') }}" class="btn-primary">
                            <i class="fas fa-list"></i>
                            <span>My Appointments</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST">
                @csrf

                <!-- Step 1: Counselor -->
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-step-num">1</div>
                        <div>
                            <div class="form-section-title">Choose Your Counselor</div>
                            <div class="form-section-sub">Select who you'd like to meet with</div>
                        </div>
                    </div>
                    <div class="form-section-body">
                        <div class="mb-4" id="counselorTypeWrapper">
                            <div class="flex flex-wrap gap-3">
                                <label class="type-radio-card">
                                    <input type="radio" name="counselor_type" value="college" checked class="counselor-type-radio sr-only">
                                    <div class="type-radio-inner"><i class="fas fa-building-columns"></i><span>{{ ($allowAllCounselors ?? false) ? 'All Colleges' : 'My College' }}</span></div>
                                </label>
                                @if($hasReferredCounselors ?? false)
                                <label class="type-radio-card" id="referredCounselorOption">
                                    <input type="radio" name="counselor_type" value="referred" class="counselor-type-radio sr-only">
                                    <div class="type-radio-inner"><i class="fas fa-user-check"></i><span>Previously Referred</span></div>
                                </label>
                                @endif
                            </div>
                        </div>
                        <div class="field-wrap">
                            <label class="field-label"><i class="fas fa-user-tie field-icon"></i> Counselor</label>
                            <select name="counselor_id" id="counselorSelect" class="select-field" required>
                                <option value="">Choose a counselor</option>
                            </select>
                            <input type="hidden" name="counselor_id" id="counselorAutoAssignedInput">
                            <p id="counselorAutoAssigned" class="hidden field-help success-help"></p>
                            <div id="counselorLoading" class="hidden loading-text mt-2"><i class="fas fa-spinner fa-spin"></i> Loading counselors...</div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Appointment Details -->
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-step-num">2</div>
                        <div>
                            <div class="form-section-title">Appointment Details</div>
                            <div class="form-section-sub">Tell us about the type of session you need</div>
                        </div>
                    </div>
                    <div class="form-section-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="field-wrap">
                                <label class="field-label"><i class="fas fa-tag field-icon"></i> Type of Booking</label>
                                <select name="booking_type" id="bookingType" class="select-field" required>
                                    <option value="">Choose a type</option>
                                    <option value="Initial Interview" id="bookingTypeInitial" {{ ($hasInitialInterviewAppointment ?? false) ? 'disabled hidden' : '' }}>Initial Interview</option>
                                    <option value="Counseling">Counseling</option>
                                    <option value="Consultation">Consultation</option>
                                </select>
                                <p class="field-help" id="bookingTypeHelp">Select the reason for your appointment.</p>
                            </div>
                            <div class="field-wrap">
                                <label class="field-label"><i class="fas fa-door-open field-icon"></i> How did you come in?</label>
                                <select name="booking_category" id="bookingCategory" class="select-field" required>
                                    <option value="walk-in" {{ old('booking_category') === 'walk-in' ? 'selected' : '' }}>Walk-in</option>
                                    <option value="referred" {{ old('booking_category') === 'referred' ? 'selected' : '' }}>Referred</option>
                                    <option value="called-in" {{ old('booking_category') === 'called-in' ? 'selected' : '' }}>Called-in</option>
                                </select>
                                <p class="field-help">How was this appointment initiated?</p>
                            </div>
                        </div>
                        <div class="field-wrap hidden" id="referredByWrap">
                            <label class="field-label"><i class="fas fa-share-nodes field-icon"></i> Referred by</label>
                            <input type="text" name="referred_by" id="referredByInput" class="input-field" maxlength="255" placeholder="e.g. Teacher, Professor, Parent, Friend" value="{{ old('referred_by') }}">
                            <p class="field-help">Who referred you to counseling?</p>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Concern -->
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-step-num">3</div>
                        <div>
                            <div class="form-section-title">Your Concern</div>
                            <div class="form-section-sub">Help us understand what you'd like to talk about</div>
                        </div>
                    </div>
                    <div class="form-section-body">
                        <input type="hidden" name="concern" id="concernHidden">
                        <input type="hidden" name="concern_category" id="concernCategory">

                        <div class="field-wrap mb-4">
                            <label class="field-label"><i class="fas fa-layer-group field-icon"></i> Concern Category</label>
                            <p class="field-help mb-3">Pick the category that best fits, then check all items that apply.</p>
                            <div class="flex flex-wrap gap-2 mb-4" id="categoryTabs">
                                @foreach(['ACADEMIC' => ['Academic / Educational','fas fa-graduation-cap'], 'PERSONAL' => ['Personal / Social','fas fa-heart'], 'CAREER' => ['Career / Vocational','fas fa-briefcase']] as $key => [$label, $icon])
                                <button type="button" data-cat="{{ $key }}" onclick="selectCategory('{{ $key }}')" class="category-tab" style="border-color:#d4b896;color:#7a5c3a;background:#fdf8f3;">
                                    <i class="{{ $icon }}"></i> {{ $label }}
                                </button>
                                @endforeach
                            </div>

                            <div id="cat-ACADEMIC" class="concern-panel hidden">
                                <p class="concern-panel-title"><i class="fas fa-graduation-cap"></i> Academic / Educational</p>
                                <div class="concern-grid">
                                    @foreach(['Has difficulty in subject/s','Poor quality of work','Inconsistent attendance','Dropped out of subjects','Absenteeism','Struggling for achievement','Cheating','Difficulty completing work','Inconsistency with effort','Poorly completed assignment(s)','Poorly completed project(s)'] as $item)
                                    <label class="concern-item"><input type="checkbox" class="concern-checkbox" data-cat="ACADEMIC" value="{{ $item }}"><span>{{ $item }}</span></label>
                                    @endforeach
                                    <label class="concern-item sm:col-span-2"><input type="checkbox" class="concern-checkbox" data-cat="ACADEMIC" data-other="true" id="academicOtherChk" value="Others"><span>Others: <input type="text" id="academicOtherText" class="other-text-input" placeholder="please specify..." maxlength="200" disabled></span></label>
                                </div>
                            </div>

                            <div id="cat-PERSONAL" class="concern-panel hidden">
                                <p class="concern-panel-title"><i class="fas fa-heart"></i> Personal / Social</p>
                                <div class="concern-grid">
                                    @foreach(['Depression / depressive thoughts','Poor hygiene/self-care','Sleeping in class','Consistently tired/sleepy','Excessive physical complaints','Always sick','Family problems','New born in family','Perfectionism','Appears apathetic','Appears sad/depressed mood','Uses obscene languages','Argues frequently','Short attention span','Has frequent mood swings','Overreacts to criticism','Has difficulty accepting mistakes','Lacks confidence','Makes excuses/blames others','Hurts self','Self-destructive acting out','Stealing','Frequently off-task','Very active or impulsive','Difficulty concentrating','Disturbs others','Defiant of rules','Destruction of property','Substance abuse','Difficulty in relating with others','Aggression resulting from conflict/s','Bullying / Cyberbullying'] as $item)
                                    <label class="concern-item"><input type="checkbox" class="concern-checkbox" data-cat="PERSONAL" value="{{ $item }}"><span>{{ $item }}</span></label>
                                    @endforeach
                                </div>
                                <p class="concern-sub-title">Recently experienced crisis:</p>
                                <div class="concern-grid">
                                    @foreach(['Death of loved one','Tragedy','Pregnancy','Harassment issues','Recent parental separation','Recently separated from parents/home','Recent change of address','Parent(s) re-marry'] as $item)
                                    <label class="concern-item"><input type="checkbox" class="concern-checkbox" data-cat="PERSONAL" value="{{ $item }}"><span>{{ $item }}</span></label>
                                    @endforeach
                                    <label class="concern-item sm:col-span-2"><input type="checkbox" class="concern-checkbox" data-cat="PERSONAL" data-other="true" id="personalOtherChk" value="Others"><span>Others: <input type="text" id="personalOtherText" class="other-text-input" placeholder="please specify..." maxlength="200" disabled></span></label>
                                </div>
                            </div>

                            <div id="cat-CAREER" class="concern-panel hidden">
                                <p class="concern-panel-title"><i class="fas fa-briefcase"></i> Career / Vocational</p>
                                <div class="concern-grid">
                                    @foreach(['Barred student','Shifting / plans to shift to another course','Undecided about degree/course/career to pursue','Difficulty in making career choice/s','Does not like course presently enrolled in'] as $item)
                                    <label class="concern-item"><input type="checkbox" class="concern-checkbox" data-cat="CAREER" value="{{ $item }}"><span>{{ $item }}</span></label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="field-wrap">
                            <label class="field-label"><i class="fas fa-pen-to-square field-icon"></i> Brief narrative of your concern</label>
                            <textarea name="" id="concernTextarea" rows="3" class="textarea-field" placeholder="In your own words, briefly describe what's on your mind..."></textarea>
                            <p class="field-help">This helps your counselor prepare for your session.</p>
                        </div>

                        <div class="field-wrap mt-4">
                            <label class="field-label"><i class="fas fa-face-smile field-icon"></i> How are you feeling today?</label>
                            <select name="mood_rating" id="moodRating" class="select-field" required>
                                <option value="">Choose your current mood</option>
                                @foreach([['1','Very Overwhelmed'],['2','Struggling'],['3','Not Okay'],['4','A Little Down'],['5','Neutral'],['6','A Bit Better'],['7','Doing Fine'],['8','Good'],['9','Very Good'],['10','Great']] as [$v,$l])
                                <option value="{{ $v }} - {{ $l }}">{{ $v }} — {{ $l }}</option>
                                @endforeach
                            </select>
                            <p class="field-help">Rate how you're feeling right now on a scale of 1–10.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Schedule -->
                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-step-num">4</div>
                        <div>
                            <div class="form-section-title">Pick a Schedule</div>
                            <div class="form-section-sub">Choose a date and time that works for you</div>
                        </div>
                    </div>
                    <div class="form-section-body">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <div>
                                <label class="field-label mb-2"><i class="fas fa-calendar field-icon"></i> Select Date</label>
                                <div id="appointmentCalendar" class="calendar-card">
                                    <div id="calendarLoadingOverlay" class="calendar-loading-overlay">
                                        <div class="calendar-loading-spinner"></div>
                                        <span class="calendar-loading-text">Checking available dates…</span>
                                    </div>
                                    <div class="calendar-header">
                                        <button type="button" id="calendarPrev" class="calendar-nav-btn" aria-label="Previous month">‹</button>
                                        <h3 id="calendarMonthLabel" class="calendar-month"></h3>
                                        <button type="button" id="calendarNext" class="calendar-nav-btn" aria-label="Next month">›</button>
                                    </div>
                                    <div class="calendar-days">
                                        <span class="calendar-day-header">Sun</span><span class="calendar-day-header">Mon</span><span class="calendar-day-header">Tue</span><span class="calendar-day-header">Wed</span><span class="calendar-day-header">Thu</span><span class="calendar-day-header">Fri</span><span class="calendar-day-header">Sat</span>
                                    </div>
                                    <div id="calendarGrid" class="calendar-grid"></div>
                                    <div class="calendar-legend">
                                        <span class="legend-item"><span class="legend-dot available"></span>Available</span>
                                        <span class="legend-item"><span class="legend-dot selected"></span>Selected</span>
                                        <span class="legend-item"><span class="legend-dot unavailable"></span>Unavailable</span>
                                    </div>
                                    <p id="calendarStatus" class="calendar-status">Select a counselor to load available dates.</p>
                                </div>
                                <input type="hidden" name="appointment_date" id="dateSelect" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                            <div>
                                <label class="field-label mb-2"><i class="fas fa-clock field-icon"></i> Available Time Slots</label>
                                <div id="timeSlots" class="time-slots-grid">
                                    <div class="time-slot-placeholder">Select a counselor and date to see available time slots</div>
                                </div>
                                <input type="hidden" name="start_time" id="selectedTime" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-submit-bar">
                    <a href="{{ route('appointments.index') }}" class="secondary-btn px-5 py-2.5 text-xs sm:text-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Cancel
                    </a>
                    <button type="button" id="openConsentModal" class="primary-btn px-6 py-2.5 text-xs sm:text-sm">
                        <i class="fas fa-calendar-check mr-1.5"></i> Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Informed Consent Modal -->
<div id="consentModal" class="modal-overlay hidden">
    <div class="modal-card">
        <div class="modal-header">
            <h2 class="modal-title">INFORMED CONSENT FOR COUNSELING</h2>
            <button type="button" class="modal-close" data-consent-close aria-label="Close">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        
        <div id="consentContent" class="modal-body">
            <p>
                COUNSELING is a confidential process designed to help you address your concerns, come to a better understanding
                of yourself, and learn effective personal and interpersonal coping strategies. It involves a relationship between
                you and a trained Counselor who has the desire and willingness to help you accomplish your individual goals.
            </p>
            <p>
                Counseling involves sharing sensitive, personal, and private information that may at times be distressing. During
                the course of counseling, there may be periods of increased anxiety or confusion. The outcome of counseling is
                often positive; however, the level of satisfaction for any individual is not predictable. Your counselor is
                available to support you throughout the counseling process.
            </p>
            <h3>CONFIDENTIALITY</h3>
            <p>
                All interactions with the counseling services of the Office of Guidance and Counseling (OGC), including scheduling
                of or attendance at appointments, content of your sessions, progress in counseling, and your records are confidential.
                No record of counseling is contained in any academic, educational or job placement file. You may request in writing
                that the counselor releases specific information about your counseling to persons you designate.
            </p>
            <h3>EXCEPTIONS TO CONFIDENTIALITY</h3>
            <p>Under the following circumstances can only there be a breach in confidentiality:</p>
            <ol class="list-decimal list-inside space-y-2">
                <li>
                    The counseling staff works as a team. Your counselor may consult with other counselors to provide the best possible
                    care. These case consultation/case conferences are for professional training purposes; and do not usually include
                    any identifiers of the client.
                </li>
                <li>
                    If there is evidence of clear and imminent danger or harm to yourself and/or others, a Counselor is legally required
                    to report this information to the authorities responsible for ensuring safety.
                </li>
                <li>
                    The staff of the Office of Guidance and Counseling who learn of, or strongly suspect physical or sexual abuse or neglect
                    of a person under 18 years of age, must report this information to local authorities for child protection services (RA 7610).
                </li>
                <li>
                    A court order, issued by a competent judge, may require the counselor to release information contained in records and/or
                    require a counselor to testify in court hearing.
                </li>
            </ol>
            <h3>CLIENT'S ROLES</h3>
            <ol class="list-decimal list-inside space-y-2">
                <li>
                    The client further agrees to willingly cooperate in attending scheduled/booked counseling sessions, follow-up and/or
                    tutorial sessions, and accomplish assigned homework/s as agreed by both parties.
                </li>
                <li>
                    The client understands that as he/she seeks professional help, the counseling relationship established shall come to a
                    termination or closure after careful evaluation and discretion of the attending counselor. There is NO FEE for counseling
                    services availed by students within the Institute.
                </li>
            </ol>
            <h3>REFERRAL TO EXPERTS</h3>
            <p>
                If you are referred off campus to health, mental health or substance abuse professionals, you are responsible for their charges,
                except when referred to clinicians who have memorandum of understanding/MOU with the Institute.
            </p>
        </div>
        
        <div class="modal-footer">
            <div class="modal-footer-row">
                <label class="checkbox-label">
                    <input type="checkbox" id="consentAcknowledged" disabled>
                    <span>I have read and understood the Informed Consent for Counseling.</span>
                </label>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            class="secondary-btn px-4 py-2 text-xs"
                            data-consent-close>
                        Cancel
                    </button>
                    <button type="button"
                            id="confirmBooking"
                            class="primary-btn px-5 py-2 text-xs"
                            disabled>
                        Confirm Booking
                    </button>
                </div>
            </div>
            <p id="consentHint" class="modal-hint">
                ↓ Scroll to bottom to enable confirmation ↓
            </p>
        </div>
    </div>
</div>

<script>
// Global concern category function (must be global for onclick attributes)
function selectCategory(cat) {
    document.getElementById('concernCategory').value = cat;
    document.querySelectorAll('.concern-panel').forEach(p => p.classList.add('hidden'));
    document.getElementById('cat-' + cat)?.classList.remove('hidden');
    document.querySelectorAll('.category-tab').forEach(btn => {
        const active = btn.dataset.cat === cat;
        btn.style.background = active ? '#7a2a2a' : '#fdf8f3';
        btn.style.color = active ? '#fff' : '#7a5c3a';
        btn.style.borderColor = active ? '#7a2a2a' : '#d4b896';
    });
    document.querySelectorAll(`.concern-checkbox:not([data-cat="${cat}"])`).forEach(cb => {
        cb.checked = false;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const counselorTypeRadios = document.querySelectorAll('.counselor-type-radio');
    const counselorSelect = document.getElementById('counselorSelect');
    const counselorTypeWrapper = document.getElementById('counselorTypeWrapper');
    const counselorAutoAssigned = document.getElementById('counselorAutoAssigned');
    const counselorAutoAssignedInput = document.getElementById('counselorAutoAssignedInput');
    const counselorLoading = document.getElementById('counselorLoading');
    const dateSelect = document.getElementById('dateSelect');
    const calendarGrid = document.getElementById('calendarGrid');
    const calendarMonthLabel = document.getElementById('calendarMonthLabel');
    const calendarPrev = document.getElementById('calendarPrev');
    const calendarNext = document.getElementById('calendarNext');
    const calendarStatus = document.getElementById('calendarStatus');
    const timeSlots = document.getElementById('timeSlots');
    const selectedTime = document.getElementById('selectedTime');
    const bookingTypeSelect = document.getElementById('bookingType');
    const bookingTypeInitial = document.getElementById('bookingTypeInitial');
    const bookingTypeHelp = document.getElementById('bookingTypeHelp');
    const referredCounselorOption = document.getElementById('referredCounselorOption');
    const concernCategorySelect = document.getElementById('concernCategory'); // hidden input
    const moodRatingSelect = document.getElementById('moodRating');

    const openConsentModal = document.getElementById('openConsentModal');
    const consentModal = document.getElementById('consentModal');
    const consentContent = document.getElementById('consentContent');
    const consentCheckbox = document.getElementById('consentAcknowledged');
    const confirmBooking = document.getElementById('confirmBooking');
    const consentHint = document.getElementById('consentHint');
    const consentCloseButtons = document.querySelectorAll('[data-consent-close]');
    const appointmentForm = document.getElementById('appointmentForm');
    const alertStack = document.getElementById('alertStack');

    const studentYearLevel = {!! json_encode(optional($student)->year_level) !!};
    const studentInitialInterviewCompleted = {!! json_encode(optional($student)->initial_interview_completed) !!};
    const hasInitialInterviewAppointment = {!! json_encode($hasInitialInterviewAppointment ?? false) !!};
    const allowAllCounselors = {!! json_encode($allowAllCounselors ?? false) !!};
    const referredCounselorsData = {!! json_encode(($referredCounselors ?? collect())->map(fn($c) => [
        'id' => $c->id,
        'name' => $c->user->first_name . ' ' . $c->user->last_name,
        'position' => $c->position,
        'college' => $c->college->name ?? 'N/A',
        'college_id' => $c->college_id,
        'is_referred' => true,
        'display_text' => $c->user->first_name . ' ' . $c->user->last_name . ' - ' . $c->position . ' (' . ($c->college->name ?? 'N/A') . ') - Previously Referred',
    ])->values()) !!};

    let currentSelectedSlot = null;
    const minDate = new Date();
    minDate.setHours(0, 0, 0, 0);
    minDate.setDate(minDate.getDate() + 1);
    let currentMonth = new Date(minDate.getFullYear(), minDate.getMonth(), 1);
    let selectedDate = null;
    let availabilityByDate = new Map();
    let availabilityRequestId = 0;

    let collegeCounselors = {!! json_encode($counselors->map(function($c) {
        return [
            'id' => $c->id,
            'name' => $c->user->first_name . ' ' . $c->user->last_name,
            'position' => $c->position,
            'college' => $c->college->name ?? 'N/A',
            'display_text' => $c->user->first_name . ' ' . $c->user->last_name . ' - ' . $c->position . ' (' . ($c->college->name ?? 'N/A') . ')'
        ];
    })) !!};

    // Toggle "Others" text input when its checkbox is checked
    document.querySelectorAll('.concern-checkbox[data-other]').forEach(cb => {
        cb.addEventListener('change', function() {
            const cat = this.dataset.cat.toLowerCase();
            const textInput = document.getElementById(cat + 'OtherText');
            if (textInput) {
                textInput.disabled = !this.checked;
                if (this.checked) textInput.focus();
                else textInput.value = '';
            }
        });
    });

    // Booking category: show "Source of Referral" only when "Referred" is selected
    const bookingCategorySelect = document.getElementById('bookingCategory');
    const referredByWrap = document.getElementById('referredByWrap');
    function toggleReferredBy() {
        const isReferred = bookingCategorySelect?.value === 'referred';
        referredByWrap?.classList.toggle('hidden', !isReferred);
        if (!isReferred && document.getElementById('referredByInput')) {
            document.getElementById('referredByInput').value = '';
        }
    }
    bookingCategorySelect?.addEventListener('change', toggleReferredBy);
    // Run on load to handle old() repopulation
    toggleReferredBy();

    function showSystemAlert(message, type = 'warning', title = '') {
        if (!alertStack) return;

        const config = {
            success: { icon: 'fa-circle-check', title: title || 'Success' },
            error: { icon: 'fa-circle-xmark', title: title || 'Something went wrong' },
            warning: { icon: 'fa-triangle-exclamation', title: title || 'Required information' },
            info: { icon: 'fa-circle-info', title: title || 'Notice' }
        };

        const selected = config[type] || config.warning;
        const duration = type === 'error' ? 5000 : 4200;

        const alertEl = document.createElement('div');
        alertEl.className = `system-alert ${type}`;
        alertEl.innerHTML = `
            <div class="system-alert-icon">
                <i class="fas ${selected.icon}"></i>
            </div>
            <div class="system-alert-content">
                <div class="system-alert-title">${selected.title}</div>
                <div class="system-alert-message">${message}</div>
            </div>
            <button type="button" class="system-alert-close" aria-label="Dismiss notification">
                <i class="fas fa-xmark"></i>
            </button>
            <div class="system-alert-progress">
                <div class="system-alert-progress-bar"></div>
            </div>
        `;

        const closeBtn = alertEl.querySelector('.system-alert-close');
        const progressBar = alertEl.querySelector('.system-alert-progress-bar');
        if (progressBar) {
            progressBar.style.animation = `alertProgress ${duration}ms linear forwards`;
        }

        const removeAlert = () => {
            if (!alertEl.parentNode) return;
            alertEl.style.opacity = '0';
            alertEl.style.transform = 'translateY(-6px)';
            alertEl.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            setTimeout(() => {
                if (alertEl.parentNode) {
                    alertEl.remove();
                }
            }, 200);
        };

        closeBtn.addEventListener('click', removeAlert);
        alertStack.appendChild(alertEl);
        setTimeout(removeAlert, duration);
    }

    function validateForm() {
        const counselorId = getActiveCounselorId();
        const bookingType = bookingTypeSelect.value;
        const concernCategory = document.getElementById('concernCategory').value;
        const moodRating = moodRatingSelect.value;
        const date = dateSelect.value;
        const time = selectedTime.value;

        if (!counselorId) {
            showSystemAlert('Please select a counselor before proceeding.', 'warning', 'Counselor required');
            return false;
        }
        if (!bookingType) {
            showSystemAlert('Please choose a booking type.', 'warning', 'Booking type required');
            return false;
        }
        if (!concernCategory) {
            showSystemAlert('Please select a concern category and check at least one item.', 'warning', 'Concern category required');
            return false;
        }
        // Ensure at least one checkbox is checked in the active category
        const checkedItems = document.querySelectorAll(`.concern-checkbox[data-cat="${concernCategory}"]:checked`);
        if (checkedItems.length === 0) {
            showSystemAlert('Please check at least one item in the selected category.', 'warning', 'Concern item required');
            return false;
        }
        const narrative = document.getElementById('concernTextarea')?.value.trim();
        if (!narrative) {
            showSystemAlert('Please share a brief narrative of your concern.', 'warning', 'Narrative required');
            return false;
        }
        if (!moodRating) {
            showSystemAlert('Please select how you are feeling today.', 'warning', 'Mood rating required');
            return false;
        }
        if (!date) {
            showSystemAlert('Please select an appointment date.', 'warning', 'Date required');
            return false;
        }
        if (!time) {
            showSystemAlert('Please select an available time slot.', 'warning', 'Time slot required');
            return false;
        }
        return true;
    }

    function openModal() {
        if (!validateForm()) {
            return;
        }
        
        consentModal?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        if (consentContent) {
            consentContent.scrollTop = 0;
        }
        
        consentCheckbox.checked = false;
        consentCheckbox.disabled = true;
        confirmBooking.disabled = true;
        
        consentHint.textContent = '↓ Scroll to bottom to enable confirmation ↓';
        consentHint.classList.remove('success', 'error');
    }

    function closeModal() {
        consentModal?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    consentContent?.addEventListener('scroll', function() {
        const scrollThreshold = this.scrollHeight - this.clientHeight - 10;
        const isAtBottom = this.scrollTop >= scrollThreshold;
        
        if (isAtBottom) {
            consentCheckbox.disabled = false;
            consentHint.textContent = 'You can now acknowledge and confirm your booking.';
            consentHint.classList.add('success');
        }
    });

    consentCheckbox?.addEventListener('change', function() {
        confirmBooking.disabled = !this.checked;
    });

    confirmBooking?.addEventListener('click', function() {
        if (consentCheckbox.checked) {
            const consentInput = document.createElement('input');
            consentInput.type = 'hidden';
            consentInput.name = 'consent_acknowledged';
            consentInput.value = '1';
            appointmentForm.appendChild(consentInput);

            // Build concern value from checked items + optional extra detail
            const category = document.getElementById('concernCategory').value;
            const categoryLabels = { ACADEMIC: 'Academic / Educational', PERSONAL: 'Personal / Social', CAREER: 'Career / Vocational' };
            const checkedBoxes = document.querySelectorAll(`.concern-checkbox[data-cat="${category}"]:checked`);
            let items = [];
            checkedBoxes.forEach(cb => {
                if (cb.dataset.other) {
                    const otherText = document.getElementById(category.toLowerCase() + 'OtherText')?.value.trim();
                    if (otherText) items.push('Others: ' + otherText);
                    else items.push('Others');
                } else {
                    items.push(cb.value);
                }
            });
            const extraDetail = document.getElementById('concernTextarea')?.value.trim();
            let concernValue = '[' + (categoryLabels[category] || category) + '] ' + items.join('; ');
            if (extraDetail) concernValue += '\n' + extraDetail;
            document.getElementById('concernHidden').value = concernValue;

            appointmentForm.submit();
        }
    });

    openConsentModal?.addEventListener('click', openModal);

    consentCloseButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !consentModal?.classList.contains('hidden')) {
            closeModal();
        }
    });

    consentModal?.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeModal();
        }
    });

    function formatDateValue(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function formatMonthLabel(date) {
        return date.toLocaleString('en-US', { month: 'long', year: 'numeric' });
    }

    function isSameDay(a, b) {
        return a && b &&
            a.getFullYear() === b.getFullYear() &&
            a.getMonth() === b.getMonth() &&
            a.getDate() === b.getDate();
    }

    function getActiveCounselorId() {
        return counselorSelect.value || counselorAutoAssignedInput.value;
    }

    function setCalendarStatus(message, tone = 'muted') {
        if (!calendarStatus) return;
        calendarStatus.textContent = message;
        calendarStatus.classList.remove('success', 'error');
        if (tone === 'success') {
            calendarStatus.classList.add('success');
        } else if (tone === 'error') {
            calendarStatus.classList.add('error');
        }
    }

    function renderCalendar() {
        if (!calendarGrid || !calendarMonthLabel) return;

        calendarMonthLabel.textContent = formatMonthLabel(currentMonth);
        calendarGrid.innerHTML = '';

        const counselorId = getActiveCounselorId();
        const firstDayOfMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);
        const startDay = firstDayOfMonth.getDay();
        const daysInMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0).getDate();

        for (let i = 0; i < startDay; i++) {
            const spacer = document.createElement('div');
            calendarGrid.appendChild(spacer);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), day);
            const dateValue = formatDateValue(date);
            const isPast = date < minDate;
            const availabilityKnown = availabilityByDate.has(dateValue);
            const isAvailable = availabilityByDate.get(dateValue) === true;
            const isDisabled = !counselorId || isPast || !availabilityKnown || !isAvailable;

            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = day;
            button.disabled = isDisabled;
            button.className = 'calendar-date-btn';

            if (!isDisabled) {
                button.classList.add('available');
            }

            if (selectedDate && isSameDay(selectedDate, date)) {
                button.classList.add('selected');
            }

            button.addEventListener('click', () => {
                if (button.disabled) return;
                selectedDate = date;
                dateSelect.value = formatDateValue(date);
                selectedTime.value = '';
                currentSelectedSlot = null;
                setCalendarStatus('', 'success');
                renderCalendar();
                loadAvailableSlots();
            });

            calendarGrid.appendChild(button);
        }
    }

    async function loadMonthAvailability() {
        const counselorId = getActiveCounselorId();
        availabilityByDate = new Map();
        renderCalendar();

        const overlay = document.getElementById('calendarLoadingOverlay');

        if (!counselorId) {
            setCalendarStatus('Select a counselor to load available dates.');
            overlay?.classList.remove('visible');
            return;
        }

        const requestId = ++availabilityRequestId;
        setCalendarStatus('');
        overlay?.classList.add('visible');

        const monthValue = `${currentMonth.getFullYear()}-${String(currentMonth.getMonth() + 1).padStart(2, '0')}`;

        try {
            const response = await fetch(`/appointments/available-dates?counselor_id=${counselorId}&month=${monthValue}`);
            const data = await response.json();
            if (requestId !== availabilityRequestId) return;
            const availability = data.availability || {};
            Object.keys(availability).forEach(dateValue => {
                availabilityByDate.set(dateValue, availability[dateValue] === true);
            });
        } catch (error) {
            if (requestId !== availabilityRequestId) return;
        }

        overlay?.classList.remove('visible');

        if (requestId !== availabilityRequestId) return;

        const hasAnyAvailability = Array.from(availabilityByDate.values()).some(value => value);
        if (!hasAnyAvailability) {
            setCalendarStatus('No available dates for this counselor in the selected month.', 'error');
        } else {
            setCalendarStatus('Available dates are highlighted. Select a date to continue.');
        }

        if (selectedDate && (!availabilityByDate.get(formatDateValue(selectedDate)))) {
            selectedDate = null;
            dateSelect.value = '';
            selectedTime.value = '';
        }

        renderCalendar();
    }

    function loadCounselors(type) {
        counselorSelect.innerHTML = '<option value="">Choose a counselor</option>';
        counselorLoading.classList.remove('hidden');

        if (type === 'college') {
            populateCounselorSelect(collegeCounselors);
        } else {
            // Use pre-loaded server-side data — no AJAX needed
            populateCounselorSelect(referredCounselorsData);
        }
        counselorLoading.classList.add('hidden');
    }

    function populateCounselorSelect(counselors) {
        counselorSelect.innerHTML = '<option value="">Choose a counselor</option>';

        if (counselors.length === 0) {
            counselorSelect.innerHTML = '<option value="">No counselors available</option>';
            counselorSelect.disabled = true;
            counselorSelect.classList.remove('hidden');
            counselorAutoAssigned.classList.add('hidden');
            return;
        }

        counselors.forEach(counselor => {
            const option = document.createElement('option');
            option.value = counselor.id;
            option.textContent = counselor.display_text || counselor.name;
            counselorSelect.appendChild(option);
        });

        if (counselors.length === 1) {
            const onlyCounselor = counselors[0];
            counselorSelect.value = onlyCounselor.id;
            counselorSelect.disabled = true;
            counselorSelect.classList.add('hidden');
            counselorAutoAssigned.textContent = `${onlyCounselor.display_text || onlyCounselor.name}`;
            counselorAutoAssigned.classList.remove('hidden');
            counselorAutoAssignedInput.value = onlyCounselor.id;
            loadMonthAvailability();
            if (dateSelect.value) {
                loadAvailableSlots();
            }
        } else {
            counselorSelect.disabled = false;
            counselorSelect.classList.remove('hidden');
            counselorAutoAssigned.classList.add('hidden');
            counselorAutoAssignedInput.value = '';
            loadMonthAvailability();
        }
    }

    function updateBookingTypeOptions() {
        // All booking types are freely available — no year-level restrictions
        bookingTypeSelect.querySelectorAll('option').forEach(option => {
            option.disabled = false;
            option.hidden = false;
        });

        if (studentInitialInterviewCompleted === true || hasInitialInterviewAppointment) {
            // Hide Initial Interview if already completed or already booked
            bookingTypeInitial.disabled = true;
            bookingTypeInitial.hidden = true;
            if (bookingTypeSelect.value === 'Initial Interview') {
                bookingTypeSelect.value = '';
            }
            bookingTypeHelp.textContent = studentInitialInterviewCompleted
                ? 'Initial Interview already completed.'
                : 'Initial Interview already booked. Cancel your existing one to rebook.';
            bookingTypeHelp.style.color = '#6b7280';
        } else {
            bookingTypeHelp.textContent = '';
        }
    }

    function checkReferredCounselorsAvailability() {
        if (allowAllCounselors) {
            counselorTypeWrapper?.classList.add('hidden');
        }
        // Button visibility is already handled server-side — nothing to do here
    }

    function loadAvailableSlots() {
        const counselorId = getActiveCounselorId();
        const date = dateSelect.value;

        if (!counselorId || !date) {
            timeSlots.innerHTML = '<div class="time-slot-placeholder">Select a counselor and date to see available time slots</div>';
            selectedTime.value = '';
            return;
        }

        timeSlots.innerHTML = '<div class="time-slot-placeholder"><i class="fas fa-spinner fa-spin mr-1"></i>Loading available slots...</div>';

        fetch(`/appointments/available-slots?counselor_id=${counselorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    timeSlots.innerHTML = `<div class="time-slot-placeholder" style="border-color:#fecaca;color:#b91c1c">${data.message}</div>`;
                    selectedTime.value = '';
                    return;
                }

                if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                    timeSlots.innerHTML = '<div class="time-slot-placeholder" style="border-color:#fecaca;color:#b91c1c">No working hours for this date. Please choose another date.</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '';

                const availableSlots = [...data.available_slots].sort((a, b) =>
                    a.start.localeCompare(b.start)
                );

                if (availableSlots.length === 0) {
                    timeSlots.innerHTML = '<div class="time-slot-placeholder" style="border-color:#fde68a;color:#92400e;background:#fffbeb">No available time slots for this date. Please choose another date or counselor.</div>';
                    selectedTime.value = '';
                    return;
                }

                availableSlots.forEach(slot => {
                    const slotElement = document.createElement('button');
                    slotElement.type = 'button';
                    slotElement.className = 'time-slot-btn';
                    slotElement.textContent = slot.display;

                    slotElement.addEventListener('click', function() {
                        document.querySelectorAll('.time-slot-btn').forEach(s => {
                            s.classList.remove('selected');
                        });

                        this.classList.add('selected');

                        selectedTime.value = slot.start;
                        currentSelectedSlot = slot.start;
                    });

                    slotElement.dataset.start = slot.start;
                    slotElement.dataset.end = slot.end;
                    slotElement.dataset.status = slot.status;
                    timeSlots.appendChild(slotElement);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                timeSlots.innerHTML = '<div class="time-slot-placeholder" style="border-color:#fecaca;color:#b91c1c">Error loading time slots. Please try again.</div>';
                showSystemAlert('Error loading time slots. Please try again.', 'error', 'Time slots unavailable');
            });
    }

    counselorTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                loadCounselors(this.value);
                timeSlots.innerHTML = '<div class="time-slot-placeholder">Select a counselor and date to see available time slots</div>';
                selectedTime.value = '';
                selectedDate = null;
                dateSelect.value = '';
                loadMonthAvailability();
            }
        });
    });

    counselorSelect.addEventListener('change', function() {
        selectedDate = null;
        dateSelect.value = '';
        selectedTime.value = '';
        loadMonthAvailability();
        loadAvailableSlots();
    });

    if (allowAllCounselors) {
        counselorTypeWrapper?.classList.add('hidden');
    }

    loadCounselors('college');
    updateBookingTypeOptions();
    checkReferredCounselorsAvailability();
    renderCalendar();

    calendarPrev?.addEventListener('click', function() {
        const prevMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1, 1);
        const minMonth = new Date(minDate.getFullYear(), minDate.getMonth(), 1);
        if (prevMonth < minMonth) return;
        currentMonth = prevMonth;
        loadMonthAvailability();
    });

    calendarNext?.addEventListener('click', function() {
        currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1);
        loadMonthAvailability();
    });
});
</script>
@endsection