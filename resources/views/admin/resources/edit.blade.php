@extends('layouts.admin')

@section('title', 'Edit Resource - Admin Panel')

@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c; --maroon-800: #5c1a1a; --maroon-700: #7a2a2a;
        --gold-500: #c9a227; --gold-400: #d4af37;
        --bg-warm: #faf8f5; --border-soft: #e5e0db;
        --text-primary: #2c2420; --text-secondary: #6b5e57; --text-muted: #8b7e76;
    }

    .edit-resource-shell {
        position: relative; overflow: hidden; background: var(--bg-warm); min-height: 100vh;
    }
    .edit-resource-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .edit-resource-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .edit-resource-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .summary-card, .glass-alert {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .section-card {
        position: relative; overflow: hidden; border-radius: 0.85rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.98);
        box-shadow: 0 4px 16px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover {
        box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    }
    .hero-card::before, .glass-alert::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .section-icon {
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
        border: 1px solid rgba(92,26,26,0.15); background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white; box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
    }
    .summary-avatar {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0; font-weight: 700;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.15rem; line-height: 1.25; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .primary-btn, .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
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

    .section-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .section-header {
        display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-soft); background: rgba(250,248,245,0.5);
    }
    .section-icon {
        width: 2rem; height: 2rem; border-radius: 0.6rem; background: rgba(254,249,231,0.7); color: var(--maroon-700);
    }
    .section-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label {
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary);
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em;
    }
    .input-field, .textarea-field, .select-field, .file-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field, .select-field, .file-field { padding: 0.55rem 0.75rem; }
    .textarea-field { padding: 0.65rem 0.75rem; resize: vertical; }
    .input-field:focus, .textarea-field:focus, .select-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }
    .file-field { padding: 0.6rem 0.7rem; }
    .helper-text { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.3rem; line-height: 1.5; }
    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; }

    .option-card {
        display: flex; align-items: flex-start; padding: 0.7rem 0.85rem; border-radius: 0.65rem;
        background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft);
        transition: all 0.2s ease;
    }
    .option-card:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }

    @media (max-width: 639px) {
        .section-header { padding: 0.75rem 1rem; }
        .input-field, .textarea-field, .select-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; padding: 0.6rem 1rem; }
        .space-x-3 > * + * { margin-left: 0; margin-top: 0.5rem; }
        .flex.justify-end { flex-direction: column; }
    }
</style>

