<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Captcha;
use App\Csrf;
use App\Mailer;
use App\Models\Review;
use App\RateLimiter;
use App\Validator;
use App\View;

final class ReviewController
{
    public static function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 6;
        $stats = Review::stats();
        $total = (int) $stats['count'];
        $offset = ($page - 1) * $perPage;
        $reviews = Review::approved($perPage, $offset);

        View::render('pages/reviews', [
            'pageTitle' => 'Client Reviews | Cartalyst Global',
            'metaDescription' => 'Read verified client reviews of Cartalyst Global procurement and sourcing. Share your experience — reviews are published after approval.',
            'canonical' => base_url('reviews'),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url('/')],
                ['name' => 'Reviews', 'url' => base_url('reviews')],
            ],
            'reviews' => $reviews,
            'stats' => $stats,
            'page' => $page,
            'pages' => max(1, (int) ceil($total / $perPage)),
            'success' => flash('success'),
        ]);
    }

    public static function store(): void
    {
        Csrf::requireValid();

        $v = new Validator();
        $v->honeypot();
        $v->required('name', $_POST['name'] ?? '', 'Name')
            ->max('name', $_POST['name'] ?? '', 120, 'Name')
            ->required('email', $_POST['email'] ?? '', 'Email')
            ->email('email', $_POST['email'] ?? '', 'Email')
            ->required('rating', $_POST['rating'] ?? '', 'Rating')
            ->between('rating', $_POST['rating'] ?? 0, 1, 5, 'Rating')
            ->required('title', $_POST['title'] ?? '', 'Title')
            ->max('title', $_POST['title'] ?? '', 180, 'Title')
            ->required('body', $_POST['body'] ?? '', 'Review')
            ->min('body', $_POST['body'] ?? '', 20, 'Review')
            ->max('body', $_POST['body'] ?? '', 3000, 'Review')
            ->max('company', $_POST['company'] ?? '', 150, 'Company')
            ->max('job_title', $_POST['job_title'] ?? '', 120, 'Job title');

        if (!Captcha::verify()) {
            $v->add('captcha', 'Please complete the verification check.');
        }
        if (RateLimiter::tooMany('review')) {
            $v->add('_form', 'You have submitted too many reviews recently. Please try again later.');
        }

        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $body = trim((string) ($_POST['body'] ?? ''));
        if ($email && $body && Review::recentDuplicate($email, $body)) {
            $v->add('_form', 'This review looks like a duplicate of one we already received.');
        }

        if ($v->failed()) {
            if (is_ajax()) {
                json_response(['ok' => false, 'errors' => $v->errors(), 'message' => reset($v->errors())], 422);
            }
            $_SESSION['_old'] = $_POST;
            flash('errors', $v->errors());
            redirect('/reviews');
        }

        Review::create([
            'name' => trim((string) $_POST['name']),
            'company' => trim((string) ($_POST['company'] ?? '')) ?: null,
            'job_title' => trim((string) ($_POST['job_title'] ?? '')) ?: null,
            'email' => $email,
            'rating' => (int) $_POST['rating'],
            'title' => trim((string) $_POST['title']),
            'body' => $body,
            'ip_hash' => ip_hash(),
        ]);

        $html = View::email('review-admin', ['post' => $_POST]);
        Mailer::send(
            (string) config('mail.reviews_to'),
            'New pending review — Cartalyst Global',
            $html,
            $email,
            trim((string) $_POST['name'])
        );

        if (is_ajax()) {
            json_response(['ok' => true, 'message' => 'Thank you. Your review is pending approval.']);
        }
        flash('success', 'Thank you. Your review has been submitted and will appear after approval.');
        redirect('/reviews');
    }
}
