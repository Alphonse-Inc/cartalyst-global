<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Captcha;
use App\Csrf;
use App\Mailer;
use App\Models\ProcurementRequest;
use App\Models\Service;
use App\RateLimiter;
use App\Upload;
use App\Validator;
use App\View;

final class ProcurementController
{
    public static function show(): void
    {
        View::render('pages/request', [
            'pageTitle' => 'Request Procurement | Cartalyst Global Quote Form',
            'metaDescription' => 'Request a procurement quote from Cartalyst Global. No account required. Tell us what you need and receive a unique reference number.',
            'canonical' => base_url('request-procurement'),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url('/')],
                ['name' => 'Request Procurement', 'url' => base_url('request-procurement')],
            ],
            'services' => Service::titles(),
            'success' => flash('success'),
            'reference' => flash('reference'),
        ]);
    }

    public static function store(): void
    {
        Csrf::requireValid();
        RateLimiter::prune();

        $v = new Validator();
        $v->honeypot();
        $v->required('full_name', $_POST['full_name'] ?? '', 'Full name')
            ->max('full_name', $_POST['full_name'] ?? '', 150, 'Full name')
            ->email('email', $_POST['email'] ?? '', 'Email')
            ->required('email', $_POST['email'] ?? '', 'Email')
            ->required('phone', $_POST['phone'] ?? '', 'Phone / WhatsApp')
            ->max('phone', $_POST['phone'] ?? '', 50, 'Phone / WhatsApp')
            ->required('country_city', $_POST['country_city'] ?? '', 'Country / city')
            ->required('category', $_POST['category'] ?? '', 'Category')
            ->in('category', $_POST['category'] ?? '', array_merge(Service::titles(), ['Other']), 'Category')
            ->required('item_description', $_POST['item_description'] ?? '', 'Item description')
            ->min('item_description', $_POST['item_description'] ?? '', 10, 'Item description')
            ->required('quantity', $_POST['quantity'] ?? '', 'Quantity')
            ->numeric('quantity', $_POST['quantity'] ?? '', 'Quantity')
            ->required('unit', $_POST['unit'] ?? '', 'Unit')
            ->in('unit', $_POST['unit'] ?? '', units(), 'Unit')
            ->in('budget_range', $_POST['budget_range'] ?? '', array_merge(array_keys(budget_ranges()), ['']), 'Budget range')
            ->date('delivery_date', $_POST['delivery_date'] ?? '', 'Delivery date')
            ->max('notes', $_POST['notes'] ?? '', 4000, 'Notes')
            ->max('company_name', $_POST['company_name'] ?? '', 150, 'Company name');

        if (!Captcha::verify()) {
            $v->add('captcha', 'Please complete the verification check.');
        }

        if (RateLimiter::tooMany('procurement')) {
            $v->add('_form', 'Too many requests from your network. Please try again later.');
        }

        $upload = null;
        try {
            $upload = Upload::storeProcurement($_FILES['attachment'] ?? null);
        } catch (\RuntimeException $e) {
            $v->add('attachment', $e->getMessage());
        }

        if ($v->failed()) {
            self::fail($v->errors());
        }

        $record = ProcurementRequest::create([
            'full_name' => trim((string) $_POST['full_name']),
            'company_name' => trim((string) ($_POST['company_name'] ?? '')) ?: null,
            'email' => strtolower(trim((string) $_POST['email'])),
            'phone' => trim((string) $_POST['phone']),
            'country_city' => trim((string) $_POST['country_city']),
            'category' => trim((string) $_POST['category']),
            'item_description' => trim((string) $_POST['item_description']),
            'quantity' => (float) $_POST['quantity'],
            'unit' => (string) $_POST['unit'],
            'budget_range' => trim((string) ($_POST['budget_range'] ?? '')) ?: null,
            'delivery_date' => trim((string) ($_POST['delivery_date'] ?? '')) ?: null,
            'notes' => trim((string) ($_POST['notes'] ?? '')) ?: null,
            'attachment_stored' => $upload['stored'] ?? null,
            'attachment_original' => $upload['original'] ?? null,
            'attachment_mime' => $upload['mime'] ?? null,
            'ip_hash' => ip_hash(),
            'user_agent' => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
        ]);

        $adminHtml = View::email('procurement-admin', ['r' => $record]);
        $userHtml = View::email('procurement-user', ['r' => $record]);

        Mailer::send(
            (string) config('mail.procurement_to'),
            'New procurement request ' . $record['reference'] . ' — Cartalyst Global',
            $adminHtml,
            $record['email'],
            $record['full_name']
        );
        Mailer::send(
            $record['email'],
            'We received your request ' . $record['reference'] . ' — Cartalyst Global',
            $userHtml
        );

        if (is_ajax()) {
            json_response([
                'ok' => true,
                'reference' => $record['reference'],
                'message' => 'Your request has been received.',
            ]);
        }

        flash('success', 'Your request has been received.');
        flash('reference', $record['reference']);
        redirect('/request-procurement');
    }

    private static function fail(array $errors): never
    {
        if (is_ajax()) {
            json_response(['ok' => false, 'errors' => $errors, 'message' => reset($errors)], 422);
        }
        $_SESSION['_old'] = $_POST;
        flash('errors', $errors);
        redirect('/request-procurement');
    }
}
