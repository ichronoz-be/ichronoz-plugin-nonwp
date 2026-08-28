<?php

declare(strict_types=1);

function ichronoz_config(): array
{
    static $config;

    if ($config === null) {
        $config = require dirname(__DIR__) . '/config.php';
        date_default_timezone_set((string) ($config['timezone'] ?? 'UTC'));
    }

    return $config;
}

function ichronoz_base_path(): string
{
    $configuredPath = trim((string) (ichronoz_config()['public_path'] ?? ''));
    if ($configuredPath !== '') {
        return $configuredPath === '/' ? '' : '/' . trim($configuredPath, '/');
    }

    $documentRoot = realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $packageRoot = realpath(dirname(__DIR__));
    if ($documentRoot !== false && $packageRoot !== false) {
        $documentRoot = rtrim(str_replace('\\', '/', $documentRoot), '/');
        $packageRoot = str_replace('\\', '/', $packageRoot);
        if ($packageRoot === $documentRoot) {
            return '';
        }
        if (str_starts_with($packageRoot, $documentRoot . '/')) {
            return '/' . ltrim(substr($packageRoot, strlen($documentRoot)), '/');
        }
    }

    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $directory = rtrim(dirname($scriptName), '/.');

    return $directory === '' ? '' : '/' . ltrim($directory, '/');
}

function ichronoz_url(string $path): string
{
    return ichronoz_base_path() . '/' . ltrim($path, '/');
}

function ichronoz_date_param(string $name, DateTimeImmutable $fallback): DateTimeImmutable
{
    $value = isset($_GET[$name]) && is_string($_GET[$name]) ? $_GET[$name] : '';
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    $errors = DateTimeImmutable::getLastErrors();

    if ($date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
        return $fallback;
    }

    return $date;
}

function ichronoz_int_param(string $name, int $default, int $minimum, int $maximum): int
{
    $value = filter_input(INPUT_GET, $name, FILTER_VALIDATE_INT);
    if ($value === false || $value === null) {
        return $default;
    }

    return max($minimum, min($maximum, $value));
}

function ichronoz_string_param(string $name, int $maximumLength = 100): string
{
    $value = isset($_GET[$name]) && is_string($_GET[$name]) ? trim($_GET[$name]) : '';

    return substr($value, 0, $maximumLength);
}

function ichronoz_booking_data(): array
{
    $today = new DateTimeImmutable('today');
    $from = ichronoz_date_param('from', $today->modify('+1 day'));
    $to = ichronoz_date_param('to', $from->modify('+1 day'));

    if ($to <= $from) {
        $to = $from->modify('+1 day');
    }

    return [
        'from' => $from->format('Y-m-d'),
        'to' => $to->format('Y-m-d'),
        'rooms' => ichronoz_int_param('rooms', 1, 1, 9),
        'adults' => ichronoz_int_param('adults', 2, 1, 20),
        'children' => ichronoz_int_param('children', 0, 0, 20),
        'utc' => ichronoz_string_param('utc', 50),
    ];
}

function ichronoz_frontend_settings(): array
{
    $config = ichronoz_config();
    $colors = is_array($config['colors'] ?? null) ? $config['colors'] : [];

    return [
        'apiBase' => ichronoz_url('api.php'),
        'layout' => $config['layout'] ?? 'horizontal',
        'selectedDayColor' => $colors['selected_day'] ?? '#0071c2',
        'searchButtonColor' => $colors['search_button'] ?? '#1566d1',
        'roomHoverBgColor' => $colors['room_hover'] ?? '#e6e6e6',
        'roomCardType' => $config['room_card_type'] ?? 'default',
        'secondaryColor' => $colors['secondary'] ?? '#6c757d',
        'successColor' => $colors['success'] ?? '#198754',
        'warningColor' => $colors['warning'] ?? '#ffc107',
        'linkColor' => $colors['link'] ?? '#1566d1',
        // A non-secret marker keeps all bundle features enabled. api.php replaces it.
        'apiValue' => 'proxy',
        'bookingPath' => trim((string) ($config['booking_path'] ?? '')) ?: ichronoz_url('book.php'),
        'loadingMessage' => 'Searching for the best rate within your requested period: {fromLong} - {toShort}',
        'spinnerUrl' => ichronoz_url('assets/spinner.svg'),
        'calendarRangeBg' => $colors['calendar_range'] ?? '#e3f2ff',
        'bookingPageScript' => '',
        'detailPageScript' => '',
        'scriptNonce' => '',
        'hidEnabled' => (bool) ($config['hid_enabled'] ?? false),
        'hidOptions' => array_values(is_array($config['hid_options'] ?? null) ? $config['hid_options'] : []),
        'gradientColors' => [],
        'fxRates' => [],
    ];
}

