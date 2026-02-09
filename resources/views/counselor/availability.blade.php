@extends('layouts.app')

@section('title', 'Availability & Booking Limits - OGC')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Availability &amp; Booking Limits</h1>
            <p class="text-gray-600 mt-1">Set your weekly availability, daily limits, and date overrides.</p>
        </div>
        <a href="{{ route('counselor.dashboard') }}"
           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    @if(session('status') === 'counselor-availability-updated')
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                Availability settings updated successfully.
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
            <div class="font-semibold mb-2">Please fix the following errors:</div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('counselor.availability.update') }}">
            @csrf
            @method('patch')

            <div class="mb-8">
                <label for="daily_booking_limit" class="block text-sm font-medium text-gray-700 mb-2">Daily Booking Limit</label>
                <input type="number" id="daily_booking_limit" name="daily_booking_limit" min="0" max="50"
                       value="{{ old('daily_booking_limit', $counselorProfile->daily_booking_limit ?? 3) }}"
                       class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <p class="text-xs text-gray-500 mt-1">Set how many students you can see per day.</p>
                @error('daily_booking_limit')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Weekly Availability</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($weekdays as $dayKey => $dayLabel)
                        @php
                            $slots = $availability[$dayKey] ?? [];
                            $isAvailable = !empty($slots);
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="flex items-center mb-2">
                                <input type="checkbox" name="availability_days[]" value="{{ $dayKey }}"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                       {{ $isAvailable ? 'checked' : '' }}>
                                <span class="ms-2 text-sm font-medium text-gray-700">{{ $dayLabel }}</span>
                            </label>
                            <input type="text" name="availability_slots[{{ $dayKey }}]"
                                   value="{{ old('availability_slots.' . $dayKey, implode(', ', $slots)) }}"
                                   placeholder="08:00-12:00, 13:00-17:00"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <p class="text-xs text-gray-500 mt-1">Use 24-hour format, comma-separated ranges.</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Schedule Overrides</h3>
                <p class="text-sm text-gray-600 mb-4">Close or open specific dates (e.g., whole-day interviews).</p>

                <div id="scheduleOverrides" data-next-index="{{ $existingOverrides->count() }}">
                    @foreach($existingOverrides as $index => $override)
                        <div class="override-row grid grid-cols-1 md:grid-cols-4 gap-3 items-end mb-3">
                            <input type="hidden" name="schedule_overrides[{{ $index }}][id]" value="{{ $override->id }}">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Date</label>
                                <input type="date" name="schedule_overrides[{{ $index }}][date]"
                                       value="{{ $override->date->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="schedule_overrides[{{ $index }}][status]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="open" {{ $override->is_closed ? '' : 'selected' }}>Open</option>
                                    <option value="closed" {{ $override->is_closed ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Time Slots</label>
                                <input type="text" name="schedule_overrides[{{ $index }}][time_slots]"
                                       value="{{ old('schedule_overrides.' . $index . '.time_slots', implode(', ', $override->time_slots ?? [])) }}"
                                       placeholder="08:00-12:00, 13:00-17:00"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="flex items-center text-sm text-gray-600">
                                    <input type="checkbox" name="schedule_overrides[{{ $index }}][remove]" value="1"
                                           class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    <span class="ms-2">Remove</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="addOverride"
                        class="mt-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition border border-blue-200">
                    Add Date Override
                </button>
                <p class="text-xs text-gray-500 mt-2">If opening a date outside your usual days, add time slots.</p>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-save mr-2"></i> Save Availability
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const overridesContainer = document.getElementById('scheduleOverrides');
        const addOverrideButton = document.getElementById('addOverride');

        if (overridesContainer && addOverrideButton) {
            addOverrideButton.addEventListener('click', () => {
                const nextIndex = parseInt(overridesContainer.dataset.nextIndex || '0', 10);
                const row = document.createElement('div');
                row.className = 'override-row grid grid-cols-1 md:grid-cols-4 gap-3 items-end mb-3';
                row.innerHTML = `
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Date</label>
                        <input type="date" name="schedule_overrides[${nextIndex}][date]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select name="schedule_overrides[${nextIndex}][status]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Time Slots</label>
                        <input type="text" name="schedule_overrides[${nextIndex}][time_slots]"
                               placeholder="08:00-12:00, 13:00-17:00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="remove-override text-sm text-red-600 hover:text-red-700">Remove</button>
                    </div>
                `;

                overridesContainer.appendChild(row);
                overridesContainer.dataset.nextIndex = (nextIndex + 1).toString();
            });

            overridesContainer.addEventListener('click', (event) => {
                const target = event.target;
                if (target && target.classList.contains('remove-override')) {
                    const row = target.closest('.override-row');
                    if (row) {
                        row.remove();
                    }
                }
            });
        }
    });
</script>
@endpush
