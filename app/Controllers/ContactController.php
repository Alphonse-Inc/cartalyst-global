<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Captcha;
use App\Csrf;
use App\Mailer;
use App\Models\ContactMessage;
use App\RateLimiter;
use App\Validator;
use App\View;

final class ContactController
{
    public static function show(): void
    {
        View::render('pages/contact', [
            'pageTitle' => 'Contact Cartalyst Global | Talk to Procurement',
            'metaDescription' => 'Contact Cartalyst Global for procurement enquiries, partnerships, and support. Send a message or request a sourcing quote.',
            'canonical' => base_url('contact'),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url('/')],
                ['name' => 'Contact', 'url' => base_url('contact')],
            ],
            'success' => flash('success'),
        ]);
    }

    public static function store(): void
    {
        Csrf::requireValid();
        $v = new Validator();
        $v->honeypot();
        $v->required('name', $_POST['name'] ?? '', 'Name')
            ->required('email', $_POST['email'] ?? '', 'Email')
            ->email('email', $_POST['email'] ?? '', 'Email')
            ->required('subject', $_POST['subject'] ?? '', 'Subject')
            ->required('message', $_POST['message'] ?? '', 'Message')
            ->min('message', $_POST['message'] ?? '', 10, 'Message')
            ->max('message', $_POST['message'] ?? '', 4000, 'Message');

        if (!Captcha::verify()) {
            $v->add('captcha', 'Please complete the verification check.');
        }
        if (RateLimiter::tooMany('contact')) {
            $v->add('_form', 'Too many messages. Please try again later.');
        }
        if ($v->failed()) {
            if (is_ajax()) {
                json_response(['ok' => false, 'errors' => $v->errors(), 'message' => reset($v->errors())], 422);
            }
            $_SESSION['_old'] = $_POST;
            flash('errors', $v->errors());
            redirect('/contact');
        }

        $data = [
            'name' => trim((string) $_POST['name']),
            'email' => strtolower(trim((string) $_POST['email'])),
            'phone' => trim((string) ($_POST['phone'] ?? '')) ?: null,
            'subject' => trim((string) $_POST['subject']),
            'message' => trim((string) $_POST['message']),
            'ip_hash' => ip_hash(),
        ];
        ContactMessage::create($data);
        Mailer::send(
            (string) config('mail.contact_to'),
            'Contact form: ' . $data['subject'] . ' — Cartalyst Global',
            View::email('contact-admin', ['m' => $data]),
            $data['email'],
            $data['name']
        );

        if (is_ajax()) {
            json_response(['ok' => true, 'message' => 'Message sent. We will reply shortly.']);
        }
        flash('success', 'Thank you. Your message has been sent to Cartalyst Global.');
        redirect('/contact');
    }
}
