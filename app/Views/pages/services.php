<section class="hero-wash">
    <div class="mx-auto max-w-6xl px-4 py-14">
        <h1 class="font-display text-4xl text-navy sm:text-5xl">Procurement services from Cartalyst Global</h1>
        <p class="mt-4 max-w-2xl text-lg text-navy/70">From industrial MRO to medical consumables and custom sourcing programmes — one accountable partner across categories.</p>
    </div>
</section>
<section class="mx-auto max-w-6xl px-4 pb-16">
    <div class="grid gap-4 sm:grid-cols-2">
        <?php foreach ($services as $service): ?>
            <article class="card p-6">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-navy text-accent"><?= icon($service['icon'], 'w-5 h-5') ?></span>
                <h2 class="mt-4 font-display text-2xl text-navy"><?= e($service['title']) ?></h2>
                <p class="mt-2 text-navy/70"><?= e($service['short_description']) ?></p>
                <a class="mt-4 inline-flex min-h-[44px] items-center font-semibold text-accent" href="<?= e(base_url('services/' . $service['slug'])) ?>">View service <?= icon('arrow-right', 'w-4 h-4 ml-1') ?></a>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="mt-10 rounded-3xl bg-navy px-6 py-10 text-cream">
        <h2 class="font-display text-2xl">Need a category we have not listed?</h2>
        <p class="mt-2 text-cream/75">Send a custom brief. If we can source it responsibly, we will say so quickly.</p>
        <a class="btn btn-primary mt-5" href="<?= e(base_url('request-procurement')) ?>">Request a Quote</a>
    </div>
</section>
