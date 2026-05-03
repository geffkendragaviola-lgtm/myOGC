@extends('layouts.admin')

@section('title', 'Edit Service - Admin Panel')

@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c;
        --maroon-800: #5c1a1a;
        --maroon-700: #7a2a2a;
        --gold-400: #d4af37;
        --bg-warm: #faf8f5;
        --border-soft: #e5e0db;
        --text-primary: #2c2420;
        --text-secondary: #6b5e57;
        --text-muted: #8b7e76;
    }

    .edit-shell { position: relative; overflow: hidden; background: var(--bg-warm); min-height: 100vh; }
    .edit-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25; }
    .edit-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .edit-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
    }
    .hero-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }
    .hero-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
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

    .section-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .section-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }
    .section-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .section-header {
        display: flex; align-items: center; gap: 0.7rem;
        padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft);
        background: rgba(250,248,245,0.5);
    }
    .section-icon {
        width: 2rem; height: 2rem; border-radius: 0.6rem;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .section-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .field-input, .field-select, .field-textarea {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .field-textarea { padding: 0.65rem 0.75rem; resize: vertical; }
    .field-input:focus, .field-select:focus, .field-textarea:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }

    .checkbox-card {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.7rem 0.85rem; border-radius: 0.65rem;
        background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft);
        transition: all 0.2s ease;
    }
    .checkbox-card:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }

    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; }
    .error-alert {
        border-radius: 0.6rem; padding: 0.65rem 0.85rem;
        border: 1px solid rgba(185,28,28,0.3); background: rgba(254,242,242,0.8); color: #b91c1c;
        font-size: 0.8rem;
    }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15); padding: 0.55rem 1rem; font-size: 0.8rem;
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center;
        background: #fff; color: var(--text-secondary); border: 1px solid var(--border-soft);
        padding: 0.55rem 1rem; font-size: 0.8rem;
    }
    .secondary-btn:hover { background: #f5f0eb; }

    .img-preview {
        width: 100%; border-radius: 0.65rem; overflow: hidden;
        border: 1px solid var(--border-soft); background: var(--bg-warm);
        display: flex; align-items: center; justify-content: center;
        min-height: 20rem;
    }
    .img-preview img {
        width: 100%; height: auto; max-height: 32rem; object-fit: contain;
        display: block; background: var(--bg-warm);
    }
    .img-placeholder {
        width: 100%; min-height: 20rem; border-radius: 0.65rem;
        border: 2px dashed var(--border-soft); background: rgba(250,248,245,0.5);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        color: var(--text-muted); padding: 2rem;
    }

    @media (max-width: 639px) {
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; }
    }
</style>

<div class="min-h-screen edit-shell">
    <div class="edit-glow one"></div>
    <div class="edit-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon flex-shrink-0">
                            <i class="fas fa-concierge-bell text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center text-[#7a2a2a] hover:text-[#5c1a1a] mb-2 font-medium text-xs sm:text-sm">
                                <i class="fas fa-arrow-left mr-1.5"></i> Back to Services
                            </a>
                            <div class="hero-badge"><span class="hero-badge-dot"></span>Service Editor</div>
                            <h1 class="text-lg sm:text-xl font-semibold tracking-tight text-[#2c2420] mt-2">Edit Service</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1">Update the service details shown on the dashboard.</p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center gap-3 p-4">
                        <div class="summary-icon flex-shrink-0">
                            <i class="fas fa-wand-magic-sparkles text-sm"></i>
                        </div>
                        <div class="text-center sm:text-left min-w-0">
                            <p class="summary-label">Edit Flow</p>
                            <p class="summary-value">Update Details</p>
                            <p class="summary-subtext">Modify service text, visibility, and image.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="error-alert mb-4 flex items-center gap-2">
                <i class="fas fa-circle-exclamation"></i>
                <span class="font-semibold">Please fix the errors below.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data" class="space-y-4 sm:space-y-5">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
                <!-- Left Column: Form Fields -->
                <div class="space-y-4 sm:space-y-5">
                    <!-- Details -->
                    <div class="section-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-pen-to-square text-xs"></i></div>
                            <div>
                                <h2 class="section-title">Service Details</h2>
                                <p class="section-subtitle hidden sm:block">Title, description, and visibility.</p>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5 space-y-4">
                            <div>
                                <label class="field-label">Title</label>
                                <input type="text" name="title" value="{{ old('title', $service->title) }}" class="field-input" required>
                                @error('title')<p class="error-text">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="field-label">Description</label>
                                <textarea name="description" rows="5" class="field-textarea" required>{{ old('description', $service->description) }}</textarea>
                                @error('description')<p class="error-text">{{ $message }}</p>@enderror
                            </div>

                            {{-- Keep hidden so the form still submits these values unchanged --}}
                            <input type="hidden" name="route_name" value="{{ $service->route_name }}">
                            <input type="hidden" name="order" value="{{ $service->order }}">

                            <div class="checkbox-card">
                                <input type="hidden" name="is_active" value="0">
                                <input id="is_active" type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-[#e5e0db] text-[#7a2a2a] focus:ring-[#7a2a2a]/20 flex-shrink-0">
                                <label for="is_active" class="text-xs sm:text-sm font-medium text-[#4a3f3a] cursor-pointer">
                                    Active — show this service on the dashboard
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Upload -->
                    <div class="section-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-upload text-xs"></i></div>
                            <div>
                                <h2 class="section-title">Upload New Image</h2>
                                <p class="section-subtitle hidden sm:block">Replace the current service image.</p>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5">
                            <label class="field-label">Choose Image File</label>
                            <input type="file" name="image" accept="image/*" class="field-input">
                            @error('image')<p class="error-text">{{ $message }}</p>@enderror
                            <p class="text-[10px] text-[#8b7e76] mt-2">Recommended: 800x600px or larger, JPG/PNG format</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Image Preview -->
                <div class="space-y-4 sm:space-y-5">
                    <div class="section-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-image text-xs"></i></div>
                            <div>
                                <h2 class="section-title">Current Image</h2>
                                <p class="section-subtitle hidden sm:block">Preview of the service image.</p>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5">
                            @php
                                $img = $service->image_url;
                                $imgSrc = $img && preg_match('/^https?:\/\//i', $img) ? $img : ($img ? asset('storage/' . $img) : null);
                            @endphp

                            @if($imgSrc)
                                <div class="img-preview">
                                    <img src="{{ $imgSrc }}" alt="{{ $service->title }}" onerror="this.parentElement.innerHTML='<div class=\'img-placeholder\'><i class=\'fas fa-image-slash\'></i><p>Image not found</p></div>'" />
                                </div>
                            @else
                                <div class="img-placeholder">
                                    <i class="fas fa-image text-3xl"></i>
                                    <p class="mt-2 text-sm font-medium">No image uploaded</p>
                                    <p class="text-xs text-[#8b7e76] mt-1">Upload an image using the form</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-1">
                <a href="{{ route('admin.services.index') }}" class="secondary-btn">Cancel</a>
                <button type="submit" class="primary-btn">
                    <i class="fas fa-floppy-disk mr-1.5 text-[9px]"></i> Save Changes
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
