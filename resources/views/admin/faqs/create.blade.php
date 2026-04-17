@extends('layouts.admin')

@section('title', 'Create FAQ - Admin Panel')

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

    .create-faq-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .create-faq-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .create-faq-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .create-faq-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .section-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .section-card:hover {
        box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    }
    .hero-card::before, .panel-card::before, .section-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .panel-icon, .section-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8);
        padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.16em; color: var(--maroon-700);
    }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }

    .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%);
        pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.25rem; }

    .primary-btn, .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; font-size: 0.8rem;
        padding: 0.55rem 0.85rem;
    }
    .primary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        background: #ffffff; color: var(--text-secondary); border: 1px solid var(--border-soft);
        box-shadow: 0 2px 6px rgba(44,36,32,0.03);
    }
    .secondary-btn:hover { background: #f5f0eb; }

    .panel-topline, .section-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header, .section-header {
        display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-soft)/60;
    }
    .panel-icon, .section-icon {
        width: 2rem; height: 2rem; border-radius: 0.6rem; background: rgba(254,249,231,0.7); color: var(--maroon-700);
    }
    .panel-title, .section-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle, .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label {
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary);
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em;
    }
    .input-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field { padding: 0.55rem 0.75rem; }
    .textarea-field { padding: 0.65rem 0.75rem; resize: vertical; }
    .input-field:focus, .textarea-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }
    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; }

    .option-card {
        display: flex; align-items: flex-start; padding: 0.7rem 0.85rem; border-radius: 0.65rem;
        background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft);
        transition: all 0.2s ease;
    }
    .option-card:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }

    @media (max-width: 639px) {
        .panel-header, .section-header { padding: 0.75rem 1rem; }
        .input-field, .textarea-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; padding: 0.6rem 1rem; }
        .space-x-3 > * + * { margin-left: 0; margin-top: 0.5rem; }
        .flex.justify-end { flex-direction: column; }
    }
</style>

<div class="min-h-screen create-faq-shell">
    <div class="create-faq-glow one"></div>
    <div class="create-faq-glow two"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-circle-question text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                FAQ Creation
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Create FAQ</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Add a frequently asked question and its answer.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-arrow-left text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Navigation</p>
                                <p class="summary-value">Back to FAQs</p>
                                <p class="summary-subtext hidden sm:block">Return to the FAQ library anytime.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.faqs.index') }}"
                           class="secondary-btn px-3 py-2 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon">
                    <i class="fas fa-pen text-[9px] sm:text-xs"></i>
                </div>
                <div>
                    <h2 class="panel-title">FAQ Form</h2>
                    <p class="panel-subtitle hidden sm:block">Write a clear question, a helpful answer, and set its category and display order.</p>
                </div>
            </div>

            <div class="p-3 sm:p-4">
                <form action="{{ route('admin.faqs.store') }}" method="POST">
                    @csrf

                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-circle-info text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">FAQ Details</h3>
                                <p class="section-subtitle hidden sm:block">Create the content and settings for this FAQ entry.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 gap-3 sm:gap-4">
                            <div>
                                <label for="question" class="field-label">Question *</label>
                                <textarea name="question" id="question" rows="3" required
                                          class="textarea-field">{{ old('question') }}</textarea>
                                @error('question')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="answer" class="field-label">Answer *</label>
                                <textarea name="answer" id="answer" rows="5" required
                                          class="textarea-field">{{ old('answer') }}</textarea>
                                @error('answer')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <label for="category" class="field-label">Category (optional)</label>
                                    <input type="text" name="category" id="category" value="{{ old('category') }}"
                                           class="input-field" placeholder="e.g., General, Counseling, Events">
                                    @error('category')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="order" class="field-label">Display Order</label>
                                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0"
                                           class="input-field" placeholder="0">
                                    @error('order')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="option-card">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-[#7a2a2a] focus:ring-[#7a2a2a] border-[#e5e0db] rounded mt-0.5 flex-shrink-0">
                                <label for="is_active" class="ml-3 text-xs font-medium text-[#4a3f3a] cursor-pointer">Active - Show this FAQ to users immediately</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                        <a href="{{ route('admin.faqs.index') }}" class="secondary-btn text-center rounded-lg">Cancel</a>
                        <button type="submit" class="primary-btn rounded-lg">
                            <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i> Create FAQ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection