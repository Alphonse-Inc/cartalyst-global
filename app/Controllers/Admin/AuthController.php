<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\View;

final class AuthController
{
    public static function showLogin(): void
    {
        if (Auth::check()) {
            redirect('/admin');
        }
        View::render('admin/login', [
            'pageTitle' => 'Admin sign in | Cartalyst Global',
        ], 'layouts/admin-auth');
    }

    public static function login(): void
    {
        Csrf::requireValid();
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        if ($email === '' || $password === '') {
            flash('errors', ['_form' => 'Enter your email and password.']);
            redirect('/admin/login');
        }
        if (!Auth::attempt($email, $password)) {
            if (!flash('errors')) {
                flash('errors', ['_form' => 'Those credentials do not match our records.']);
            }
            redirect('/admin/login');
        }
        redirect('/admin');
    }

    public static function logout(): void
    {
        Auth::logout();
        redirect('/admin/login');
    }
}
