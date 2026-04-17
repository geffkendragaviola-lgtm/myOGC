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

    .announce-form-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    .announce-form-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .announce-form-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .announce-form-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover { 
        box-shadow: 0 4px 14px rgba(44,36,32,0.06); 
    }
    .hero-card::before, .panel-card::before, .glass-card::before {
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

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { 
        width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; 
        align-items: center; justify-content: center; 
        background: rgba(254,249,231,0.7); color: var(--maroon-700); 
    }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { 
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); 
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; 
    }
    .input-field, .select-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus, .textarea-field:focus { 
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); 
    }
    .textarea-field { min-height: 120px; resize: vertical; line-height: 1.5; }

    .upload-zone {
        border: 2px dashed var(--border-soft); border-radius: 0.75rem;
        background: rgba(250,248,245,0.6); transition: all 0.2s ease;
        cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 1.5rem; text-align: center; min-height: 10rem;
    }
    .upload-zone:hover { border-color: var(--maroon-700); background: rgba(254,249,231,0.5); }
    .upload-zone i { font-size: 1.5rem; color: var(--text-muted); margin-bottom: 0.5rem; }
    .upload-zone .hint { font-size: 0.65rem; color: var(--text-muted); margin-top: 0.25rem; }
    .upload-zone .hint strong { color: var(--text-secondary); font-weight: 600; }

    .image-preview-card {
        position: relative; border-radius: 0.75rem; overflow: hidden;
        border: 1px solid var(--border-soft); background: #fff;
        box-shadow: 0 2px 8px rgba(44,36,32,0.04);
    }
    .image-preview-card img { width: 100%; height: 12rem; object-fit: cover; display: block; }
    .image-remove-btn {
        position: absolute; top: 0.5rem; right: 0.5rem;
        width: 1.75rem; height: 1.75rem; border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(185,28,28,0.95); color: white;
        font-size: 0.65rem; transition: all 0.18s ease;
    }
    .image-remove-btn:hover { transform: scale(1.05); background: var(--maroon-800); }

    .radio-group, .checkbox-group { display: flex; flex-direction: column; gap: 0.75rem; }
    .radio-option, .checkbox-option {
        display: flex; align-items: flex-start; gap: 0.5rem;
        padding: 0.5rem; border-radius: 0.5rem;
        transition: background-color 0.15s ease;
    }
    .radio-option:hover, .checkbox-option:hover { background: rgba(254,249,231,0.4); }
    .radio-option input, .checkbox-option input {
        margin-top: 0.15rem; width: 1rem; height: 1rem;
        accent-color: var(--maroon-700); cursor: pointer;
    }
    .radio-option label, .checkbox-option label {
        font-size: 0.8rem; color: var(--text-secondary); cursor: pointer; line-height: 1.4;
    }

    .college-grid {
        display: grid; grid-template-columns: repeat(1, 1fr); gap: 0.5rem;
        max-height: 12rem; overflow-y: auto; padding: 0.75rem;
        border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9);
    }
    @media (min-width: 768px) { .college-grid { grid-template-columns: repeat(2, 1fr); } }

    .error-text {
        font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem;
        display: flex; align-items: center; gap: 0.25rem;
    }
    .error-text::before { content: "•"; font-weight: bold; }

    .form-actions {
        display: flex; flex-direction: column-reverse; gap: 0.75rem;
        padding-top: 1rem; border-top: 1px solid var(--border-soft)/60;
    }
    @media (min-width: 768px) { 
        .form-actions { flex-direction: row; justify-content: flex-end; } 
    }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: var(--text-secondary); background: rgba(255,255,255,0.9);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field, .textarea-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; }
        .upload-zone { min-height: 9rem; padding: 1.25rem; }
        .image-preview-card img { height: 10rem; }
        .college-grid { max-height: 10rem; }
        .radio-option label, .checkbox-option label { font-size: 0.85rem; }
    }
</style>

