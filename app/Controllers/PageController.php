<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Review;
use App\Models\Service;
use App\View;

final class PageController
{
    public static function home(): void
    {
        View::render('pages/home', [
            'pageTitle' => 'Cartalyst Global | Procurement & Sourcing Partner',
            'metaDescription' => 'Cartalyst Global is a trusted global procurement and sourcing partner for industrial, corporate, IT, medical, and construction supplies. Request a quote today.',
            'canonical' => base_url('/'),
            'breadcrumbs' => [['name' => 'Home', 'url' => base_url('/')]],
            'services' => Service::active(),
            'reviews' => Review::featured(8),
            'stats' => Review::stats(),
        ]);
    }

    public static function about(): void
    {
        View::render('pages/about', [
            'pageTitle' => 'About Cartalyst Global | Our Story, Mission & Values',
            'metaDescription' => 'Learn how Cartalyst Global became a modern procurement partner. Our mission, vision, values, and the people behind global sourcing you can trust.',
            'canonical' => base_url('about'),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url('/')],
                ['name' => 'About', 'url' => base_url('about')],
            ],
        ]);
    }

    public static function services(): void
    {
        View::render('pages/services', [
            'pageTitle' => 'Procurement Services | Cartalyst Global',
            'metaDescription' => 'Explore Cartalyst Global procurement services: industrial supplies, office products, IT, construction materials, medical supplies, logistics, and custom sourcing.',
            'canonical' => base_url('services'),
            'type' => 'services',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url('/')],
                ['name' => 'Services', 'url' => base_url('services')],
            ],
            'services' => Service::active(),
        ]);
    }

    public static function service(string $slug): void
    {
        $service = Service::findBySlug($slug);
        if (!$service) {
            http_response_code(404);
            View::render('pages/404', [
                'pageTitle' => 'Service not found | Cartalyst Global',
                'metaDescription' => 'This Cartalyst Global service page could not be found.',
            ]);
            return;
        }
        View::render('pages/service', [
            'pageTitle' => $service['title'] . ' | Cartalyst Global',
            'metaDescription' => $service['short_description'] . ' Enquire with Cartalyst Global.',
            'canonical' => base_url('services/' . $service['slug']),
            'service' => $service,
            'services' => Service::active(),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url('/')],
                ['name' => 'Services', 'url' => base_url('services')],
                ['name' => $service['title'], 'url' => base_url('services/' . $service['slug'])],
            ],
        ]);
    }

    public static function privacy(): void
    {
        View::render('pages/privacy', [
            'pageTitle' => 'Privacy Policy | Cartalyst Global',
            'metaDescription' => 'How Cartalyst Global collects, uses, and protects personal information submitted through our website, quote requests, and reviews.',
            'canonical' => base_url('privacy'),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url('/')],
                ['name' => 'Privacy Policy', 'url' => base_url('privacy')],
            ],
        ]);
    }

    public static function terms(): void
    {
        View::render('pages/terms', [
            'pageTitle' => 'Terms of Use | Cartalyst Global',
            'metaDescription' => 'Terms of use for the Cartalyst Global website, procurement requests, and related communications.',
            'canonical' => base_url('terms'),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url('/')],
                ['name' => 'Terms of Use', 'url' => base_url('terms')],
            ],
        ]);
    }
}
