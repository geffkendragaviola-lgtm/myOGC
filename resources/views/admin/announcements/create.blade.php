@extends('layouts.admin')

@section('title', 'Create Announcement - Admin Panel')

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

    .hero-card, .panel-card, .section-card, .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .section-card:hover, .summary-card:hover {
        box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    }
    .hero-card::before, .panel-card::before, .section-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .summary-card {
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
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
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.25rem; }

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

    .panel-topline, .section-topline { position: absolute; inset-inline: 0; top: 0; }
    .panel-topline { height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .section-topline { height: 2.5px; background: linear-gradient(90deg, var(--maroon-700), var(--gold-400)); }

    .panel-header, .section-header {
        display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-soft)/60;
    }
    .panel-icon, .section-icon {
        width: 2rem; height: 2rem; border-radius: 0.6rem;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
    }
    .panel-title, .section-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle, .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label {
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary);
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em;
    }
    .input-field, .textarea-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field, .select-field { padding: 0.55rem 0.75rem; }
    .textarea-field { padding: 0.65rem 0.75rem; resize: vertical; min-height: 120px; }
    .input-field:focus, .textarea-field:focus, .select-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }

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

    .radio-group { display: flex; flex-direction: column; gap: 0.75rem; }
    .radio-option, .checkbox-option, .option-card {
        display: flex; align-items: flex-start; gap: 0.5rem;
        padding: 0.5rem; border-radius: 0.5rem;
        transition: background-color 0.15s ease;
    }
    .radio-option:hover, .checkbox-option:hover { background: rgba(254,249,231,0.4); }
    .option-card {
        background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft);
        padding: 0.7rem 0.85rem; border-radius: 0.65rem;
    }
    .option-card:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }

    .radio-option input, .checkbox-option input, .option-card input[type="checkbox"] {
        margin-top: 0.15rem; width: 1rem; height: 1rem;
        accent-color: var(--maroon-700); cursor: pointer; flex-shrink: 0;
    }
    .radio-option label, .checkbox-option label, .option-card label {
        font-size: 0.8rem; color: var(--text-secondary); cursor: pointer; line-height: 1.4;
    }

    .college-grid {
        display: grid; grid-template-columns: repeat(1, 1fr); gap: 0.5rem;
        max-height: 12rem; overflow-y: auto; padding: 0.75rem;
        border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9);
    }
    @media (min-width: 768px) { .college-grid { grid-template-columns: repeat(2, 1fr); } }

    .helper-text { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.3rem; line-height: 1.5; }
    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; display: flex; align-items: center; gap: 0.25rem; }
    .error-text::before { content: "•"; font-weight: bold; }

    .alert-error { border-radius: 0.6rem; padding: 0.65rem 0.85rem; border-width: 1px; margin-bottom: 1rem; }

    .form-action-primary, .form-action-secondary {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;
        padding: 0.55rem 0.85rem; white-space: nowrap;
    }
    .form-action-primary {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .form-action-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .form-action-secondary { background: #f5f0eb; color: var(--text-secondary); border: 1px solid var(--border-soft); }
    .form-action-secondary:hover { background: #e5e0db; }

    @media (max-width: 639px) {
        .panel-header, .section-header { padding: 0.75rem 1rem; }
        .input-field, .select-field { padding: 0.5rem 0.7rem; font-size: 0.85rem; }
        .textarea-field { padding: 0.6rem 0.7rem; }
        .form-action-primary, .form-action-secondary { width: 100%; justify-content: center; }
        .upload-zone { min-height: 9rem; padding: 1.25rem; }
        .image-preview-card img { height: 10rem; }
        .college-grid { max-height: 10rem; }
    }
</style>

<div class="min-h-screen announce-form-shell">
    <div class="announce-form-glow one"></div>
    <div class="announce-form-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-bullhorn text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Announcement Creation
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">
                                Create New Announcement
                            </h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Fill in the details below to broadcast a new announcement.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center gap-3 p-4">
                        <div class="summary-icon flex-shrink-0">
                            <i class="fas fa-wand-magic-sparkles text-sm"></i>
                        </div>
                        <div class="text-center sm:text-left min-w-0">
                            <p class="summary-label">Publishing Flow</p>
                            <p class="summary-value">Draft your message</p>
                            <p class="summary-subtext">Configure content, audience targeting, and active dates.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-error bg-[#fdf2f2] border-[#b91c1c]/30 text-[#b91c1c]">
                <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="panel-card">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-pen-ruler text-[9px] sm:text-xs"></i></div>
                <div>
                    <h2 class="panel-title">Announcement Form</h2>
                    <p class="panel-subtitle hidden sm:block">Complete each section carefully to create a polished announcement.</p>
                </div>
            </div>

            <div class="p-3 sm:p-4">
                <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Content Section -->
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-file-alt text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Announcement Content</h3>
                                <p class="section-subtitle hidden sm:block">Set the main message and optional visual cover for the announcement.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 gap-3 sm:gap-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="field-label">Title <span class="text-[#b91c1c]">*</span></label>
                                <input type="text" name="title" id="title"
                                       value="{{ old('title') }}"
                                       class="input-field"
                                       placeholder="Enter announcement title" required>
                                @error('title')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Content -->
                            <div>
                                <label for="content" class="field-label">Content <span class="text-[#b91c1c]">*</span></label>
                                <textarea name="content" id="content" rows="6"
                                          class="textarea-field"
                                          placeholder="Enter announcement content..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Announcement Image -->
                            <div>
                                <label for="image" class="field-label">Announcement Image (Optional)</label>

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
                        </div>
                    </div>

                    <!-- Target Audience Section -->
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-users-viewfinder text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Target Audience</h3>
                                <p class="section-subtitle hidden sm:block">Choose the colleges and year levels that should be able to see this announcement.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 gap-3 sm:gap-4">
                            <!-- College Targeting -->
                            <div>
                                <label class="field-label">College Availability <span class="text-[#b91c1c]">*</span></label>
                                <div class="mb-4 radio-group">
                                    <div class="radio-option">
                                        <input type="radio" id="for_all_colleges_true" name="for_all_colleges" value="1"
                                               {{ old('for_all_colleges', true) ? 'checked' : '' }}>
                                        <label for="for_all_colleges_true">
                                            <strong>All Colleges</strong> — Announcement visible to students from all colleges
                                        </label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="for_all_colleges_false" name="for_all_colleges" value="0"
                                               {{ old('for_all_colleges') === '0' ? 'checked' : '' }}>
                                        <label for="for_all_colleges_false">
                                            <strong>Specific Colleges</strong> — Choose which colleges can see this announcement
                                        </label>
                                    </div>
                                </div>

                                <!-- College Selection -->
                                <div id="colleges_selection" class="{{ old('for_all_colleges', true) ? 'hidden' : '' }}">
                                    <label class="field-label">Select Colleges <span class="text-[#b91c1c]">*</span></label>
                                    <div class="college-grid">
                                        @foreach($colleges as $college)
                                            <div class="checkbox-option">
                                                <input type="checkbox" id="college_{{ $college->id }}" name="colleges[]"
                                                       value="{{ $college->id }}"
                                                       {{ in_array($college->id, old('colleges', [])) ? 'checked' : '' }}>
                                                <label for="college_{{ $college->id }}">{{ $college->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('colleges')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Year Level Targeting -->
                            <div>
                                <label class="field-label">Year Level Availability <span class="text-[#b91c1c]">*</span></label>
                                <div class="mb-4 radio-group">
                                    <div class="radio-option">
                                        <input type="radio" id="for_all_year_levels_true" name="for_all_year_levels" value="1"
                                               {{ empty(old('year_levels', [])) ? 'checked' : '' }}>
                                        <label for="for_all_year_levels_true">
                                            <strong>All Year Levels</strong> — Visible to students from all year levels
                                        </label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="for_all_year_levels_false" name="for_all_year_levels" value="0"
                                               {{ !empty(old('year_levels', [])) ? 'checked' : '' }}>
                                        <label for="for_all_year_levels_false">
                                            <strong>Specific Year Levels</strong> — Choose which year levels can see this announcement
                                        </label>
                                    </div>
                                </div>

                                <div id="year_levels_selection" class="{{ !empty(old('year_levels', [])) ? '' : 'hidden' }}">
                                    <label class="field-label">Select Year Levels <span class="text-[#b91c1c]">*</span></label>
                                    <div class="college-grid" style="max-height:none;">
                                        @php $oldYearLevels = old('year_levels', []); @endphp
                                        @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $yl)
                                        <div class="checkbox-option">
                                            <input type="checkbox" id="yl_ann_{{ Str::slug($yl) }}" name="year_levels[]"
                                                   value="{{ $yl }}"
                                                   {{ in_array($yl, $oldYearLevels) ? 'checked' : '' }}>
                                            <label for="yl_ann_{{ Str::slug($yl) }}">{{ $yl }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('year_levels')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scheduling and Options Section -->
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-clock text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Scheduling & Options</h3>
                                <p class="section-subtitle hidden sm:block">Determine when this announcement goes live and its active status.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="field-label">Start Date (Optional)</label>
                                <input type="date" name="start_date" id="start_date"
                                       value="{{ old('start_date') }}"
                                       class="input-field">
                                @error('start_date')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                                <p class="helper-text">Leave empty to start immediately</p>
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="field-label">End Date (Optional)</label>
                                <input type="date" name="end_date" id="end_date"
                                       value="{{ old('end_date') }}"
                                       class="input-field">
                                @error('end_date')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                                <p class="helper-text">Leave empty for no end date</p>
                            </div>

                            <!-- Active Status -->
                            <div class="sm:col-span-2">
                                <div class="option-card">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label for="is_active" class="ml-2 font-medium">
                                        Activate this announcement immediately
                                    </label>
                                </div>
                                @error('is_active')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-2">
                        <a href="{{ route('admin.announcements.index') }}"
                           class="form-action-secondary">
                            <i class="fas fa-times mr-2 text-[9px] sm:text-xs"></i> Cancel
                        </a>
                        <button type="submit"
                                class="form-action-primary">
                            <i class="fas fa-save mr-2 text-[9px] sm:text-xs"></i> Create Announcement
                        </button>
                    </div>
                </form>
            </div>
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

    // Year level selection toggle
    const allYearLevelsRadio = document.getElementById('for_all_year_levels_true');
    const specificYearLevelsRadio = document.getElementById('for_all_year_levels_false');
    const yearLevelsSelection = document.getElementById('year_levels_selection');

    function toggleYearLevelsSelection() {
        if (specificYearLevelsRadio.checked) {
            yearLevelsSelection.classList.remove('hidden');
        } else {
            yearLevelsSelection.classList.add('hidden');
            document.querySelectorAll('input[name="year_levels[]"]').forEach(cb => cb.checked = false);
        }
    }

    allYearLevelsRadio.addEventListener('change', toggleYearLevelsSelection);
    specificYearLevelsRadio.addEventListener('change', toggleYearLevelsSelection);

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
