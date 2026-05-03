<?php

$file = 'resources/views/counselor/appointments/appointments.blade.php';
$content = file_get_contents($file);

// Replace the modal header
$oldHeader = '<div class="modal-header">
                    <div class="flex items-center gap-2">
                        <div class="modal-header-icon"><i class="fas fa-calendar-alt"></i></div>
                        <h3 class="modal-title">Reschedule Appointment</h3>
                    </div>
                    <button onclick="closeRescheduleModal()" class="modal-close" title="Close">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>';

$newHeader = '<div class="modal-header" style="background:linear-gradient(135deg,var(--maroon-800) 0%,var(--maroon-700) 100%);border-radius:0.75rem 0.75rem 0 0;padding:1.1rem 1.5rem;">
                    <div class="flex items-center gap-3">
                        <div style="width:2.25rem;height:2.25rem;border-radius:0.6rem;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-calendar-days text-sm" style="color:#fef9e7;"></i>
                        </div>
                        <div>
                            <h3 style="margin:0;font-size:0.95rem;font-weight:600;color:#fef9e7;">Reschedule Appointment</h3>
                            <p style="margin:0;font-size:0.7rem;color:rgba(254,249,231,0.7);">Select a new date and time</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeRescheduleModal()" class="modal-close" title="Close"
                            style="color:rgba(254,249,231,0.7);">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>';

$content = str_replace($oldHeader, $newHeader, $content);

// Replace the calendar status HTML
$oldStatusHtml = '<p id="rescheduleCalendarStatus" class="mt-3 text-[10px] sm:text-xs text-[#8b7e76]">
                                        Select a counselor to load available dates.
                                    </p>';

$newStatusHtml = '<div id="rescheduleCalendarStatus" class="mt-4">
                                        <div class="flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl bg-[#f5f0eb]/50 border border-[#e5e0db] text-[#6b5e57] font-semibold transition-all duration-300 w-full">
                                            <span class="text-xs sm:text-sm tracking-wide">Select a counselor to load available dates.</span>
                                        </div>
                                    </div>';

$content = str_replace($oldStatusHtml, $newStatusHtml, $content);

