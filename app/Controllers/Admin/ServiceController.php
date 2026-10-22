<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\Models\Service;
use App\View;

final class ServiceController
{
    public static function index(): void
    {
        Auth::requireAdmin();
        View::render('admin/services', [
            'pageTitle' => 'Services | Cartalyst Global Admin',
            'services' => Service::all(),
        ], 'layouts/admin');
    }

    public static function save(): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        $id = (int) ($_POST['id'] ?? 0) ?: null;
        $title = trim((string) ($_POST['title'] ?? ''));
        if ($title === '') {
            flash('errors', ['_form' => 'Title is required.']);
            redirect('/admin/services');
        }
        $slug = slugify((string) ($_POST['slug'] ?? $title));
        Service::save([
            'slug' => $slug,
            'title' => $title,
            'short_description' => trim((string) ($_POST['short_description'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'icon' => trim((string) ($_POST['icon'] ?? 'package')) ?: 'package',
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ], $id);
        flash('success', 'Service saved.');
        redirect('/admin/services');
    }

    public static function delete(): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        Service::delete((int) ($_POST['id'] ?? 0));
        flash('success', 'Service removed.');
        redirect('/admin/services');
    }
}
