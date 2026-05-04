<?php
$file = 'resources/views/counselor/appointments/appointments.blade.php';
$content = file_get_contents($file);

$blockToRemove = "                calendarStatus.textContent = message;
                calendarStatus.classList.remove('text-gray-500', 'text-green-600', 'text-red-600');
                if (tone === 'success') {
                    calendarStatus.classList.add('text-green-600');
                } else if (tone === 'error') {
                    calendarStatus.classList.add('text-red-600');
                } else {
                    calendarStatus.classList.add('text-gray-500');
                }
            }";

$content = str_replace($blockToRemove, "", $content);

// Also with carriage returns in case
$blockToRemove2 = "                calendarStatus.textContent = message;\r\n                calendarStatus.classList.remove('text-gray-500', 'text-green-600', 'text-red-600');\r\n                if (tone === 'success') {\r\n                    calendarStatus.classList.add('text-green-600');\r\n                } else if (tone === 'error') {\r\n                    calendarStatus.classList.add('text-red-600');\r\n                } else {\r\n                    calendarStatus.classList.add('text-gray-500');\r\n                }\r\n            }";
$content = str_replace($blockToRemove2, "", $content);

file_put_contents($file, $content);
echo "Fixed JS syntax errors.\n";
