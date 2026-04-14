@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

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

    /* Base Layout & Glow */
    .resource-shell {
        position: relative;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 3rem;
    }
    .resource-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; z-index: 0;
    }
    .resource-glow.one { top: -50px; left: -50px; width: 250px; height: 250px; background: var(--gold-400); }
    .resource-glow.two { bottom: 10%; right: -50px; width: 220px; height: 220px; background: var(--maroon-800); }

    /* Glass Cards - High Specificity to override generic Tailwind bg-white */
    .panel-card {
        position: relative !important; z-index: 1; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft) !important; 
        background: rgba(255,255,255,0.95) !important;
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .panel-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    /* Header Specifics */
    .page-header h1 { color: var(--text-primary); font-weight: 700; letter-spacing: -0.02em; }
    .page-header p { color: var(--text-secondary); }

    /* Form Elements - Override Tailwind inputs */
    .section-title {
        font-size: 0.8rem; font-weight: 600; color: var(--text-primary);
        text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.35rem; display: block;
    }
    
    .input-field, .select-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft) !important; border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.85rem; padding: 0.6rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    /* Force focus styles even if Tailwind tries to override */
    .input-field:focus, .select-field:focus, .textarea-field:focus {
        border-color: var(--maroon-700) !important; 
        box-shadow: 0 0 0 3px rgba(92,26,26,0.08) !important;
        --tw-ring-color: transparent; /* Disable Tailwind ring */
        --tw-ring-offset-width: 0;
    }
    .textarea-field { resize: vertical; line-height: 1.5; }

    /* Custom Checkbox - Override native input styles */
    .custom-checkbox {
        appearance: none; -webkit-appearance: none;
        width: 1.1rem; height: 1.1rem; border: 1px solid var(--border-soft);
        border-radius: 0.25rem; background: white; cursor: pointer;
        position: relative; transition: all 0.2s; flex-shrink: 0;
    }
    .custom-checkbox:checked {
        background: var(--maroon-700); border-color: var(--maroon-700);
    }
    .custom-checkbox:checked::after {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(45deg);
        width: 0.3rem; height: 0.6rem; border: solid white; border-width: 0 2px 2px 0;
    }
    .custom-control-label {
        font-size: 0.85rem; color: var(--text-secondary); cursor: pointer; user-select: none;
    }

    /* Error Messages */
    .error-msg { color: #b91c1c; font-size: 0.75rem; margin-top: 0.35rem; font-weight: 500; }

    /* Buttons - Override Tailwind button colors */
    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%) !important;
        color: #fef9e7 !important; font-weight: 600; border-radius: 0.6rem;
        padding: 0.75rem 1.5rem; display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(92,26,26,0.15); transition: all 0.2s ease;
        border: none; text-decoration: none;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    
    .btn-secondary {
        background: white !important; color: var(--text-secondary) !important; border: 1px solid var(--border-soft) !important;
        font-weight: 600; border-radius: 0.6rem; padding: 0.75rem 1.5rem;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s ease; text-decoration: none;
    }
    .btn-secondary:hover { background: var(--bg-warm) !important; color: var(--text-primary) !important; border-color: var(--maroon-700) !important; }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .panel-card { padding: 1rem !important; }
        .btn-primary, .btn-secondary { width: 100%; justify-content: center; }
        .action-group { flex-direction: column-reverse; gap: 0.75rem; }
        .action-group > * { width: 100%; }
    }
</style>

<div class="min-h-screen resource-shell">
    <div class="resource-glow one"></div>
    <div class="resource-glow two"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="mb-6 panel-card p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="page-header">
                    <h1 class="text-xl sm:text-2xl font-bold">
                        {{ isset($resource) ? 'Edit Resource' : 'Create New Resource' }}
                    </h1>
                    <p class="text-sm mt-1">
                        {{ isset($resource) ? 'Update the resource details.' : 'Add a new mental health resource for students.' }}
                    </p>
                </div>
                <a href="{{ route('counselor.resources.index') }}"
                   class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Resources
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="panel-card p-5 sm:p-6 md:p-8">
            <form action="{{ isset($resource) ? route('counselor.resources.update', $resource) : route('counselor.resources.store') }}" method="POST">
                @csrf
                @if(isset($resource))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="section-title">Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $resource->title ?? '') }}"
                               class="input-field" required placeholder="Enter resource title">
                        @error('title')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="section-title">Description *</label>
                        <textarea name="description" id="description" rows="3"
                                  class="textarea-field" required placeholder="Briefly describe this resource...">{{ old('description', $resource->description ?? '') }}</textarea>
                        @error('description')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon -->
                    <div>
                        <label for="icon" class="section-title">Icon *</label>
                        <select name="icon" id="icon" class="select-field" required>
                            <option value="">Select an icon</option>
                            @foreach($icons as $icon)
                                <option value="{{ $icon }}" {{ old('icon', $resource->icon ?? '') == $icon ? 'selected' : '' }}>
                                    {{ $icon }}
                                </option>
                            @endforeach
                        </select>
                        @error('icon')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="section-title">Category *</label>
                        <select name="category" id="category" class="select-field" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $value => $label)
                                <option value="{{ $value }}" {{ old('category', $resource->category ?? '') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button Text -->
                    <div>
                        <label for="button_text" class="section-title">Button Text *</label>
                        <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $resource->button_text ?? '') }}"
                               class="input-field" required placeholder="e.g., Explore Videos">
                        @error('button_text')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="order" class="section-title">Display Order</label>
                        <input type="number" name="order" id="order" value="{{ old('order', $resource->order ?? 0) }}"
                               class="input-field" min="0" placeholder="0">
                        <p class="text-xs text-[var(--text-muted)] mt-1">Lower numbers appear first.</p>
                        @error('order')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link -->
                    <div class="md:col-span-2">
                        <label for="link" class="section-title">Resource Link *</label>
                        <input type="url" name="link" id="link" value="{{ old('link', $resource->link ?? '') }}"
                               class="input-field" required placeholder="https://example.com/resource">
                        @error('link')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-[rgba(250,248,245,0.6)] border border-[var(--border-soft)]">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $resource->is_active ?? true) ? 'checked' : '' }}
                                   class="custom-checkbox">
                            <label for="is_active" class="custom-control-label font-medium text-[var(--text-primary)]">
                                Make this resource active and visible to students
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 action-group flex flex-col md:flex-row gap-4 justify-end">
                    <a href="{{ route('counselor.resources.index') }}"
                       class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit"
                            class="btn-primary">
                        <i class="fas fa-save mr-2 text-xs"></i>
                        {{ isset($resource) ? 'Update Resource' : 'Create Resource' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection