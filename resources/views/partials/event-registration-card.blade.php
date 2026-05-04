@php
    $event = $registration->event;
    $student = Auth::user()->student;
    $isRequiredEvent = $event->is_required && $event->isRequiredForStudent($student);
@endphp

<div class="event-card-new flex flex-col h-full" style="{{ $isRequiredEvent ? 'border-top: 3px solid #ef4444;' : 'border-top: 3px solid #059669;' }}">
    <!-- Event Header -->
    <div class="event-banner flex-shrink-0">
        @if($event->image_url)
            <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                 onerror="this.style.display='none'">
        @endif
        <div class="event-banner-overlay"></div>

        <!-- Status badge top-right -->
        <div class="event-banner-top">
            <span class="status-badge status-registered">
                Registered
            </span>
        </div>

        <!-- Title + type bottom -->
        <div class="event-banner-content">
            <div class="flex flex-wrap gap-1 mb-1.5">
                <span class="event-type-pill">{{ $event->type }}</span>
                @if($isRequiredEvent)
                    <span class="event-type-pill" style="background:rgba(220,38,38,0.9); border-color:rgba(220,38,38,0.5);">
                        <i class="fas fa-circle-exclamation text-[8px] mr-0.5"></i> Required
                    </span>
                @endif
            </div>
            <h3 class="text-sm sm:text-base font-semibold leading-tight line-clamp-2">{{ $event->title }}</h3>
            <p class="text-[10px] sm:text-xs text-white/80 mt-0.5 truncate">
                @if($event->for_all_colleges)
                    <i class="fas fa-globe mr-1"></i> All Colleges
                @else
                    <i class="fas fa-building-columns mr-1"></i> {{ $event->colleges->count() }} college(s)
                @endif
            </p>
        </div>
    </div>

    <!-- Card Body -->
    <div class="p-3.5 flex-1 flex flex-col">
        <div class="space-y-2 mb-3">
            <div class="event-meta-row">
                <div class="event-meta-icon bg-[#fef9e7] text-[10px] sm:text-xs text-[#c9a227]"><i class="fas fa-calendar-days"></i></div>
                <span class="text-xs sm:text-sm font-medium">{{ $event->date_range }}</span>
            </div>
            <div class="event-meta-row">
                <div class="event-meta-icon bg-[#eff6ff] text-[10px] sm:text-xs text-sky-600"><i class="fas fa-clock"></i></div>
                <span class="text-[11px] sm:text-xs">{{ $event->time_range }}</span>
            </div>
            <div class="event-meta-row">
                <div class="event-meta-icon bg-[#fdf2f2] text-[10px] sm:text-xs text-[#b91c1c]"><i class="fas fa-location-dot"></i></div>
                <span class="text-[11px] sm:text-xs truncate" title="{{ $event->location }}">{{ $event->location }}</span>
            </div>
            <div class="event-meta-row">
                <div class="event-meta-icon bg-[#f0fdf4] text-[10px] sm:text-xs text-[#16a34a]"><i class="fas fa-calendar-check"></i></div>
                <span class="text-[11px] sm:text-xs">Registered: {{ $registration->registered_at->format('M j, Y') }}</span>
            </div>
        </div>

        <p class="text-[11px] sm:text-xs text-[#6b5e57] line-clamp-2 leading-relaxed mb-3 flex-1">
            {{ Str::limit($event->description, 120) }}
        </p>

        <!-- Action Buttons -->
        <div class="mt-auto pt-3 border-t border-[#e5e0db] flex justify-between gap-2">
            <button onclick="toggleDetails('details-{{ $registration->id }}')" class="action-btn-soft bg-[#faf8f5] text-[#6b5e57] border border-[#e5e0db] flex-1">
                <i class="fas fa-circle-info mr-1.5"></i> Details
            </button>
            
            @if($registration->status === 'registered' && $event->is_upcoming)
                @if($isRequiredEvent)
                    <button class="action-btn-soft bg-gray-100 text-gray-400 border border-gray-200 flex-1 cursor-not-allowed" disabled title="Required events cannot be cancelled">
                        <i class="fas fa-lock mr-1.5"></i> Cannot Cancel
                    </button>
                @else
                    <form action="{{ route('student.events.cancel', $event) }}" method="POST" class="flex-1 flex">
                        @csrf
                        <button type="submit" class="action-btn-soft bg-[#fdf2f2] text-[#b91c1c] border border-[#fca5a5] w-full"
                                onclick="return confirm('Are you sure you want to cancel your registration for this event?')">
                            <i class="fas fa-xmark mr-1.5"></i> Cancel
                        </button>
                    </form>
                @endif
            @endif
        </div>
        
        <!-- Expandable Details -->
        <div id="details-{{ $registration->id }}" class="hidden mt-3 pt-3 border-t border-dashed border-[#e5e0db]">
            <div class="space-y-3">
                <!-- Full Description -->
                <div>
                    <p class="text-[11px] font-semibold text-[#6b5e57] uppercase tracking-wider mb-1">Description</p>
                    <p class="text-[11px] sm:text-xs text-[#2c2420] leading-relaxed">{{ $event->description }}</p>
                </div>

                <!-- Event Requirements Information -->
                @if($isRequiredEvent)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-2.5">
                        <div class="flex items-center">
                            <i class="fas fa-circle-info text-red-500 text-[10px] mr-1.5"></i>
                            <span class="text-red-800 font-medium text-[11px]">Required Event</span>
                        </div>
                        <p class="text-red-700 text-[10px] mt-1 leading-relaxed">
                            This event is required for your college. Attendance is mandatory and registration cannot be cancelled.
                        </p>
                    </div>
                @endif

                <!-- Cancellation Info for Optional Events -->
                @if(!$isRequiredEvent && $event->is_upcoming && $registration->status === 'registered')
                    <div class="bg-[#fffbeb] border border-[#fde68a] rounded-xl p-3">
                        <div class="flex items-start gap-2.5">
                            <div class="h-7 w-7 rounded-lg bg-white/70 border border-[#fde68a] text-[#d97706] flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shield-halved text-[11px]"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[#92400e] font-semibold text-[11px]">Cancellation Policy</span>
                                    @if($event->isCancellationAllowed())
                                        <span class="inline-flex items-center gap-1 rounded-full bg-white/80 border border-[#fde68a] px-2 py-0.5 text-[10px] font-semibold text-[#92400e]">
                                            <i class="fas fa-clock text-[9px]"></i>
                                            <span>{{ $event->getCancellationCutoffTime() }}</span>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[#b45309] text-[10px] mt-1 leading-relaxed">
                                    You can cancel your registration up to 24 hours before the event starts.
                                </p>
                                <div class="mt-2 grid grid-cols-1 gap-1.5 text-[10px] text-[#92400e]">
                                    <div class="flex items-center gap-1.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#d97706]"></span>
                                        <span>Late cancellations may be denied.</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#d97706]"></span>
                                        <span>Check the cutoff time before cancelling.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
