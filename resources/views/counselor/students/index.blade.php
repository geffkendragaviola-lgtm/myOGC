@extends('layouts.app')

@section('title', 'Students - Counselor')

@section('content')
<div class="min-h-screen bg-[#faf8f5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <div class="mb-5 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420]">Students</h1>
                    <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">All students in your assigned college(s).</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#e5e0db] rounded-xl overflow-hidden mb-5 sm:mb-6">
            <div class="px-4 sm:px-5 py-3 border-b border-[#e5e0db]/60 bg-[#faf8f5]/40">
                <form method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 min-w-0">
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search student ID, name, email, course..."
                               class="w-full px-3 py-2.5 rounded-lg border border-[#e5e0db] bg-white text-xs sm:text-sm">
                    </div>

                    <div class="w-full md:w-60 min-w-0">
                        <select name="college" class="w-full px-3 py-2.5 rounded-lg border border-[#e5e0db] bg-white text-xs sm:text-sm">
                            <option value="" {{ empty($college) ? 'selected' : '' }}>All Assigned Colleges</option>
                            @foreach($colleges as $c)
                                <option value="{{ $c->id }}" {{ (string)($college ?? '') === (string)$c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="w-full md:w-auto px-4 py-2.5 rounded-lg bg-[#5c1a1a] text-white text-xs sm:text-sm font-medium">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[850px] md:min-w-full divide-y divide-[#e5e0db]/60 w-full">
                    <thead class="bg-[#faf8f5]/85">
                        <tr>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Student</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Student ID</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">College</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Course / Year</th>
                            <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Last Session</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                        @forelse($students as $student)
                            @php
                                $stressResponses = $student->needsAssessment?->stress_responses ?? [];
                                $stressResponses = is_array($stressResponses) ? $stressResponses : [];
                                $riskResponses = ['Hurt myself', 'Attempted to end my life', 'Thought it would be better dead'];
                                $hasSelfHarmRisk = !$student->high_risk_overridden
                                    && count(array_intersect($riskResponses, $stressResponses)) > 0;
                                $isHighRisk = $student->is_high_risk || $hasSelfHarmRisk;
                            @endphp
                            <tr class="cursor-pointer hover:bg-[#faf8f5] {{ $isHighRisk ? 'bg-red-50' : '' }}" onclick="window.location='{{ route('counselor.students.profile', $student) }}'">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full overflow-hidden flex-shrink-0 bg-[#f0ebe5] flex items-center justify-center {{ $isHighRisk ? 'ring-2 ring-red-500' : '' }}">
                                            @if($student->profile_picture)
                                                <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs font-bold text-[#7a2a2a]">
                                                    {{ strtoupper(substr($student->user->first_name, 0, 1)) }}{{ strtoupper(substr($student->user->last_name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-[#2c2420] truncate">
                                                {{ $student->user->first_name }} {{ $student->user->last_name }}
                                            </div>
                                            <div class="text-[11px] text-[#8b7e76] font-mono truncate max-w-xs">
                                                {{ $student->user->email }}
                                            </div>
                                            @if($isHighRisk)
                                                <div class="mt-1 flex flex-wrap gap-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700 border border-red-200">
                                                        <i class="fas fa-exclamation-triangle text-[9px] mr-1"></i> High-risk individuals
                                                    </span>
                                                    @if($student->is_high_risk)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-orange-100 text-orange-700 border border-orange-200">
                                                            <i class="fas fa-flag text-[9px] mr-1"></i> Counselor Flagged
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-xs font-mono text-[#6b5e57]">{{ $student->student_id }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-[#2c2420] truncate max-w-[220px]">{{ $student->college->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-[#2c2420] truncate max-w-[220px]">{{ $student->course }}</div>
                                    <div class="text-[11px] text-[#8b7e76]">Year {{ $student->year_level }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-xs text-[#6b5e57]">
                                        {{ $student->lastSessionNote?->session_date?->format('M j, Y') ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center">
                                    <p class="text-sm text-[#8b7e76]">No students found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="px-4 sm:px-5 py-3 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
