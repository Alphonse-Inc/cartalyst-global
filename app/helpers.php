<?php
declare(strict_types=1);

function env(string $key, mixed $default = null): mixed
{
    if (array_key_exists($key, $_ENV) && $_ENV[$key] !== '') {
        return $_ENV[$key];
    }
    $value = getenv($key);
    if ($value !== false && $value !== '') {
        return $value;
    }
    return $default;
}

function env_bool(string $key, bool $default = false): bool
{
    $value = env($key, $default ? 'true' : 'false');
    if (is_bool($value)) {
        return $value;
    }
    return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], true);
}

function load_env_file(string $path): void
{
    if (!is_readable($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        return;
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }
        $_ENV[$name] = $value;
        putenv($name . '=' . $value);
    }
}

function config(?string $key = null, mixed $default = null): mixed
{
    static $config;
    if ($config === null) {
        $file = CONFIG_PATH . '/config.php';
        if (!is_file($file)) {
            $file = CONFIG_PATH . '/config.example.php';
        }
        $config = require $file;
    }
    if ($key === null) {
        return $config;
    }
    $segments = explode('.', $key);
    $value = $config;
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function base_url(string $path = ''): string
{
    $base = rtrim((string) config('url'), '/');
    $path = ltrim($path, '/');
    return $path === '' ? $base : $base . '/' . $path;
}

function current_path(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    // Support installs in a subdirectory (e.g. /public)
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/.');
    if ($dir !== '' && $dir !== '/' && str_starts_with($uri, $dir)) {
        $uri = substr($uri, strlen($dir)) ?: '/';
    }
    if ($uri === '/index.php') {
        return '/';
    }
    return $uri === '' ? '/' : $uri;
}

function is_active(string $path): bool
{
    $current = current_path();
    if ($path === '/') {
        return $current === '/';
    }
    return $current === $path || str_starts_with($current, rtrim($path, '/') . '/');
}

function local_url(string $path = ''): string
{
    $path = ltrim($path, '/');
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir = rtrim(dirname($script), '/.');
    $prefix = ($dir === '' || $dir === '/' || $dir === '.') ? '' : $dir;
    return $prefix . '/' . $path;
}

function redirect(string $to, int $code = 302): never
{
    if (!str_starts_with($to, 'http')) {
        $to = local_url($to);
    }
    header('Location: ' . $to, true, $code);
    exit;
}

function request_method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function is_post(): bool
{
    return request_method() === 'POST';
}

function is_ajax(): bool
{
    $requested = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    return strcasecmp($requested, 'XMLHttpRequest') === 0
        || str_contains($accept, 'application/json');
}

function json_response(array $payload, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function old(string $key, mixed $default = ''): mixed
{
    $flashed = $_SESSION['_old'][$key] ?? null;
    if ($flashed !== null) {
        return $flashed;
    }
    return $_POST[$key] ?? $default;
}

function flash(string $key, mixed $value = null): mixed
{
    if ($value === null) {
        $data = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $data;
    }
    $_SESSION['_flash'][$key] = $value;
    return $value;
}

function errors(): array
{
    return flash('errors') ?? [];
}

function error(string $key): ?string
{
    $all = $_SESSION['_flash']['errors'] ?? [];
    return $all[$key] ?? null;
}

function client_ip(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

function ip_hash(): string
{
    return hash_hmac('sha256', client_ip(), (string) config('key'));
}

function setting(string $key, string $default = ''): string
{
    static $cache;
    if ($cache === null) {
        try {
            $cache = App\Models\Setting::all();
        } catch (Throwable) {
            $cache = [];
        }
    }
    return isset($cache[$key]) ? (string) $cache[$key] : $default;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(App\Csrf::token()) . '">';
}

function honeypot_field(): string
{
    return '<div class="hp-field" aria-hidden="true">'
        . '<label>Website<input type="text" name="website_url" tabindex="-1" autocomplete="off"></label>'
        . '</div>';
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'item';
}

function icon(string $name, string $class = 'w-6 h-6'): string
{
    $icons = [
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'x' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'check' => '<path d="M5 13l4 4L19 7"/>',
        'star' => '<path d="M12 3l2.6 5.3L20 9.2l-4 3.9.9 5.9L12 16.5 7.1 19l.9-5.9-4-3.9 5.4-.9L12 3z"/>',
        'chevron-right' => '<path d="M9 6l6 6-6 6"/>',
        'chevron-left' => '<path d="M15 6l-6 6 6 6"/>',
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'mail' => '<path d="M4 6h16v12H4z"/><path d="M4 7l8 6 8-6"/>',
        'phone' => '<path d="M6 3h4l2 5-3 2a12 12 0 006 6l2-3 5 2v4c0 1-1 2-2 2C10 21 3 14 3 5c0-1 1-2 3-2z"/>',
        'map-pin' => '<path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/>',
        'package' => '<path d="M3 8l9-5 9 5-9 5-9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/>',
        'truck' => '<path d="M3 7h11v10H3z"/><path d="M14 10h5l3 3v4h-8"/><circle cx="7" cy="18" r="2"/><circle cx="18" cy="18" r="2"/>',
        'shield' => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/>',
        'briefcase' => '<path d="M8 7V6a2 2 0 012-2h4a2 2 0 012 2v1"/><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M3 12h18"/>',
        'cpu' => '<rect x="7" y="7" width="10" height="10" rx="1"/><path d="M9 3v4M15 3v4M9 17v4M15 17v4M3 9h4M3 15h4M17 9h4M17 15h4"/>',
        'hard-hat' => '<path d="M4 14h16v3H4z"/><path d="M5 14a7 7 0 0114 0"/><path d="M12 7V4"/>',
        'heart-pulse' => '<path d="M4 10c0-3 2.5-5 5-3.5C10.5 5 12 7 12 7s1.5-2 3-1.5C17.5 5 20 7 20 10c0 5-8 10-8 10S4 15 4 10z"/>',
        'wrench' => '<path d="M14 7a4 4 0 105 5L10 21l-4-4 9-9"/>',
        'utensils' => '<path d="M6 3v8M4 3v5a2 2 0 004 0V3M10 3v18M18 3c0 4-2 6-2 10v8"/>',
        'zap' => '<path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
        'users' => '<circle cx="9" cy="8" r="3"/><path d="M3 19c0-3 2.5-5 6-5s6 2 6 5"/><circle cx="17" cy="9" r="2"/><path d="M21 19c0-2-1.5-4-4-4"/>',
        'quote' => '<path d="M7 11c-2 0-3 1.5-3 3.5S6 18 8 18s2.5-1 2.5-3c0-5-3-8-3-8M16 11c-2 0-3 1.5-3 3.5S15 18 17 18s2.5-1 2.5-3c0-5-3-8-3-8"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v6l4 2"/>',
        'file' => '<path d="M7 3h8l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/><path d="M15 3v5h5"/>',
        'upload' => '<path d="M12 16V6M8 10l4-4 4 4"/><path d="M4 20h16"/>',
        'lock' => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/>',
        'filter' => '<path d="M4 6h16M7 12h10M10 18h4"/>',
        'download' => '<path d="M12 4v12M8 12l4 4 4-4"/><path d="M4 20h16"/>',
        'trash' => '<path d="M4 7h16M9 7V5h6v2M8 7l1 13h6l1-13"/>',
        'edit' => '<path d="M4 20h4l10-10-4-4L4 16v4z"/><path d="M14 6l4 4"/>',
        'building' => '<path d="M4 21V5l8-2 8 2v16"/><path d="M9 9h.01M15 9h.01M9 13h.01M15 13h.01M9 17h.01M15 17h.01M4 21h16"/>',
        'layers' => '<path d="M12 3l9 5-9 5-9-5 9-5z"/><path d="M3 13l9 5 9-5"/><path d="M3 17l9 5 9-5"/>',
        'message' => '<path d="M4 5h16v12H7l-3 3V5z"/>',
        'badge-check' => '<path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/>',
    ];
    $path = $icons[$name] ?? $icons['package'];
    return '<svg class="' . e($class) . '" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">' . $path . '</svg>';
}

function stars(int $rating, string $class = 'w-5 h-5'): string
{
    $html = '<span class="inline-flex gap-0.5 text-amber-500" aria-label="' . e((string) $rating) . ' out of 5 stars">';
    for ($i = 1; $i <= 5; $i++) {
        $fill = $i <= $rating ? 'currentColor' : 'none';
        $html .= '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="' . $fill . '" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 3l2.6 5.3L20 9.2l-4 3.9.9 5.9L12 16.5 7.1 19l.9-5.9-4-3.9 5.4-.9L12 3z"/></svg>';
    }
    return $html . '</span>';
}

function nav_items(): array
{
    return [
        ['href' => '/', 'label' => 'Home'],
        ['href' => '/about', 'label' => 'About'],
        ['href' => '/services', 'label' => 'Services'],
        ['href' => '/request-procurement', 'label' => 'Request a Quote'],
        ['href' => '/reviews', 'label' => 'Reviews'],
        ['href' => '/contact', 'label' => 'Contact'],
    ];
}

function units(): array
{
    return ['pcs', 'kg', 'tonnes', 'litres', 'boxes', 'pallets', 'sets', 'metres', 'other'];
}

function budget_ranges(): array
{
    return [
        'under-5k' => 'Under $5,000',
        '5k-25k' => '$5,000 – $25,000',
        '25k-100k' => '$25,000 – $100,000',
        '100k-500k' => '$100,000 – $500,000',
        '500k-plus' => '$500,000+',
        'undisclosed' => 'Prefer not to say',
    ];
}
