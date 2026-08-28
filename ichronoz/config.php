<?php

declare(strict_types=1);

return [
    'api_base' => 'https://api.ichronoz.net',
    'api_key' => getenv('ICHRONOZ_API_KEY') ?: 'e972a19b09ea90cf3ff34ec12359bd7b1',
    // Leave empty to derive the Referer from the current installation URL.
    'api_referer' => getenv('ICHRONOZ_API_REFERER') ?: '',
    'timezone' => 'Asia/Jakarta',
    'site_name' => 'iChronoz Booking Engine',
    // Leave empty when this directory is located below the web document root.
    'public_path' => '',
    // Set this to the page containing booking-list.php when embedding in an existing site.
    'booking_path' => '/book.php',
    'layout' => 'horizontal',
    'room_card_type' => 'default',
    'colors' => [
        'selected_day' => '#0071c2',
        'search_button' => '#1566d1',
        'room_hover' => '#e6e6e6',
        'secondary' => '#6c757d',
        'success' => '#198754',
        'warning' => '#ffc107',
        'link' => '#1566d1',
        'calendar_range' => '#e3f2ff',
    ],
    'hid_enabled' => false,
    'hid_options' => [],
];
