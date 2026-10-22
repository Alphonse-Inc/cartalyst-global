<?php
declare(strict_types=1);

namespace App;

final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function verify(?string $token = null): bool
    {
        $token ??= $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $session = $_SESSION['_csrf'] ?? '';
        return is_string($token) && $session !== '' && hash_equals($session, $token);
    }

    public static function requireValid(): void
    {
        if (!self::verify()) {
            if (is_ajax()) {
                json_response(['ok' => false, 'message' => 'Your session expired. Please refresh the page and try again.'], 419);
            }
            http_response_code(419);
            flash('errors', ['_form' => 'Your session expired. Please refresh the page and try again.']);
            redirect(current_path());
        }
    }
}