// Replace the setRescheduleCalendarStatus JS function
$oldJsPattern = '/function setRescheduleCalendarStatus\(message, tone = \'muted\'\) \{.*?\}/s';
$newJs = 'function setRescheduleCalendarStatus(msg, tone = \'muted\') {
                const calStatus = document.getElementById(\'rescheduleCalendarStatus\');
                if (!calStatus) return;
                
                let icon = \'\';
                let colorClass = \'text-[#6b5e57]\';
                let bgClass = \'bg-[#f5f0eb]/50 border border-[#e5e0db]\';
                let animateClass = \'\';
                
                if (msg === \'Checking available dates...\') {
                    icon = \'<i class="fas fa-circle-notch fa-spin text-base"></i>\';
                    colorClass = \'text-[#b48600]\';
                    bgClass = \'bg-[#fef9e7] border border-[#d4af37]/40 shadow-[0_2px_10px_rgba(212,175,55,0.15)]\';
                    animateClass = \'animate-pulse\';
                } else if (tone === \'success\' || msg.includes(\'Selected:\')) {
                    icon = \'<i class="fas fa-check-circle text-base"></i>\';
                    colorClass = \'text-[#065f46]\';
                    bgClass = \'bg-[#f0fdf4] border border-[#10b981]/40 shadow-[0_2px_10px_rgba(16,185,129,0.15)]\';
                } else if (tone === \'error\' || msg.includes(\'No available\')) {
                    icon = \'<i class="fas fa-exclamation-circle text-base"></i>\';
                    colorClass = \'text-[#b91c1c]\';
                    bgClass = \'bg-[#fef2f2] border border-[#ef4444]/40 shadow-[0_2px_10px_rgba(239,68,68,0.15)]\';
                } else if (msg.includes(\'Available dates\')) {
                    icon = \'<i class="fas fa-info-circle text-base"></i>\';
                    colorClass = \'text-[#0369a1]\';
                    bgClass = \'bg-[#f0f9ff] border border-[#0ea5e9]/40 shadow-[0_2px_10px_rgba(14,165,233,0.15)]\';
                }
                
                calStatus.innerHTML = `
                    <div class="flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl ${bgClass} ${colorClass} ${animateClass} font-semibold transition-all duration-300 transform w-full">
                        ${icon}
                        <span class="text-xs sm:text-sm tracking-wide">${msg}</span>
                    </div>
                `;
            }';

$content = preg_replace($oldJsPattern, $newJs, $content);

// What about the referral modal? It also has a calendar. Let's do the same for the referral modal.
// Referral Modal Header
$oldReferralHeader = '<div class="modal-header">
                    <div class="flex items-center gap-2">
                        <div class="modal-header-icon"><i class="fas fa-share-square"></i></div>
                        <h3 class="modal-title">Refer Appointment</h3>
                    </div>
                    <button onclick="closeReferralModal()" class="modal-close" title="Close">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>';

$newReferralHeader = '<div class="modal-header" style="background:linear-gradient(135deg,var(--maroon-800) 0%,var(--maroon-700) 100%);border-radius:0.75rem 0.75rem 0 0;padding:1.1rem 1.5rem;">
                    <div class="flex items-center gap-3">
                        <div style="width:2.25rem;height:2.25rem;border-radius:0.6rem;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-share-square text-sm" style="color:#fef9e7;"></i>
                        </div>
                        <div>
                            <h3 style="margin:0;font-size:0.95rem;font-weight:600;color:#fef9e7;">Refer Appointment</h3>
                            <p style="margin:0;font-size:0.7rem;color:rgba(254,249,231,0.7);">Refer student to another counselor</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeReferralModal()" class="modal-close" title="Close"
                            style="color:rgba(254,249,231,0.7);">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>';
$content = str_replace($oldReferralHeader, $newReferralHeader, $content);

// Referral Calendar Status HTML
$oldReferralStatusHtml = '<p id="referralCalendarStatus" class="mt-3 text-[10px] sm:text-xs text-[#8b7e76]">
                                        Select a counselor to load available dates.
                                    </p>';

$newReferralStatusHtml = '<div id="referralCalendarStatus" class="mt-4">
                                        <div class="flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl bg-[#f5f0eb]/50 border border-[#e5e0db] text-[#6b5e57] font-semibold transition-all duration-300 w-full">
                                            <span class="text-xs sm:text-sm tracking-wide">Select a counselor to load available dates.</span>
                                        </div>
                                    </div>';

$content = str_replace($oldReferralStatusHtml, $newReferralStatusHtml, $content);

// Referral Calendar Status JS
$oldReferralJsPattern = '/function setReferralCalendarStatus\(message, tone = \'muted\'\) \{.*?\}/s';
$newReferralJs = 'function setReferralCalendarStatus(msg, tone = \'muted\') {
                const calStatus = document.getElementById(\'referralCalendarStatus\');
                if (!calStatus) return;
                
                let icon = \'\';
                let colorClass = \'text-[#6b5e57]\';
                let bgClass = \'bg-[#f5f0eb]/50 border border-[#e5e0db]\';
                let animateClass = \'\';
                
                if (msg === \'Checking available dates...\') {
                    icon = \'<i class="fas fa-circle-notch fa-spin text-base"></i>\';
                    colorClass = \'text-[#b48600]\';
                    bgClass = \'bg-[#fef9e7] border border-[#d4af37]/40 shadow-[0_2px_10px_rgba(212,175,55,0.15)]\';
                    animateClass = \'animate-pulse\';
                } else if (tone === \'success\' || msg.includes(\'Selected:\')) {
                    icon = \'<i class="fas fa-check-circle text-base"></i>\';
                    colorClass = \'text-[#065f46]\';
                    bgClass = \'bg-[#f0fdf4] border border-[#10b981]/40 shadow-[0_2px_10px_rgba(16,185,129,0.15)]\';
                } else if (tone === \'error\' || msg.includes(\'No available\')) {
                    icon = \'<i class="fas fa-exclamation-circle text-base"></i>\';
                    colorClass = \'text-[#b91c1c]\';
                    bgClass = \'bg-[#fef2f2] border border-[#ef4444]/40 shadow-[0_2px_10px_rgba(239,68,68,0.15)]\';
                } else if (msg.includes(\'Available dates\')) {
                    icon = \'<i class="fas fa-info-circle text-base"></i>\';
                    colorClass = \'text-[#0369a1]\';
                    bgClass = \'bg-[#f0f9ff] border border-[#0ea5e9]/40 shadow-[0_2px_10px_rgba(14,165,233,0.15)]\';
                }
                
                calStatus.innerHTML = `
                    <div class="flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl ${bgClass} ${colorClass} ${animateClass} font-semibold transition-all duration-300 transform w-full">
                        ${icon}
                        <span class="text-xs sm:text-sm tracking-wide">${msg}</span>
                    </div>
                `;
            }';

$content = preg_replace($oldReferralJsPattern, $newReferralJs, $content);

file_put_contents($file, $content);
echo "Modals upgraded in appointments.blade.php.\n";
