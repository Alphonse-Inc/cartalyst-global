<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Admin | Cartalyst Global') ?></title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" href="<?= e(base_url('assets/img/favicon.svg')) ?>" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Manrope:wght@400;600;700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= e(base_url('assets/css/app.css')) ?>">
</head>
<body class="min-h-screen bg-cream font-sans text-navy flex items-center justify-center px-4 py-10">
<div class="w-full max-w-md">
    <a href="<?= e(base_url('/')) ?>" class="mb-6 flex justify-center">
        <img src="<?= e(base_url('assets/img/logo.svg')) ?>" alt="Cartalyst Global" class="h-12">
    </a>
    <?php require $contentView; ?>
</div>
</body>
</html>
