<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Service;

final class SeoController
{
    public static function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        $urls = [
            ['loc' => base_url('/'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => base_url('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => base_url('services'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => base_url('request-procurement'), 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => base_url('reviews'), 'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => base_url('contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => base_url('privacy'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['loc' => base_url('terms'), 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];
        try {
            foreach (Service::active() as $service) {
                $urls[] = [
                    'loc' => base_url('services/' . $service['slug']),
                    'priority' => '0.7',
                    'changefreq' => 'monthly',
                ];
            }
        } catch (\Throwable) {
            // DB may be unavailable during first install
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo '  <url>';
            echo '<loc>' . e($u['loc']) . '</loc>';
            echo '<changefreq>' . e($u['changefreq']) . '</changefreq>';
            echo '<priority>' . e($u['priority']) . '</priority>';
            echo "</url>\n";
        }
        echo '</urlset>';
        exit;
    }

    public static function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        $host = base_url('/');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /setup\n";
        echo "Disallow: /uploads\n";
        echo "Disallow: /uploads/\n";
        echo "Disallow: /config\n";
        echo "Sitemap: {$host}sitemap.xml\n";
        exit;
    }
}
