<?php
/**
 * Cartalyst Global — copy this file to config/config.php
 * Prefer setting secrets in config/.env (loaded automatically).
 * Values below are fallbacks for local/shared-hosting installs.
 */
declare(strict_types=1);

return [
    'env' => env('APP_ENV', 'production'),
    'debug' => env_bool('APP_DEBUG', false),
    'name' => env('APP_NAME', 'Cartalyst Global'),
    'url' => rtrim((string) env('APP_URL', 'https://cartalystglobal.com'), '/'),
    'key' => env('APP_KEY', 'replace-with-a-long-random-string'),
    'canonical_host' => env('CANONICAL_HOST', 'cartalystglobal.com'),
    'canonical_style' => env('CANONICAL_STYLE', 'apex'), // apex | www
    'force_https' => env_bool('FORCE_HTTPS', true),

    'timezone' => 'UTC',

    'db' => [
        'host' => env('DB_HOST', 'localhost'),
        'name' => env('DB_NAME', 'cartalyst_global'),
        'user' => env('DB_USER', ''),
        'pass' => env('DB_PASS', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
    ],

    'mail' => [
        'host' => env('SMTP_HOST', ''),
        'port' => (int) env('SMTP_PORT', 587),
        'username' => env('SMTP_USER', ''),
        'password' => env('SMTP_PASS', ''),
        'encryption' => env('SMTP_ENCRYPTION', 'tls'), // tls | ssl | none
        'from_email' => env('SMTP_FROM_EMAIL', 'noreply@cartalystglobal.com'),
        'from_name' => env('SMTP_FROM_NAME', 'Cartalyst Global'),
        'procurement_to' => env('PROCUREMENT_TO_EMAIL', 'procurement@cartalystglobal.com'),
        'contact_to' => env('CONTACT_TO_EMAIL', 'hello@cartalystglobal.com'),
        'reviews_to' => env('REVIEWS_TO_EMAIL', 'hello@cartalystglobal.com'),
    ],

    'captcha' => [
        'provider' => env('CAPTCHA_PROVIDER', 'none'), // turnstile | recaptcha | none
        'turnstile_site' => env('TURNSTILE_SITE_KEY', ''),
        'turnstile_secret' => env('TURNSTILE_SECRET_KEY', ''),
        'recaptcha_site' => env('RECAPTCHA_SITE_KEY', ''),
        'recaptcha_secret' => env('RECAPTCHA_SECRET_KEY', ''),
    ],

    'seo' => [
        'gsc_verification' => env('GSC_VERIFICATION', ''),
        'bing_verification' => env('BING_VERIFICATION', ''),
        'og_image' => '/assets/img/og-default.svg',
        'locale' => 'en_GB',
        'twitter_handle' => '',
    ],

    'uploads' => [
        'max_bytes' => 5 * 1024 * 1024,
        'allowed_ext' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'],
        'allowed_mime' => [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'image/jpeg',
            'image/png',
        ],
    ],

    'rate_limits' => [
        'procurement' => ['hits' => 5, 'window' => 3600],
        'review' => ['hits' => 3, 'window' => 86400],
        'contact' => ['hits' => 5, 'window' => 3600],
        'login' => ['hits' => 8, 'window' => 900],
    ],

    'session' => [
        'name' => 'cg_sess',
        'lifetime' => 7200,
    ],

    'brand' => [
        'navy' => '#0B1F3A',
        'emerald' => '#0F9D6E',
        'amber' => '#D4A017',
        'cream' => '#F6F3EC',
    ],
];
