<?php $errs = errors(); ?>
<section class="hero-wash">
    <div class="mx-auto max-w-6xl px-4 py-14">
        <h1 class="font-display text-4xl text-navy">Contact Cartalyst Global</h1>
        <p class="mt-3 max-w-2xl text-navy/70">Speak with our team about sourcing, partnerships, or an existing reference number.</p>
    </div>
</section>
<section class="mx-auto grid max-w-6xl gap-8 px-4 pb-16 lg:grid-cols-2">
    <div>
        <div class="card p-6 space-y-4">
            <p><strong>Email</strong><br><?= e(setting('email', (string) config('mail.contact_to'))) ?></p>
            <p><strong>Phone / WhatsApp</strong><br><?= e(setting('phone')) ?></p>
            <p><strong>Address</strong><br><?= e(setting('address')) ?></p>
            <p><strong>Hours</strong><br><?= e(setting('hours')) ?></p>
        </div>
        <div class="mt-6 overflow-hidden rounded-3xl bg-navy/5 min-h-[220px] flex items-center justify-center text-navy/60 text-sm p-6 text-center">
            <?php if (setting('map_embed')): ?>
                <?= setting('map_embed') ?>
            <?php else: ?>
                Map embed placeholder — paste a Google Maps iframe into the <code>map_embed</code> setting after install.
            <?php endif; ?>
        </div>
    </div>
    <form class="card p-6 sm:p-8" method="post" action="<?= e(base_url('contact')) ?>" data-ajax-form>
        <?= csrf_field() ?><?= honeypot_field() ?>
        <?php if (!empty($success)): ?><p class="mb-4 rounded-xl bg-emerald-50 p-3 text-emerald-800"><?= e($success) ?></p><?php endif; ?>
        <?php if (!empty($errs['_form'])): ?><p class="mb-4 rounded-xl bg-red-50 p-3 text-red-800"><?= e($errs['_form']) ?></p><?php endif; ?>
        <div>
            <label class="label" for="name">Name</label>
            <input class="input" id="name" name="name" required value="<?= e((string) old('name')) ?>">
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="email">Email</label>
                <input class="input" id="email" name="email" type="email" required value="<?= e((string) old('email')) ?>">
            </div>
            <div>
                <label class="label" for="phone">Phone (optional)</label>
                <input class="input" id="phone" name="phone" value="<?= e((string) old('phone')) ?>">
            </div>
        </div>
        <div class="mt-4">
            <label class="label" for="subject">Subject</label>
            <input class="input" id="subject" name="subject" required value="<?= e((string) old('subject')) ?>">
        </div>
        <div class="mt-4">
            <label class="label" for="message">Message</label>
            <textarea class="input" id="message" name="message" required><?= e((string) old('message')) ?></textarea>
        </div>
        <?php App\View::partial('captcha'); ?>
        <button class="btn btn-primary mt-6" type="submit">Send message</button>
        <p data-form-status></p>
    </form>
</section>
