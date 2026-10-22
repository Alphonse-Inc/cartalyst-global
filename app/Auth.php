<?php
declare(strict_types=1);

namespace App;

use App\Models\Admin;

final class Auth
{
    public static function check(): bool
    {
        return !empty($_SESSION['admin_id']);
    }

    public static function id(): ?int
    {
        return isset($_SESSION['admin_id']) ? (int) $_SESSION['admin_id'] : null;
    }

    public static function user(): ?array
    {
        $id = self::id();
        return $id ? Admin::find($id) : null;
    }

    public static function requireAdmin(): void
    {
        if (!self::check()) {
            flash('errors', ['_form' => 'Please sign in to continue.']);
            redirect('/admin/login');
        }
    }

    public static function attempt(string $email, string $password): bool
    {
        if (RateLimiter::tooMany('login')) {
            flash('errors', ['_form' => 'Too many sign-in attempts. Please wait 15 minutes.']);
            return false;
        }

        $admin = Admin::findByEmail($email);
        if (!$admin) {
            usleep(300000);
            return false;
        }

        if (!empty($admin['locked_until']) && strtotime((string) $admin['locked_until']) > time()) {
            flash('errors', ['_form' => 'This account is temporarily locked. Try again later.']);
            return false;
        }

        if (!password_verify($password, $admin['password_hash'])) {
            Admin::recordFailure((int) $admin['id']);
            return false;
        }

        if (password_needs_rehash($admin['password_hash'], PASSWORD_DEFAULT)) {
            Admin::updatePassword((int) $admin['id'], $password);
        }

        Admin::recordSuccess((int) $admin['id'], client_ip());
        Session::regenerate();
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        return true;
    }

    public static function logout(): void
    {
        Session::destroy();
        Session::start();
    }
}