function ichronoz_json(mixed $value): string
{
    return (string) json_encode(
        $value,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR
    );
}

function ichronoz_render_embed(string $mount): void
{
    static $runtimeRendered = false;

    $mount = $mount === 'booking' ? 'booking' : 'search';
    $renderRuntime = !$runtimeRendered;
    $runtimeRendered = true;

    if ($renderRuntime) {
        ?>
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
<link rel="stylesheet" href="<?= htmlspecialchars(ichronoz_url('assets/ichronoz-bootstrap.min.css'), ENT_QUOTES, 'UTF-8') ?>">
<link rel="stylesheet" href="<?= htmlspecialchars(ichronoz_url('build/style-index.css'), ENT_QUOTES, 'UTF-8') ?>">
<link rel="stylesheet" href="<?= htmlspecialchars(ichronoz_url('build/index.css'), ENT_QUOTES, 'UTF-8') ?>">
        <?php
    }
    ?>
<div class="ichronoz">
    <div data-ichronoz-mount="<?= $mount ?>"></div>
</div>
    <?php
    if ($renderRuntime) {
        $settings = ichronoz_frontend_settings();
        $bookingData = ichronoz_booking_data();
        ?>
<script>
    window.ichronozSettings = <?= ichronoz_json($settings) ?>;
    window.ichronozBookingData = <?= ichronoz_json($bookingData) ?>;
</script>
<script defer src="<?= htmlspecialchars(ichronoz_url('vendor/react.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
<script defer src="<?= htmlspecialchars(ichronoz_url('vendor/react-dom.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
<script defer src="<?= htmlspecialchars(ichronoz_url('vendor/react-jsx-runtime.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
<script defer src="<?= htmlspecialchars(ichronoz_url('assets/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
<script defer src="<?= htmlspecialchars(ichronoz_url('build/index.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
        <?php
    }
}

function ichronoz_render_page(string $mount, string $title): void
{
    $config = ichronoz_config();
    $settings = ichronoz_frontend_settings();
    $bookingData = ichronoz_booking_data();
    $siteName = (string) ($config['site_name'] ?? 'iChronoz Booking Engine');
    $mount = $mount === 'booking' ? 'booking' : 'search';
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($title . ' | ' . $siteName, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(ichronoz_url('assets/ichronoz-bootstrap.min.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(ichronoz_url('build/style-index.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(ichronoz_url('build/index.css'), ENT_QUOTES, 'UTF-8') ?>">
    <style>
        html, body { min-height: 100%; }
        body { margin: 0; background: #f5f7fa; }
        .ichronoz-shell { width: min(1180px, calc(100% - 24px)); margin: 24px auto; }
    </style>
</head>
<body>
    <main class="ichronoz-shell">
        <div class="ichronoz">
            <div data-ichronoz-mount="<?= $mount ?>"></div>
        </div>
    </main>

    <script>
        window.ichronozSettings = <?= ichronoz_json($settings) ?>;
        window.ichronozBookingData = <?= ichronoz_json($bookingData) ?>;
    </script>
    <script defer src="<?= htmlspecialchars(ichronoz_url('vendor/react.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script defer src="<?= htmlspecialchars(ichronoz_url('vendor/react-dom.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script defer src="<?= htmlspecialchars(ichronoz_url('vendor/react-jsx-runtime.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script defer src="<?= htmlspecialchars(ichronoz_url('assets/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script defer src="<?= htmlspecialchars(ichronoz_url('build/index.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>
</html>
    <?php
}
