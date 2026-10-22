<section class="hero-wash">
    <div class="mx-auto max-w-3xl px-4 py-14">
        <p class="text-sm"><a class="text-accent font-semibold" href="<?= e(base_url('services')) ?>">Services</a></p>
        <h1 class="font-display mt-3 text-4xl text-navy"><?= e($service['title']) ?></h1>
        <p class="mt-4 text-lg text-navy/70"><?= e($service['short_description']) ?></p>
    </div>
</section>
<section class="mx-auto max-w-3xl px-4 pb-16">
    <div class="card p-6 sm:p-10">
        <div class="prose max-w-none text-navy/80 leading-relaxed">
            <?= nl2br(e($service['description'])) ?>
        </div>
        <a class="btn btn-primary mt-8" href="<?= e(base_url('request-procurement')) ?>">Request this category</a>
    </div>
</section>
