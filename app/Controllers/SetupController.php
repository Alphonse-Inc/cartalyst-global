<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Admin;
use App\View;

final class SetupController
{
    public static function show(): void
    {
        $lock = CONFIG_PATH . '/installed.lock';
        $hasConfig = is_file(CONFIG_PATH . '/config.php');
        $adminCount = 0;
        if ($hasConfig) {
            try {
                $adminCount = Admin::count();
            } catch (\Throwable) {
                $adminCount = 0;
            }
        }
        if (is_file($lock) && $adminCount > 0) {
            redirect('/');
        }
        View::render('pages/setup', [
            'pageTitle' => 'Setup | Cartalyst Global',
            'hasConfig' => $hasConfig,
            'adminCount' => $adminCount,
        ], 'layouts/admin-auth');
    }

    public static function store(): void
    {
        \App\Csrf::requireValid();
        $lock = CONFIG_PATH . '/installed.lock';
        if (!is_file(CONFIG_PATH . '/config.php') && is_file(CONFIG_PATH . '/config.example.php')) {
            copy(CONFIG_PATH . '/config.example.php', CONFIG_PATH . '/config.php');
        }

        try {
            if (Admin::count() > 0) {
                file_put_contents($lock, date('c'));
                redirect('/admin/login');
            }
        } catch (\Throwable $e) {
            flash('errors', ['_form' => 'Database connection failed. Check config/.env then try again.']);
            redirect('/setup');
        }

        $email = strtolower(trim((string) ($_POST['email'] ?? 'admin@cartalystglobal.com')));
        $password = (string) ($_POST['password'] ?? 'ChangeMeNow!2026');
        $name = trim((string) ($_POST['name'] ?? 'Site Admin'));
        if (strlen($password) < 10) {
            flash('errors', ['_form' => 'Use a password of at least 10 characters.']);
            redirect('/setup');
        }
        Admin::create($name, $email, $password);
        file_put_contents($lock, date('c'));
        flash('success', 'Admin created. Sign in and change the password immediately.');
        redirect('/admin/login');
    }
}
