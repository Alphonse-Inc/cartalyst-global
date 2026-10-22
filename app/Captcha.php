<?php
declare(strict_types=1);

namespace App;

final class Captcha
{
    public static function provider(): string
    {
        return strtolower((string) config('captcha.provider', 'none'));
    }

    public static function enabled(): bool
    {
        $p = self::provider();
        if ($p === 'turnstile') {
            return config('captcha.turnstile_site') && config('captcha.turnstile_secret');
        }
        if ($p === 'recaptcha') {
            return config('captcha.recaptcha_site') && config('captcha.recaptcha_secret');
        }
        return false;
    }

    public static function siteKey(): string
    {
        return self::provider() === 'turnstile'
            ? (string) config('captcha.turnstile_site')
            : (string) config('captcha.recaptcha_site');
    }

    public static function verify(): bool
    {
        if (!self::enabled()) {
            return true;
        }
        $provider = self::provider();
        if ($provider === 'turnstile') {
            $token = (string) ($_POST['cf-turnstile-response'] ?? '');
            return self::remote('https://challenges.cloudflare.com/turnstile/v0/siteverify', (string) config('captcha.turnstile_secret'), $token);
        }
        $token = (string) ($_POST['g-recaptcha-response'] ?? '');
        return self::remote('https://www.google.com/recaptcha/api/siteverify', (string) config('captcha.recaptcha_secret'), $token);
    }

    private static function remote(string $url, string $secret, string $token): bool
    {
        if ($token === '') {
            return false;
        }
        $payload = http_build_query([
            'secret' => $secret,
            'response' => $token,
            'remoteip' => client_ip(),
        ]);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 8,
            ],
        ]);
        $raw = @file_get_contents($url, false, $context);
        if ($raw === false) {
            Logger::error('Captcha verify request failed');
            return false;
        }
        $data = json_decode($raw, true);
        return is_array($data) && !empty($data['success']);
    }
}
