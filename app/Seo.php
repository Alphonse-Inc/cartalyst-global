<?php
declare(strict_types=1);

namespace App;

use App\Models\Review;
use App\Models\Service;

final class Seo
{
    public static function jsonLd(array $page = []): string
    {
        $graph = [];
        $graph[] = self::organization();
        $graph[] = self::website();
        if (!empty($page['breadcrumbs'])) {
            $graph[] = self::breadcrumbs($page['breadcrumbs']);
        }
        $stats = Review::stats();
        if ((int) $stats['count'] > 0) {
            $graph[] = self::aggregate($stats);
            foreach (Review::approved(5, 0) as $review) {
                $graph[] = self::review($review);
            }
        }
        if (!empty($page['service'])) {
            $graph[] = self::service($page['service']);
        } elseif (($page['type'] ?? '') === 'services') {
            foreach (Service::active() as $service) {
                $graph[] = self::service($service);
            }
        }

        $payload = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_PRETTY_PRINT) ?: '{}';
    }

    public static function organization(): array
    {
        $sameAs = array_values(array_filter([
            setting('social_linkedin'),
            setting('social_twitter'),
            setting('social_facebook'),
            setting('social_instagram'),
        ]));
        $org = [
            '@type' => ['Organization', 'LocalBusiness'],
            '@id' => base_url('/#organization'),
            'name' => 'Cartalyst Global',
            'alternateName' => ['Cartalyst', 'Cartalyst Global Procurement'],
            'url' => base_url('/'),
            'logo' => base_url('assets/img/logo.svg'),
            'image' => base_url(ltrim((string) config('seo.og_image'), '/')),
            'email' => setting('email', (string) config('mail.contact_to')),
            'telephone' => setting('phone'),
            'description' => 'Cartalyst Global is a modern procurement and sourcing partner for industrial, corporate, medical, IT, and construction supplies worldwide.',
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'contactType' => 'sales',
                'email' => setting('email', (string) config('mail.procurement_to')),
                'telephone' => setting('phone'),
                'availableLanguage' => ['English'],
            ]],
        ];
        if (setting('address')) {
            $org['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => setting('address'),
            ];
        }
        if ($sameAs) {
            $org['sameAs'] = $sameAs;
        }
        return $org;
    }

    public static function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => base_url('/#website'),
            'name' => 'Cartalyst Global',
            'alternateName' => 'Cartalyst',
            'url' => base_url('/'),
            'publisher' => ['@id' => base_url('/#organization')],
            'inLanguage' => 'en',
        ];
    }

    public static function breadcrumbs(array $crumbs): array
    {
        $items = [];
        $i = 1;
        foreach ($crumbs as $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $i++,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ];
        }
        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    public static function aggregate(array $stats): array
    {
        return [
            '@type' => 'Organization',
            '@id' => base_url('/#organization'),
            'name' => 'Cartalyst Global',
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => $stats['average'],
                'reviewCount' => (int) $stats['count'],
                'bestRating' => 5,
                'worstRating' => 1,
            ],
        ];
    }

    public static function review(array $review): array
    {
        return [
            '@type' => 'Review',
            'itemReviewed' => [
                '@type' => 'Organization',
                'name' => 'Cartalyst Global',
            ],
            'author' => [
                '@type' => 'Person',
                'name' => $review['name'],
            ],
            'reviewRating' => [
                '@type' => 'Rating',
                'ratingValue' => (int) $review['rating'],
                'bestRating' => 5,
                'worstRating' => 1,
            ],
            'name' => $review['title'],
            'reviewBody' => $review['body'],
            'datePublished' => substr((string) $review['created_at'], 0, 10),
        ];
    }

    public static function service(array $service): array
    {
        return [
            '@type' => 'Service',
            'name' => $service['title'],
            'description' => $service['short_description'],
            'provider' => ['@id' => base_url('/#organization')],
            'areaServed' => 'Worldwide',
            'url' => base_url('services/' . $service['slug']),
        ];
    }
}
