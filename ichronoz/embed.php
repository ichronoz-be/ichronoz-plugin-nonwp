<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/app.php';

if (!function_exists('ichronoz_show_search_form')) {
    function ichronoz_show_search_form(): void
    {
        ichronoz_render_embed('search');
    }
}

if (!function_exists('ichronoz_show_booking_list')) {
    function ichronoz_show_booking_list(): void
    {
        ichronoz_render_embed('booking');
    }
}
