<?php $admin = $admin ?? App\Auth::user(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Admin') ?></title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" href="<?= e(base_url('assets/img/favicon.svg')) ?>" type="image/svg+xml">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Manrope:wght@400;600;700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= e(base_url('assets/css/app.css')) ?>">
</head>
<body class="min-h-screen bg-cream font-sans text-navy">
<div class="lg:grid lg:grid-cols-[240px_1fr]">
    <aside class="bg-navy text-cream p-5">
        <a href="<?= e(base_url('admin')) ?>" class="block font-display text-xl">Cartalyst Admin</a>
        <nav class="mt-6 flex flex-col gap-1 text-sm font-semibold">
            <a class="min-h-[44px] flex items-center rounded-xl px-3 hover:bg-white/10" href="<?= e(base_url('admin')) ?>">Dashboard</a>
            <a class="min-h-[44px] flex items-center rounded-xl px-3 hover:bg-white/10" href="<?= e(base_url('admin/requests')) ?>">Requests</a>
            <a class="min-h-[44px] flex items-center rounded-xl px-3 hover:bg-white/10" href="<?= e(base_url('admin/reviews')) ?>">Reviews</a>
            <a class="min-h-[44px] flex items-center rounded-xl px-3 hover:bg-white/10" href="<?= e(base_url('admin/services')) ?>">Services</a>
            <a class="min-h-[44px] flex items-center rounded-xl px-3 hover:bg-white/10" href="<?= e(base_url('/')) ?>" target="_blank" rel="noopener">View site</a>
        </nav>
        <form class="mt-8" method="post" action="<?= e(base_url('admin/logout')) ?>">
            <?= csrf_field() ?>
            <button class="btn btn-ghost text-cream border-white/20 w-full" type="submit">Sign out</button>
        </form>
    </aside>
    <div class="p-4 sm:p-8">
        <?php if ($msg = flash('success')): ?>
            <p class="mb-4 rounded-xl bg-emerald-50 p-3 text-emerald-800"><?= e($msg) ?></p>
        <?php endif; ?>
        <?php require $contentView; ?>
    </div>
</div>
</body>
</html>
