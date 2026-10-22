<?php $errs = errors(); ?>
<section class="hero-wash">
    <div class="mx-auto max-w-3xl px-4 py-12">
        <h1 class="font-display text-4xl text-navy">Request procurement from Cartalyst Global</h1>
        <p class="mt-3 text-navy/70">No account needed. Tell us what to source. You will receive a unique reference number such as CG-<?= date('Y') ?>-000123.</p>
    </div>
</section>
<section class="mx-auto max-w-3xl px-4 pb-16">
    <div class="card p-5 sm:p-8" data-success-panel <?php if (empty($reference)): ?>hidden<?php endif; ?>>
        <p class="text-accent font-bold">Request received</p>
        <h2 class="font-display mt-2 text-3xl text-navy">Thank you. Your reference is <span data-reference><?= e($reference ?? '') ?></span></h2>
        <p class="mt-3 text-navy/70">A confirmation has been emailed to you. Our team will reply using this reference. Keep it for all follow-up.</p>
        <a class="btn btn-navy mt-6" href="<?= e(base_url('/')) ?>">Back to home</a>
    </div>

    <form class="card p-5 sm:p-8 mt-6" method="post" action="<?= e(base_url('request-procurement')) ?>" enctype="multipart/form-data" data-ajax-form
          x-data="procurementForm()" x-init="init()" <?php if (!empty($reference)): ?>hidden<?php endif; ?>>
        <?= csrf_field() ?>
        <?= honeypot_field() ?>
        <?php if (!empty($errs['_form']) || !empty($errs['_spam'])): ?>
            <p class="mb-4 rounded-xl bg-red-50 p-3 text-red-800"><?= e($errs['_form'] ?? $errs['_spam']) ?></p>
        <?php endif; ?>

        <div class="mb-6 lg:hidden">
            <p class="text-sm font-semibold">Step <span x-text="step"></span> of 3</p>
            <div class="progress-bar mt-2"><span :style="'width:' + (step/3*100) + '%'"></span></div>
        </div>

        <fieldset class="space-y-4" x-show="show(1)">
            <legend class="font-display text-2xl text-navy mb-2">Your details</legend>
            <div>
                <label class="label" for="full_name">Full name</label>
                <input class="input" id="full_name" name="full_name" required maxlength="150" value="<?= e((string) old('full_name')) ?>">
                <?php if (!empty($errs['full_name'])): ?><p class="help"><?= e($errs['full_name']) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="label" for="company_name">Company name (optional)</label>
                <input class="input" id="company_name" name="company_name" maxlength="150" value="<?= e((string) old('company_name')) ?>">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="label" for="email">Email</label>
                    <input class="input" id="email" name="email" type="email" required value="<?= e((string) old('email')) ?>">
                    <?php if (!empty($errs['email'])): ?><p class="help"><?= e($errs['email']) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="label" for="phone">Phone / WhatsApp</label>
                    <input class="input" id="phone" name="phone" required value="<?= e((string) old('phone')) ?>">
                    <?php if (!empty($errs['phone'])): ?><p class="help"><?= e($errs['phone']) ?></p><?php endif; ?>
                </div>
            </div>
            <div>
                <label class="label" for="country_city">Country / city</label>
                <input class="input" id="country_city" name="country_city" required value="<?= e((string) old('country_city')) ?>">
                <?php if (!empty($errs['country_city'])): ?><p class="help"><?= e($errs['country_city']) ?></p><?php endif; ?>
            </div>
        </fieldset>

        <fieldset class="space-y-4 mt-8" x-show="show(2)">
            <legend class="font-display text-2xl text-navy mb-2">What do you need?</legend>
            <div>
                <label class="label" for="category">Category</label>
                <select class="input" id="category" name="category" required>
                    <option value="">Select a category</option>
                    <?php foreach ($services as $title): ?>
                        <option value="<?= e($title) ?>" <?= old('category') === $title ? 'selected' : '' ?>><?= e($title) ?></option>
                    <?php endforeach; ?>
                    <option value="Other" <?= old('category') === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
                <?php if (!empty($errs['category'])): ?><p class="help"><?= e($errs['category']) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="label" for="item_description">Item description</label>
                <textarea class="input" id="item_description" name="item_description" required minlength="10"><?= e((string) old('item_description')) ?></textarea>
                <?php if (!empty($errs['item_description'])): ?><p class="help"><?= e($errs['item_description']) ?></p><?php endif; ?>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="label" for="quantity">Quantity</label>
                    <input class="input" id="quantity" name="quantity" type="number" min="0.01" step="0.01" required value="<?= e((string) old('quantity')) ?>">
                </div>
                <div>
                    <label class="label" for="unit">Unit</label>
                    <select class="input" id="unit" name="unit" required>
                        <?php foreach (units() as $unit): ?>
                            <option value="<?= e($unit) ?>" <?= old('unit') === $unit ? 'selected' : '' ?>><?= e($unit) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset class="space-y-4 mt-8" x-show="show(3)">
            <legend class="font-display text-2xl text-navy mb-2">Timing & files</legend>
            <div>
                <label class="label" for="budget_range">Budget range (optional)</label>
                <select class="input" id="budget_range" name="budget_range">
                    <option value="">Select if you wish</option>
                    <?php foreach (budget_ranges() as $key => $label): ?>
                        <option value="<?= e($key) ?>" <?= old('budget_range') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="label" for="delivery_date">Required delivery date</label>
                <input class="input" id="delivery_date" name="delivery_date" type="date" value="<?= e((string) old('delivery_date')) ?>">
            </div>
            <div>
                <label class="label" for="notes">Additional notes</label>
                <textarea class="input" id="notes" name="notes"><?= e((string) old('notes')) ?></textarea>
            </div>
            <div>
                <label class="label" for="attachment">Attachment (optional, max 5MB — PDF, DOC, XLS, JPG, PNG)</label>
                <input class="input" id="attachment" name="attachment" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                <?php if (!empty($errs['attachment'])): ?><p class="help"><?= e($errs['attachment']) ?></p><?php endif; ?>
            </div>
            <?php App\View::partial('captcha'); ?>
        </fieldset>

        <div class="mt-8 flex gap-3 lg:hidden">
            <button type="button" class="btn btn-ghost" @click="prev()" :disabled="step===1">Back</button>
            <button type="button" class="btn btn-navy flex-1" x-show="step<3" @click="next()">Continue</button>
            <button type="submit" class="btn btn-primary flex-1" x-show="step===3">Submit request</button>
        </div>
        <div class="mt-8 hidden lg:block">
            <button type="submit" class="btn btn-primary">Submit request</button>
        </div>
        <p data-form-status class="mt-4 text-sm"></p>
    </form>
</section>
