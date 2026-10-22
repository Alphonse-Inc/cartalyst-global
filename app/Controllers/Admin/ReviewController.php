<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\Models\Review;
use App\View;

final class ReviewController
{
    public static function index(): void
    {
        Auth::requireAdmin();
        $status = (string) ($_GET['status'] ?? 'pending');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $result = Review::paginateAdmin($status, $page);
        View::render('admin/reviews', [
            'pageTitle' => 'Reviews | Cartalyst Global Admin',
            'result' => $result,
            'status' => $status,
            'page' => $page,
        ], 'layouts/admin');
    }

    public static function action(): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        $id = (int) ($_POST['id'] ?? 0);
        $do = (string) ($_POST['do'] ?? '');
        if (!$id) {
            redirect('/admin/reviews');
        }
        if ($do === 'approve') {
            Review::setStatus($id, 'approved');
            flash('success', 'Review approved and is now public.');
        } elseif ($do === 'reject') {
            Review::setStatus($id, 'rejected');
            flash('success', 'Review rejected.');
        } elseif ($do === 'delete') {
            Review::delete($id);
            flash('success', 'Review deleted.');
        } elseif ($do === 'save') {
            Review::update($id, [
                'name' => trim((string) $_POST['name']),
                'company' => trim((string) ($_POST['company'] ?? '')) ?: null,
                'job_title' => trim((string) ($_POST['job_title'] ?? '')) ?: null,
                'rating' => max(1, min(5, (int) $_POST['rating'])),
                'title' => trim((string) $_POST['title']),
                'body' => trim((string) $_POST['body']),
                'featured' => isset($_POST['featured']) ? 1 : 0,
                'status' => in_array($_POST['status'] ?? '', ['pending', 'approved', 'rejected'], true)
                    ? $_POST['status']
                    : 'pending',
            ]);
            flash('success', 'Review saved.');
        }
        redirect('/admin/reviews?status=' . urlencode((string) ($_POST['return_status'] ?? 'pending')));
    }
}
