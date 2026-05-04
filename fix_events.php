<?php
$file = 'resources/views/student/events/available-events.blade.php';
$content = file_get_contents($file);

// 1. Update CSS
$content = preg_replace(
    '/\.event-image \{\s*position: relative; height: 10rem; overflow: hidden;/',
    '.event-image {
        position: relative; height: 18rem; overflow: hidden;',
    $content
);

// Add 'group cursor-pointer' and 'onclick' to '.event-card'
$oldCardStart = '<div class="event-card"
                         data-type="{{ $event->type }}"
                         data-required="{{ $isRequiredEvent ? \'true\' : \'false\' }}"
                         data-registered="{{ $isRegistered ? \'true\' : \'false\' }}"
                         data-available="{{ $canRegister ? \'true\' : \'false\' }}">';

$newCardStart = '<div class="event-card group cursor-pointer"
                         data-type="{{ $event->type }}"
                         data-required="{{ $isRequiredEvent ? \'true\' : \'false\' }}"
                         data-registered="{{ $isRegistered ? \'true\' : \'false\' }}"
                         data-available="{{ $canRegister ? \'true\' : \'false\' }}"
                         onclick="openEventModal({
                            title: `{{ addslashes($event->title) }}`,
                            type: `{{ addslashes($event->event_type) }}`,
                            dateRange: `{{ \Carbon\Carbon::parse($event->event_start_date)->format(\'M d, Y\') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format(\'M d, Y\') }}`,
                            timeRange: `{{ addslashes($event->time_range) }}`,
                            location: `{{ addslashes($event->location) }}`,
                            description: `{{ addslashes($event->description) }}`,
                            imageUrl: `{{ $event->image_path ? asset(\'storage/\' . $event->image_path) : \'\' }}`,
                            maxAttendees: {{ $event->max_attendees ?? \'null\' }},
                            registeredCount: {{ $event->registered_count ?? 0 }},
                            isRequired: {{ $isRequiredEvent ? \'true\' : \'false\' }},
                            isRegistered: {{ $isRegistered ? \'true\' : \'false\' }}
                        })">';

$content = str_replace($oldCardStart, $newCardStart, $content);

// Remove the Details button block completely
$detailsButtonBlockRegex = '/<!-- View Details Button -->.*?<\/button>/s';
$content = preg_replace($detailsButtonBlockRegex, '', $content);

// Add onclick="event.stopPropagation()" to forms and spans inside Action Buttons
$content = preg_replace(
    '/<form action="([^"]+)" method="POST" class="flex-1 min-w-\[100px\]">/',
    '<form action="$1" method="POST" class="flex-1 min-w-[100px]" onclick="event.stopPropagation()">',
    $content
);

// We should also replace the hover effect on image
$content = preg_replace(
    '/\.event-card:hover \.event-image img \{ transform: scale\(1\.03\); \}/',
    '.event-card:hover .event-image img { transform: scale(1.08); }',
    $content
);

file_put_contents($file, $content);
echo "Updated available-events.blade.php layout.\n";
