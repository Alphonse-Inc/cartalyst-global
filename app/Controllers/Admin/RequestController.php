<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\Models\ProcurementRequest;
use App\View;

final class RequestController
{
    public static function index(): void
    {
        Auth::requireAdmin();
        $status = (string) ($_GET['status'] ?? '');
        $q = trim((string) ($_GET['q'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $result = ProcurementRequest::paginate($status, $q, $page);

        View::render('admin/requests', [
            'pageTitle' => 'Procurement requests | Cartalyst Global Admin',
            'result' => $result,
            'status' => $status,
            'q' => $q,
            'counts' => ProcurementRequest::counts(),
        ], 'layouts/admin');
    }

    public static function update(): void
    {
        Auth::requireAdmin();
        Csrf::requireValid();
        $id = (int) ($_POST['id'] ?? 0);
        $status = (string) ($_POST['status'] ?? '');
        if ($id && in_array($status, ['new', 'in_progress', 'completed'], true)) {
            ProcurementRequest::updateStatus($id, $status);
            flash('success', 'Request status updated.');
        }
        redirect('/admin/requests');
    }

    public static function export(): void
    {
        Auth::requireAdmin();
        $status = (string) ($_GET['status'] ?? '');
        $q = trim((string) ($_GET['q'] ?? ''));
        $rows = ProcurementRequest::export($status, $q);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="cartalyst-requests-' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Reference', 'Status', 'Name', 'Company', 'Email', 'Phone', 'Location', 'Category', 'Quantity', 'Unit', 'Budget', 'Delivery', 'Created']);
        foreach ($rows as $row) {
            fputcsv($out, [
                $row['reference'], $row['status'], $row['full_name'], $row['company_name'],
                $row['email'], $row['phone'], $row['country_city'], $row['category'],
                $row['quantity'], $row['unit'], $row['budget_range'], $row['delivery_date'], $row['created_at'],
            ]);
        }
        fclose($out);
        exit;
    }

    public static function file(string $id): void
    {
        Auth::requireAdmin();
        $row = ProcurementRequest::find((int) $id);
        if (!$row || empty($row['attachment_stored'])) {
            redirect('/admin/requests');
        }
        $path = UPLOAD_PATH . '/' . $row['attachment_stored'];
        $realUpload = realpath(UPLOAD_PATH);
        $realFile = realpath($path);
        if (!$realUpload || !$realFile || !str_starts_with($realFile, $realUpload) || !is_file($realFile)) {
            redirect('/admin/requests');
        }
        $mime = $row['attachment_mime'] ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename((string) $row['attachment_original']) . '"');
        header('X-Content-Type-Options: nosniff');
        readfile($realFile);
        exit;
    }

    public static function show(string $id): void
    {
        Auth::requireAdmin();
        $row = ProcurementRequest::find((int) $id);
        if (!$row) {
            redirect('/admin/requests');
        }
        View::render('admin/request-show', [
            'pageTitle' => $row['reference'] . ' | Cartalyst Global Admin',
            'row' => $row,
        ], 'layouts/admin');
    }
}
