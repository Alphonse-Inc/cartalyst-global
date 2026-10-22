<div class="card p-8">
    <h1 class="font-display text-3xl text-navy">Set up Cartalyst Global</h1>
    <p class="mt-2 text-sm text-navy/70">Create the first admin. Import <code>database/schema.sql</code> and <code>database/seed.sql</code>, and fill <code>config/.env</code> before continuing.</p>
    <?php $errs = errors(); if (!empty($errs['_form'])): ?>
        <p class="mt-4 rounded-xl bg-red-50 p-3 text-red-800"><?= e($errs['_form']) ?></p>
    <?php endif; ?>
    <form class="mt-6 space-y-4" method="post" action="<?= e(base_url('setup')) ?>">
        <?= csrf_field() ?>
        <div>
            <label class="label" for="name">Admin name</label>
            <input class="input" id="name" name="name" value="Site Admin" required>
        </div>
        <div>
            <label class="label" for="email">Admin email</label>
            <input class="input" id="email" name="email" type="email" value="admin@cartalystglobal.com" required>
        </div>
        <div>
            <label class="label" for="password">Password (min 10 characters)</label>
            <input class="input" id="password" name="password" type="password" value="ChangeMeNow!2026" required minlength="10">
            <p class="mt-1 text-xs text-navy/50">Change this immediately after first login.</p>
        </div>
        <button class="btn btn-primary w-full" type="submit">Create admin</button>
    </form>
</div>
