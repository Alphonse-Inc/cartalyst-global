<?php
$industries = [
    ['title' => 'Manufacturing', 'copy' => 'MRO, spare parts, and production consumables.'],
    ['title' => 'Construction', 'copy' => 'Materials and site packages aligned to programme.'],
    ['title' => 'Healthcare', 'copy' => 'Clinical consumables and facility supplies.'],
    ['title' => 'Corporate offices', 'copy' => 'Workplace, IT, and multi-site replenishment.'],
    ['title' => 'Energy & utilities', 'copy' => 'Specified equipment and operational spares.'],
    ['title' => 'Hospitality', 'copy' => 'Kitchen, housekeeping, and guest amenity supply.'],
];
$steps = [
    ['n' => '01', 'title' => 'Share the brief', 'copy' => 'Tell us the item, specification, quantity, and destination. No account required.'],
    ['n' => '02', 'title' => 'We source & compare', 'copy' => 'Cartalyst Global qualifies suppliers, checks availability, and clarifies lead times.'],
    ['n' => '03', 'title' => 'You approve', 'copy' => 'Receive a clear proposal with options, so procurement stays in your control.'],
    ['n' => '04', 'title' => 'We deliver', 'copy' => 'Orders are tracked through to site, with a single point of contact.'],
];
?>
<section class="hero-wash">
    <div class="mx-auto grid max-w-6xl gap-10 px-4 pb-16 pt-10 lg:grid-cols-2 lg:items-center lg:pt-16">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-accent">Global procurement partner</p>
            <h1 class="font-display mt-3 text-4xl leading-tight text-navy sm:text-5xl lg:text-6xl">Sourcing you can brief once, and trust worldwide.</h1>
            <p class="mt-5 max-w-xl text-lg text-navy/70">Cartalyst Global finds, vets, and delivers industrial, corporate, medical, IT, and construction supplies — so your team spends less time chasing vendors and more time running the operation.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a class="btn btn-primary" href="<?= e(base_url('request-procurement')) ?>">Request a Quote</a>
                <a class="btn btn-ghost" href="<?= e(base_url('services')) ?>">Explore services</a>
            </div>
            <dl class="mt-10 grid grid-cols-3 gap-3 text-center sm:text-left">
                <div class="card p-4"><dt class="text-xs uppercase tracking-wide text-navy/50">Categories</dt><dd class="stat font-display text-2xl text-navy"><?= count($services ?? []) ?>+</dd></div>
                <div class="card p-4"><dt class="text-xs uppercase tracking-wide text-navy/50">Reviews</dt><dd class="stat font-display text-2xl text-navy"><?= e((string) ($stats['count'] ?? 0)) ?></dd></div>
                <div class="card p-4"><dt class="text-xs uppercase tracking-wide text-navy/50">Avg rating</dt><dd class="stat font-display text-2xl text-navy"><?= e($stats['average'] ?? '—') ?></dd></div>
            </dl>
        </div>
        <div class="relative">
            <!-- REPLACE: Unsplash warehouse placeholder -->
            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1400&q=80"
                 alt="Organised warehouse aisle representing Cartalyst Global procurement logistics"
                 width="900" height="700" class="h-[280px] w-full rounded-3xl object-cover shadow-xl sm:h-[420px]" loading="eager">
            <div class="absolute -bottom-5 left-4 right-4 card p-4 sm:left-8 sm:right-auto sm:max-w-xs">
                <p class="text-sm font-semibold text-navy">No signup required</p>
                <p class="text-sm text-navy/70">Submit a brief and receive a unique Cartalyst Global reference number.</p>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-16" data-reveal>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-accent">What we buy</p>
            <h2 class="font-display text-3xl text-navy sm:text-4xl">Procurement services, built around your specification.</h2>
        </div>
        <a class="btn btn-ghost" href="<?= e(base_url('services')) ?>">All services</a>
    </div>
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach (array_slice($services ?? [], 0, 6) as $service): ?>
            <a class="card p-6 hover:-translate-y-0.5 transition" href="<?= e(base_url('services/' . $service['slug'])) ?>">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-navy text-accent"><?= icon($service['icon'], 'w-5 h-5') ?></span>
                <h3 class="mt-4 font-display text-xl text-navy"><?= e($service['title']) ?></h3>
                <p class="mt-2 text-sm text-navy/70"><?= e($service['short_description']) ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-navy text-cream py-16" data-reveal>
    <div class="mx-auto max-w-6xl px-4">
        <p class="text-sm font-bold uppercase tracking-[0.18em] text-accent">How it works</p>
        <h2 class="font-display mt-2 text-3xl sm:text-4xl">Four steps from brief to delivery.</h2>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($steps as $step): ?>
                <div class="rounded-3xl border border-white/10 p-5">
                    <p class="font-display text-3xl text-accent"><?= e($step['n']) ?></p>
                    <h3 class="mt-2 text-lg font-bold"><?= e($step['title']) ?></h3>
                    <p class="mt-2 text-sm text-cream/70"><?= e($step['copy']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-16" data-reveal>
    <h2 class="font-display text-3xl text-navy">Industries we serve</h2>
    <p class="mt-2 max-w-2xl text-navy/70">Cartalyst Global supports operators who need a buying partner, not another catalogue login.</p>
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($industries as $item): ?>
            <article class="card p-6">
                <h3 class="font-semibold text-navy"><?= e($item['title']) ?></h3>
                <p class="mt-2 text-sm text-navy/70"><?= e($item['copy']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="px-4 pb-16" data-reveal>
    <div class="mx-auto max-w-6xl">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="font-display text-3xl text-navy">What clients say</h2>
                <p class="text-navy/70">Approved reviews only — published from the Cartalyst Global reviews page.</p>
            </div>
            <a class="btn btn-ghost" href="<?= e(base_url('reviews')) ?>">All reviews</a>
        </div>
        <?php if (empty($reviews)): ?>
            <p class="card mt-6 p-6 text-navy/70">Reviews will appear here once they are approved in the admin panel.</p>
        <?php else: ?>
            <div class="mt-6 flex snap-x gap-4 overflow-x-auto pb-3" role="list">
                <?php foreach ($reviews as $review): ?>
                    <article class="card w-[85%] shrink-0 snap-start p-6 sm:w-[380px]" role="listitem">
                        <?= stars((int) $review['rating']) ?>
                        <h3 class="mt-3 font-semibold text-navy"><?= e($review['title']) ?></h3>
                        <p class="mt-2 text-sm text-navy/70"><?= e(function_exists('mb_strimwidth') ? mb_strimwidth($review['body'], 0, 180, '…') : (strlen($review['body']) > 180 ? substr($review['body'], 0, 177) . '…' : $review['body'])) ?></p>
                        <p class="mt-4 text-sm font-bold"><?= e($review['name']) ?><?php if ($review['company']): ?><span class="font-medium text-navy/60"> · <?= e($review['company']) ?></span><?php endif; ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="px-4 pb-20">
    <div class="mx-auto max-w-6xl overflow-hidden rounded-[2rem] bg-gradient-to-br from-navy to-navy-800 px-6 py-12 text-cream sm:px-12">
        <h2 class="font-display text-3xl sm:text-4xl">Need it sourced? Brief Cartalyst Global today.</h2>
        <p class="mt-3 max-w-xl text-cream/75">Send the specification, quantity, and destination. We will reply with a reference number and next steps.</p>
        <a class="btn btn-primary mt-6" href="<?= e(base_url('request-procurement')) ?>">Request a Quote</a>
    </div>
</section>
