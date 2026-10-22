<?php
$pageTitle = $pageTitle ?? 'Cartalyst Global';
$metaDescription = $metaDescription ?? 'Cartalyst Global is a modern procurement and sourcing partner.';
$canonical = $canonical ?? base_url(ltrim(current_path(), '/'));
$ogImage = base_url(ltrim((string) config('seo.og_image'), '/'));
$jsonLd = App\Seo::jsonLd([
    'breadcrumbs' => $breadcrumbs ?? [],
    'service' => $service ?? null,
    'type' => $type ?? '',
]);
$gsc = (string) config('seo.gsc_verification');
$bing = (string) config('seo.bing_verification');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta name="robots" content="index,follow">
    <?php if ($gsc): ?><meta name="google-site-verification" content="<?= e($gsc) ?>"><?php endif; ?>
    <?php if ($bing): ?><meta name="msvalidate.01" content="<?= e($bing) ?>"><?php endif; ?>
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Cartalyst Global">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">
    <link rel="icon" href="<?= e(base_url('assets/img/favicon.svg')) ?>" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Manrope:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Manrope:wght@400;500;600;700;800&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              navy: { DEFAULT: '#0B1F3A', 800: '#123056', 900: '#0B1F3A', 950: '#07111F' },
              accent: { DEFAULT: '#0F9D6E', 600: '#0B7D57' },
              cream: '#F6F3EC',
              amberx: '#D4A017'
            },
            fontFamily: {
              display: ['Fraunces', 'Georgia', 'serif'],
              sans: ['Manrope', 'ui-sans-serif', 'system-ui']
            }
          }
        }
      }
    </script>
    <link rel="stylesheet" href="<?= e(base_url('assets/css/app.css')) ?>">
    <script defer src="https://unpkg.com/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <?php if (App\Captcha::enabled() && App\Captcha::provider() === 'turnstile'): ?>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php elseif (App\Captcha::enabled()): ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?= e(App\Captcha::siteKey()) ?>"></script>
    <?php endif; ?>
    <script type="application/ld+json"><?= $jsonLd ?></script>
</head>
<body class="font-sans bg-cream text-navy antialiased">
<a class="skip-link" href="#main">Skip to content</a>
<?php App\View::partial('header'); ?>
<main id="main">
    <?php require $contentView; ?>
</main>
<?php App\View::partial('footer'); ?>
<script src="<?= e(base_url('assets/js/app.js')) ?>" defer></script>
<?php unset($_SESSION['_old']); ?>
</body>
</html>