<div class="min-h-screen edit-resource-shell">
    <div class="edit-resource-glow one"></div>
    <div class="edit-resource-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                        <div class="hero-icon">
                            <i class="fas fa-pen-to-square text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('admin.resources.index') }}" class="inline-flex items-center text-[#7a2a2a] hover:text-[#5c1a1a] mb-3 sm:mb-4 font-medium text-xs sm:text-sm">
                                <i class="fas fa-arrow-left mr-1.5"></i> Back to Resources
                            </a>
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Resource Editor
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Edit Resource</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Update the resource details, metadata, link behavior, and visibility settings.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card min-w-[240px] sm:min-w-[280px]">
                    <div class="relative h-full flex flex-col justify-center p-4 sm:p-5">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="summary-avatar flex-shrink-0">
                                <i class="{{ $resource->icon }} text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="summary-label">Resource Profile</div>
                                <div class="summary-value truncate" title="{{ $resource->title }}">{{ $resource->title }}</div>
                                <div class="summary-subtext">{{ Str::title(str_replace('_', ' ', $resource->category)) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 sm:gap-6 items-start">
                <!-- Left Column -->
                <div class="space-y-5 sm:space-y-6">
                    <div class="section-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-circle-info text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Resource Details</h3>
                                <p class="section-subtitle hidden sm:block">Edit the title, description, category, and main link.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 gap-3 sm:gap-4 md:gap-6">
                            <div>
                                <label for="title" class="field-label">Title *</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $resource->title) }}"
                                       class="input-field" required>
                                @error('title')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <label for="category" class="field-label">Category *</label>
                                    <select name="category" id="category" class="select-field" required>
                                        <option value="">Select a category</option>
                                        @foreach($categories as $value => $label)
                                            <option value="{{ $value }}" {{ old('category', $resource->category) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="button_text" class="field-label">Button Text *</label>
                                    <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $resource->button_text) }}"
                                           class="input-field" required>
                                    @error('button_text')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="description" class="field-label">Description *</label>
                                <textarea name="description" id="description" rows="4"
                                          class="textarea-field" required>{{ old('description', $resource->description) }}</textarea>
                                @error('description')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="link" class="field-label">Resource Link *</label>
                                <input type="url" name="link" id="link" value="{{ old('link', $resource->link) }}"
                                       class="input-field" required>
                                @error('link')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-5 sm:space-y-6">
                    <div class="section-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-sliders text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Media & Settings</h3>
                                <p class="section-subtitle hidden sm:block">Icon selection, thumbnail, and display preferences.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 gap-3 sm:gap-4 md:gap-6">
                            <div>
                                <label for="icon" class="field-label">Icon *</label>
                                <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-7 gap-2">
                                    @foreach($icons as $icon)
                                        <label class="option-card cursor-pointer flex items-center justify-center gap-2 p-2 sm:p-2.5 !m-0">
                                            <input type="radio"
                                                   name="icon"
                                                   value="{{ $icon }}"
                                                   class="sr-only peer"
                                                   {{ old('icon', $resource->icon) == $icon ? 'checked' : '' }}
                                                   required>
                                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-[#e5e0db] text-[#6b5e57] peer-checked:border-[#7a2a2a] peer-checked:text-[#7a2a2a] peer-checked:bg-[#fef9e7]">
                                                <i class="{{ $icon }}"></i>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('icon')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="image" class="field-label">Replace Image (optional)</label>
                                <input type="file" name="image" id="image" accept="image/*" class="file-field">
                                @error('image')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-3 sm:space-y-4">
                                <div class="option-card">
                                    <input type="checkbox" name="use_yt_thumbnail" id="use_yt_thumbnail" value="1" {{ old('use_yt_thumbnail', $resource->use_yt_thumbnail) ? 'checked' : '' }}
                                           class="h-4 w-4 text-[#7a2a2a] focus:ring-[#7a2a2a] border-[#e5e0db] rounded mt-0.5 flex-shrink-0">
                                    <label for="use_yt_thumbnail" class="ml-3 text-xs sm:text-sm font-medium text-[#4a3f3a] cursor-pointer">Use YouTube thumbnail (for YouTube links)</label>
                                </div>

                                <div class="option-card">
                                    <input type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ old('is_pinned', $resource->is_pinned ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-[#7a2a2a] focus:ring-[#7a2a2a] border-[#e5e0db] rounded mt-0.5 flex-shrink-0">
                                    <label for="is_pinned" class="ml-3 text-xs sm:text-sm font-medium text-[#4a3f3a] cursor-pointer">Pin this resource to appear first</label>
                                </div>

                                <div class="option-card">
                                    <input type="checkbox" name="show_disclaimer" id="show_disclaimer" value="1" {{ old('show_disclaimer', $resource->show_disclaimer) ? 'checked' : '' }}
                                           class="h-4 w-4 text-[#7a2a2a] focus:ring-[#7a2a2a] border-[#e5e0db] rounded mt-0.5 flex-shrink-0">
                                    <label for="show_disclaimer" class="ml-3 text-xs sm:text-sm font-medium text-[#4a3f3a] cursor-pointer">Show disclaimer</label>
                                </div>

                                <div>
                                    <label for="disclaimer_text" class="field-label">Disclaimer Text (optional)</label>
                                    <textarea name="disclaimer_text" id="disclaimer_text" rows="3"
                                              class="textarea-field">{{ old('disclaimer_text', $resource->disclaimer_text) }}</textarea>
                                    @error('disclaimer_text')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="option-card">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $resource->is_active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-[#7a2a2a] focus:ring-[#7a2a2a] border-[#e5e0db] rounded mt-0.5 flex-shrink-0">
                                    <label for="is_active" class="ml-3 text-xs sm:text-sm font-medium text-[#4a3f3a] cursor-pointer">Make this resource active and visible to students</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- End Grid -->

            <div class="mt-6 flex justify-end">
                <button type="submit" class="primary-btn w-full sm:w-auto rounded-lg">
                    <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>Update Resource
                </button>
            </div>
        </form>
    </div>
</div>
@endsection