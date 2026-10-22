<?php $errs = errors(); ?>
<section class="hero-wash">
    <div class="mx-auto max-w-6xl px-4 py-14">
        <h1 class="font-display text-4xl text-navy">Cartalyst Global reviews</h1>
        <p class="mt-3 max-w-2xl text-navy/70">Honest feedback from clients. New reviews are published only after our team approves them.</p>
        <div class="mt-6 flex flex-wrap gap-4">
            <div class="card px-5 py-4">
                <p class="text-sm text-navy/60">Average rating</p>
                <p class="font-display text-3xl"><?= e($stats['average']) ?> / 5</p>
            </div>
            <div class="card px-5 py-4">
                <p class="text-sm text-navy/60">Approved reviews</p>
                <p class="font-display text-3xl"><?= e((string) $stats['count']) ?></p>
            </div>
        </div>
    </div>
</section>
<section class="mx-auto max-w-6xl px-4 pb-10">
    <?php if (empty($reviews)): ?>
        <p class="card p-6">No approved reviews yet. Be the first to share your experience with Cartalyst Global.</p>
    <?php else: ?>
        <div class="grid gap-4 md:grid-cols-2">
            <?php foreach ($reviews as $review): ?>
                <article class="card p-6">
                    <?= stars((int) $review['rating']) ?>
                    <h2 class="mt-3 font-display text-xl"><?= e($review['title']) ?></h2>
                    <p class="mt-2 text-navy/75"><?= nl2br(e($review['body'])) ?></p>
                    <p class="mt-4 text-sm font-bold"><?= e($review['name']) ?>
                        <?php if ($review['job_title'] || $review['company']): ?>
                            <span class="font-medium text-navy/60"> · <?= e(trim($review['job_title'] . ($review['job_title'] && $review['company'] ? ', ' : '') . $review['company'])) ?></span>
                        <?php endif; ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if ($pages > 1): ?>
            <nav class="mt-8 flex gap-2" aria-label="Reviews pagination">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <a class="btn <?= $i === $page ? 'btn-navy' : 'btn-ghost' ?>" href="<?= e(base_url('reviews?page=' . $i)) ?>"><?= $i ?></a>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>
<section class="mx-auto max-w-3xl px-4 pb-16">
    <form class="card p-6 sm:p-8" method="post" action="<?= e(base_url('reviews')) ?>" data-ajax-form>
        <h2 class="font-display text-2xl text-navy">Share a review</h2>
        <p class="mt-1 text-sm text-navy/70">Your email is never shown publicly. Submissions stay pending until approved.</p>
        <?= csrf_field() ?><?= honeypot_field() ?>
        <?php if (!empty($success)): ?><p class="mt-4 rounded-xl bg-emerald-50 p-3 text-emerald-800"><?= e($success) ?></p><?php endif; ?>
        <?php if (!empty($errs['_form'])): ?><p class="mt-4 rounded-xl bg-red-50 p-3 text-red-800"><?= e($errs['_form']) ?></p><?php endif; ?>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="name">Name</label>
                <input class="input" id="name" name="name" required value="<?= e((string) old('name')) ?>">
            </div>
            <div>
                <label class="label" for="email">Email (not published)</label>
                <input class="input" id="email" name="email" type="email" required value="<?= e((string) old('email')) ?>">
            </div>
            <div>
                <label class="label" for="company">Company (optional)</label>
                <input class="input" id="company" name="company" value="<?= e((string) old('company')) ?>">
            </div>
            <div>
                <label class="label" for="job_title">Job title (optional)</label>
                <input class="input" id="job_title" name="job_title" value="<?= e((string) old('job_title')) ?>">
            </div>
        </div>
        <div class="mt-4">
            <label class="label" for="rating">Star rating</label>
            <select class="input" id="rating" name="rating" required>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>" <?= (string) old('rating', '5') === (string) $i ? 'selected' : '' ?>><?= $i ?> star<?= $i > 1 ? 's' : '' ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="mt-4">
            <label class="label" for="title">Title</label>
            <input class="input" id="title" name="title" required value="<?= e((string) old('title')) ?>">
        </div>
        <div class="mt-4">
            <label class="label" for="body">Your review</label>
            <textarea class="input" id="body" name="body" required minlength="20"><?= e((string) old('body')) ?></textarea>
        </div>
        <?php App\View::partial('captcha'); ?>
        <button class="btn btn-primary mt-6" type="submit">Submit review</button>
        <p data-form-status></p>
    </form>
</section>
