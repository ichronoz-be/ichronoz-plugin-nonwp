<?php

declare(strict_types=1);

$config = require __DIR__ . '/config.php';

const ICHRONOZ_MAX_REQUEST_BYTES = 1048576;

function ichronoz_api_error(int $status, string $message): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode(['error' => $message], JSON_UNESCAPED_SLASHES);
    exit;
}

function ichronoz_api_route(): string
{
    $pathInfo = isset($_SERVER['PATH_INFO']) && is_string($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $route = trim($pathInfo, '/');

    if ($route === '' && isset($_GET['route']) && is_string($_GET['route'])) {
        $route = trim($_GET['route'], '/');
    }

    return $route;
}

function ichronoz_installation_referer(): string
{
    $https = strtolower((string) ($_SERVER['HTTPS'] ?? ''));
    $scheme = ($https !== '' && $https !== 'off') || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443
        ? 'https'
        : 'http';

    $host = trim((string) ($_SERVER['HTTP_HOST'] ?? ''));
    if ($host === '' || preg_match('/^(?:[A-Za-z0-9.-]+|\[[0-9A-Fa-f:]+\])(?::[0-9]{1,5})?$/', $host) !== 1) {
        $serverName = trim((string) ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        $host = preg_match('/^[A-Za-z0-9.-]+$/', $serverName) === 1 ? $serverName : 'localhost';
        $port = (int) ($_SERVER['SERVER_PORT'] ?? 0);
        if ($port > 0 && $port !== 80 && $port !== 443) {
            $host .= ':' . $port;
        }
    }

    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/api.php'));
    $basePath = rtrim(dirname($scriptName), '/.');
    $path = $basePath === '' ? '/' : '/' . ltrim($basePath, '/') . '/';

    return $scheme . '://' . $host . $path;
}

function ichronoz_normalize_response(string $body): array
{
    $trimmed = trim(preg_replace('/^\xEF\xBB\xBF/', '', $body) ?? $body);
    $candidate = $trimmed;

    if (preg_match('/^(?:[A-Za-z_$][A-Za-z0-9_$]*\s*)?\(\s*(.*)\s*\)\s*;?$/s', $trimmed, $matches) === 1) {
        $candidate = trim($matches[1]);
    }

    json_decode($candidate, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return [$candidate, 'application/json; charset=utf-8'];
    }

    return [$body, 'text/plain; charset=utf-8'];
}

$routes = [
    'availabilityV27/v3' => 'GET',
    'availability/guest/10' => 'GET',
    'exchange' => 'GET',
    'availability/create/posts' => 'POST',
];

$route = ichronoz_api_route();
$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

if (!isset($routes[$route])) {
    ichronoz_api_error(404, 'Unknown iChronoz API route.');
}

if ($routes[$route] !== $method) {
    header('Allow: ' . $routes[$route]);
    ichronoz_api_error(405, 'Method not allowed for this route.');
}

$apiKey = trim((string) ($config['api_key'] ?? ''));
$upstreamBase = rtrim((string) ($config['api_base'] ?? ''), '/');
if ($apiKey === '' || $upstreamBase === '') {
    ichronoz_api_error(500, 'The iChronoz API is not configured.');
}

$contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
if ($contentLength > ICHRONOZ_MAX_REQUEST_BYTES) {
    ichronoz_api_error(413, 'Request body is too large.');
}

if ($method === 'POST') {
    $rawBody = file_get_contents('php://input');
    if ($rawBody === false || strlen($rawBody) > ICHRONOZ_MAX_REQUEST_BYTES) {
        ichronoz_api_error(400, 'Unable to read the request body.');
    }
    parse_str($rawBody, $parameters);
} else {
    $parameters = $_GET;
}

if (!is_array($parameters)) {
    $parameters = [];
}

unset($parameters['route'], $parameters['api'], $parameters['callback']);
$parameters['api'] = $apiKey;
if ($route === 'availabilityV27/v3' && !array_key_exists('url', $parameters)) {
    // Retained for compatibility with legacy API keys.
    $parameters['url'] = '';
}

$upstreamUrl = $upstreamBase . '/' . $route;
$encodedParameters = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
if ($method === 'GET' && $encodedParameters !== '') {
    $upstreamUrl .= '?' . $encodedParameters;
}

$curl = curl_init($upstreamUrl);
if ($curl === false) {
    ichronoz_api_error(500, 'Unable to initialize the API client.');
}

$curlOptions = [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 45,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_MAXREDIRS => 0,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; iChronoz Plain PHP Adapter/1.0)',
    CURLOPT_HTTPHEADER => ['Accept: application/json, text/plain;q=0.9'],
];

$apiReferer = trim((string) ($config['api_referer'] ?? ''));
$curlOptions[CURLOPT_REFERER] = $apiReferer !== '' ? $apiReferer : ichronoz_installation_referer();

if ($method === 'POST') {
    $curlOptions[CURLOPT_POST] = true;
    $curlOptions[CURLOPT_POSTFIELDS] = $encodedParameters;
    $curlOptions[CURLOPT_HTTPHEADER][] = 'Content-Type: application/x-www-form-urlencoded';
}

curl_setopt_array($curl, $curlOptions);
$body = curl_exec($curl);
$curlError = curl_error($curl);
$status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
curl_close($curl);

if (!is_string($body)) {
    ichronoz_api_error(502, $curlError !== '' ? 'The iChronoz API could not be reached.' : 'Invalid API response.');
}

[$responseBody, $contentType] = ichronoz_normalize_response($body);

http_response_code($status >= 100 && $status <= 599 ? $status : 502);
header('Content-Type: ' . $contentType);
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
echo $responseBody;