<div class="min-h-screen announce-form-shell">
    <div class="announce-form-glow one"></div>
    <div class="announce-form-glow two"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex items-start gap-3">
                    <div class="hero-icon">
                        <i class="fas {{ isset($announcement) ? 'fa-pen-to-square' : 'fa-plus' }} text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="hero-badge">
                            <span class="hero-badge-dot"></span>
                            {{ isset($announcement) ? 'Edit Mode' : 'Create Mode' }}
                        </div>
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">
                            {{ isset($announcement) ? 'Edit Announcement' : 'Create New Announcement' }}
                        </h1>
                        <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                            {{ isset($announcement) ? 'Update the announcement details below' : 'Fill in the details below to create a new announcement' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="panel-card">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-file-alt text-[9px] sm:text-xs"></i></div>
                <div>
                    <h2 class="panel-title">Announcement Details</h2>
                    <p class="panel-subtitle hidden sm:block">Configure content, targeting, and scheduling</p>
                </div>
            </div>

            <form action="{{ isset($announcement) ? route('counselor.announcements.update', $announcement) : route('counselor.announcements.store') }}"
                  method="POST" enctype="multipart/form-data" class="p-4 sm:p-5 md:p-6">
                @csrf
                @if(isset($announcement))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Announcement Image -->
                    <div class="md:col-span-2">
                        <label for="image" class="field-label">Announcement Image (Optional)</label>

                        <!-- Current Image Preview -->
                        @if(isset($announcement) && $announcement->image_url)
                            <div class="mb-4">
                                <div class="image-preview-card">
                                    <img src="{{ $announcement->image_url }}" alt="Current announcement image">
                                    <a href="{{ route('counselor.announcements.remove-image', $announcement) }}"
                                       class="image-remove-btn"
                                       onclick="return confirm('Are you sure you want to remove this image?')"
                                       title="Remove Image">
                                        <i class="fas fa-xmark"></i>
                                    </a>
                                </div>
                                <p class="text-[10px] sm:text-xs text-[#8b7e76] mt-2">Current image — upload a new one to replace</p>
                            </div>
                        @endif

                        <!-- Image Upload Area -->
                        <div class="w-full">
                            <label for="image" class="upload-zone">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p class="text-xs sm:text-sm text-[#6b5e57]">
                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                </p>
                                <p class="hint">PNG, JPG, GIF (MAX. 10MB)</p>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>

                        <!-- New Image Preview -->
                        <div id="image-preview" class="mt-4 hidden">
                            <div class="image-preview-card">
                                <img id="preview" class="w-full h-48 object-cover" alt="Preview">
                                <button type="button" onclick="removeImagePreview()"
                                        class="image-remove-btn" title="Remove Preview">
                                    <i class="fas fa-xmark"></i>
                                </button>
                            </div>
                        </div>

                        @error('image')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="field-label">Title <span class="text-[#b91c1c]">*</span></label>
                        <input type="text" name="title" id="title"
                               value="{{ old('title', $announcement->title ?? '') }}"
                               class="input-field"
                               placeholder="Enter announcement title" required>
                        @error('title')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="md:col-span-2">
                        <label for="content" class="field-label">Content <span class="text-[#b91c1c]">*</span></label>
                        <textarea name="content" id="content" rows="6"
                                  class="textarea-field"
                                  placeholder="Enter announcement content..." required>{{ old('content', $announcement->content ?? '') }}</textarea>
                        @error('content')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- College Targeting -->
                    <div class="md:col-span-2">
                        <label class="field-label">College Availability <span class="text-[#b91c1c]">*</span></label>

                        <!-- All Colleges Option -->
                        <div class="mb-4 radio-group">
                            <div class="radio-option">
                                <input type="radio" id="for_all_colleges_true" name="for_all_colleges" value="1"
                                       {{ old('for_all_colleges', $announcement->for_all_colleges ?? true) ? 'checked' : '' }}>
                                <label for="for_all_colleges_true">
                                    <strong>All Colleges</strong> — Announcement visible to students from all colleges
                                </label>
                            </div>

                            <div class="radio-option">
                                <input type="radio" id="for_all_colleges_false" name="for_all_colleges" value="0"
                                       {{ old('for_all_colleges', $announcement->for_all_colleges ?? '') === '0' ? 'checked' : '' }}>
                                <label for="for_all_colleges_false">
                                    <strong>Specific Colleges</strong> — Choose which colleges can see this announcement
                                </label>
                            </div>
                        </div>

                        <!-- College Selection (shown only when specific colleges is selected) -->
                        <div id="colleges_selection" class="{{ old('for_all_colleges', $announcement->for_all_colleges ?? true) ? 'hidden' : '' }}">
                            <label class="field-label">Select Colleges <span class="text-[#b91c1c]">*</span></label>
                            <div class="college-grid">
                                @foreach($colleges as $college)
                                    <div class="checkbox-option">
                                        <input type="checkbox" id="college_{{ $college->id }}" name="colleges[]"
                                               value="{{ $college->id }}"
                                               {{ in_array($college->id, old('colleges', $selectedColleges ?? [])) ? 'checked' : '' }}>
                                        <label for="college_{{ $college->id }}">{{ $college->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('colleges')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates -->
                    <div>
                        <label for="start_date" class="field-label">Start Date (Optional)</label>
                        <input type="date" name="start_date" id="start_date"
                               value="{{ old('start_date', isset($announcement->start_date) ? $announcement->start_date->format('Y-m-d') : '') }}"
                               class="input-field">
                        @error('start_date')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="field-label">End Date (Optional)</label>
                        <input type="date" name="end_date" id="end_date"
                               value="{{ old('end_date', isset($announcement->end_date) ? $announcement->end_date->format('Y-m-d') : '') }}"
                               class="input-field">
                        @error('end_date')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="md:col-span-2">
                        <div class="checkbox-option" style="padding: 0.75rem; background: rgba(254,249,231,0.3); border-radius: 0.5rem;">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}
                                   style="margin-top: 0.1rem;">
                            <label for="is_active" style="font-weight: 600; color: var(--text-primary);">
                                Activate this announcement immediately
                            </label>
                        </div>
                        @error('is_active')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('counselor.announcements.index') }}"
                       class="secondary-btn px-5 py-2.5 text-xs sm:text-sm">
                        Cancel
                    </a>
                    <button type="submit" class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                        <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>
                        {{ isset($announcement) ? 'Update Announcement' : 'Create Announcement' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // College selection toggle
    const allCollegesRadio = document.getElementById('for_all_colleges_true');
    const specificCollegesRadio = document.getElementById('for_all_colleges_false');
    const collegesSelection = document.getElementById('colleges_selection');

    function toggleCollegesSelection() {
        if (specificCollegesRadio.checked) {
            collegesSelection.classList.remove('hidden');
        } else {
            collegesSelection.classList.add('hidden');
            // Uncheck all college checkboxes when "all colleges" is selected
            document.querySelectorAll('input[name="colleges[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    }

    allCollegesRadio.addEventListener('change', toggleCollegesSelection);
    specificCollegesRadio.addEventListener('change', toggleCollegesSelection);

    // Image preview functionality
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const preview = document.getElementById('preview');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.addEventListener('load', function() {
                preview.setAttribute('src', this.result);
                imagePreview.classList.remove('hidden');
            });
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }
    });

    // Remove image preview
    window.removeImagePreview = function() {
        imageInput.value = '';
        imagePreview.classList.add('hidden');
    }

    // Initial toggle
    toggleCollegesSelection();
});
</script>
@endsection