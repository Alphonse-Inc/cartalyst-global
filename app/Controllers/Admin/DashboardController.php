<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Models\ProcurementRequest;
use App\Models\Review;
use App\View;

final class DashboardController
{
    public static function index(): void
    {
        Auth::requireAdmin();
        View::render('admin/dashboard', [
            'pageTitle' => 'Dashboard | Cartalyst Global Admin',
            'counts' => ProcurementRequest::counts(),
            'pendingReviews' => Review::pendingCount(),
            'reviewStats' => Review::stats(),
            'admin' => Auth::user(),
        ], 'layouts/admin');
    }
}
